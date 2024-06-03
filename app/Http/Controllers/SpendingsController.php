<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Spendings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpendingsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at'); // По умолчанию сортировка по 'created_at'
        $direction = $request->get('direction', 'asc'); // По умолчанию 'asc'

        $spendings = Spendings::orderBy($sort, $direction)->paginate(8);

        return view('spendings.index', compact('spendings'));
    }

    public function create()
    {
        return view('spendings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gas' => 'required|numeric',
            'odorant' => 'required|numeric',
        ], [
            'gas.required' => 'Поле :attribute є обов\'язковим.',
            'gas.numeric' => 'Поле :attribute повинне бути числовим.',
            'odorant.required' => 'Поле :attribute є обов\'язковим.',
            'odorant.numeric' => 'Поле :attribute повинне бути числовим.',
        ]);

        $spending = new Spendings($request->all());
        $spending->user_id = Auth::id();
        $spending->user_station_id = Auth::user()->station_id;

        $spending->save();

        // Логирование создания
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => 'spendings',
            'new_data' => $spending->toJson()
        ]);

        return redirect()->route('spendings.index')
            ->with('success', 'Spending created successfully.');
    }

    public function update(Request $request, Spendings $spending)
    {
        $request->validate([
            'gas' => 'required|numeric',
            'odorant' => 'required|numeric',
        ], [
            'gas.required' => 'Поле :attribute є обов\'язковим.',
            'gas.numeric' => 'Поле :attribute повинне бути числовим.',
            'odorant.required' => 'Поле :attribute є обов\'язковим.',
            'odorant.numeric' => 'Поле :attribute повинне бути числовим.',
        ]);

        $oldData = $spending->toJson();
        $spending->update($request->all());

        // Логирование обновления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'table_name' => 'spendings',
            'old_data' => $oldData,
            'new_data' => $spending->toJson()
        ]);

        return redirect()->route('spendings.index')
            ->with('success', 'Spending updated successfully');
    }

    public function destroy(Spendings $spending)
    {
        $oldData = $spending->toJson();
        $spending->delete();

        // Логирование удаления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => 'spendings',
            'old_data' => $oldData
        ]);

        return redirect()->route('spendings.index')
            ->with('success', 'Spending deleted successfully');
    }

    public function edit(Spendings $spending)
    {
        return view('spendings.edit', compact('spending'));
    }
}
