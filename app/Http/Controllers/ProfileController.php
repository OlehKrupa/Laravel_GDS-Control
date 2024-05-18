<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Role;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $stations = Station::all();
        $roles = Role::all();
        $userStation = $stations->firstWhere('id', $user->station_id);

        return view('profile.edit', [
            'user' => $user,
            'userRoles' => $user->roles,
            'userStation' => $userStation,
            'isAdmin' => $user->hasRole('admin'),
        ], compact('stations', 'roles'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $roles = $request->input('roles', []); // Получаем выбранные роли из запроса
        $user->roles()->sync($roles); // Назначаем выбранные роли пользователю

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
