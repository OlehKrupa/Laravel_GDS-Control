<?php

namespace App\Http\Controllers;

use App\Models\SelfSpendings;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class SelfSpendingsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at'); // По умолчанию сортировка по 'created_at'
        $direction = $request->get('direction', 'asc'); // По умолчанию 'asc'
        $days = $request->input('days', 1);
        $userStationId = $request->input('user_station_id');

        $query = SelfSpendings::orderBy($sort, $direction);

        if ($userStationId) {
            $query->where('user_station_id', $userStationId);
        }

        // Применяем фильтр по дате
        $selfSpendings = $query->where('created_at', '>=', now()->subDays($days))->paginate(10);

        $stations = Station::all();

        return view('selfSpendings.index', compact('selfSpendings', 'sort', 'direction', 'stations'));
    }

    public function create()
    {
        return view('selfSpendings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "heater_time" => "required|numeric",
            "boiler_time" => "required|numeric",
            "heater_gas" => "required|numeric",
            "boiler_gas" => "required|numeric",
        ], [
            "heater_time.required" => "Поле :attribute є обов'язковим.",
            "heater_time.numeric" => "Поле :attribute повинне бути числовим.",
            "boiler_time.required" => "Поле :attribute є обов'язковим.",
            "boiler_time.numeric" => "Поле :attribute повинне бути числовим.",
            "heater_gas.required" => "Поле :attribute є обов'язковим.",
            "heater_gas.numeric" => "Поле :attribute повинне бути числовим.",
            "boiler_gas.required" => "Поле :attribute є обов'язковим.",
            "boiler_gas.numeric" => "Поле :attribute повинне бути числовим.",
        ]);

        $selfSpending = new SelfSpendings($request->all());
        $selfSpending->user_id = auth()->user()->id;
        $selfSpending->user_station_id = auth()->user()->station_id;
        $selfSpending->save();

        // Логирование создания
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => 'self_spendings',
            'new_data' => $selfSpending->toJson()
        ]);

        return redirect()->route('selfSpendings.index')
            ->with('success', 'Self Spending created successfully.');
    }

    public function edit(SelfSpendings $selfSpending)
    {
        return view('selfSpendings.edit', compact('selfSpending'));
    }

    public function update(Request $request, SelfSpendings $selfSpending)
    {
        $request->validate([
            "heater_time" => "required|numeric",
            "boiler_time" => "required|numeric",
            "heater_gas" => "required|numeric",
            "boiler_gas" => "required|numeric",
        ], [
            "heater_time.required" => "Поле :attribute є обов'язковим.",
            "heater_time.numeric" => "Поле :attribute повинне бути числовим.",
            "boiler_time.required" => "Поле :attribute є обов'язковим.",
            "boiler_time.numeric" => "Поле :attribute повинне бути числовим.",
            "heater_gas.required" => "Поле :attribute є обов'язковим.",
            "heater_gas.numeric" => "Поле :attribute повинне бути числовим.",
            "boiler_gas.required" => "Поле :attribute є обов'язковим.",
            "boiler_gas.numeric" => "Поле :attribute повинне бути числовим.",
        ]);

        $oldData = $selfSpending->toJson();
        $selfSpending->update($request->all());

        // Логирование обновления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'table_name' => 'self_spendings',
            'old_data' => $oldData,
            'new_data' => $selfSpending->toJson()
        ]);

        return redirect()->route('selfSpendings.index')
            ->with('success', 'Self Spending updated successfully.');
    }

    public function destroy(SelfSpendings $selfSpending)
    {
        $oldData = $selfSpending->toJson();
        $selfSpending->delete();

        // Логирование удаления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => 'self_spendings',
            'old_data' => $oldData
        ]);

        return redirect()->route('selfSpendings.index')
            ->with('success', 'Self Spending deleted successfully.');
    }
}
