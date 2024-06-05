<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with('station', 'roles');

        if ($request->input('station_id')) {
            $query->where('station_id', $request->input('station_id'));
        }

        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        }

        $users = $query->paginate(10);
        $stations = Station::all();

        return view('admin.users.index', compact('users', 'stations'));
    }


    /**
     * Display the registration view.
     */
    public function create()
    {
        $stations = Station::all();
        $roles = Role::all();
        return view('admin.users.create', compact('stations', 'roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'patronymic' => ['required', 'string', 'max:255'],
            'station_id' => ['required', 'exists:station,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'roles' => ['required', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'station_id' => $request->station_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $roles = Role::whereIn('id', $request->roles)->get();

        foreach ($roles as $role) {
            $user->assignRole($role->name);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        $stations = Station::all();
        $userRoles = $user->roles;
        $userStation = $user->station;

        return view('admin.users.edit', compact('user', 'roles', 'stations', 'userRoles', 'userStation'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(ProfileUpdateRequest $request, User $user): RedirectResponse
    {
        $user->fill($request->validated());

        if ($request->input('email') !== $user->email) {
            $user->email_verified_at = null;
        }

        if ($request->user()->can('edit user station')) {
            $user->station_id = $request->input('station_id');
        }

        if ($request->user()->can('edit roles')) {
            $roles = $request->input('roles', []);
            $roleNames = Role::whereIn('id', $roles)->pluck('name')->toArray();

            if ($user->hasRole('ADMIN')) {
                if (!in_array('ADMIN', $roleNames)) {
                    $roleNames[] = 'ADMIN';
                }
            }

            $user->syncRoles($roleNames);
        }

        $user->save();

        return Redirect::route('admin.users.edit', $user)->with('status', 'profile-updated');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Display a listing of the soft deleted users.
     */
    public function trashed(Request $request): View
    {
        $query = User::onlyTrashed()->with('station', 'roles');

        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        }

        $users = $query->paginate(10);

        return view('admin.users.trashed', compact('users'));
    }


    /**
     * Restore the specified soft deleted user.
     */
    public function restore($id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.trashed')->with('success', 'User restored successfully.');
    }

}
