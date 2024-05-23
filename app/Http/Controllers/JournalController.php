<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $days = $request->input('days', 1);
        $userStationId = $request->input('user_station_id');

        $query = Journal::where('created_at', '>=', now()->subDays($days));

        if ($userStationId) {
            $query->where('user_station_id', $userStationId);
        }

        $journals = $query->orderBy($sort, $direction)->paginate(8);
        $stations = Station::all();

        return view('journals.index', compact('journals', 'stations'));
    }

    public function create()
    {
        return view('journals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pressure_in' => 'required|numeric',
            'pressure_out_1' => 'required|numeric',
            'pressure_out_2' => 'nullable|numeric',
            'temperature_1' => 'required|numeric',
            'temperature_2' => 'required|numeric',
            'odorant_value_1' => 'nullable|numeric',
            'odorant_value_2' => 'nullable|numeric',
            'gas_heater_temperature_in' => 'nullable|numeric',
            'gas_heater_temperature_out' => 'nullable|numeric',
        ], [
            "pressure_in.required" => "Поле Тиск вх. обов'язкове для заповнення.",
            "pressure_in.numeric" => "Поле Тиск вх. повинне бути числовим.",
            "pressure_out_1.required" => "Поле Тиск вих. I обов'язкове для заповнення.",
            "pressure_out_1.numeric" => "Поле Тиск вих. I повинне бути числовим.",
            "pressure_out_2.numeric" => "Поле Тиск вих. II повинне бути числовим.",
            "temperature_1.required" => "Поле ℃ вих. I обов'язкове для заповнення.",
            "temperature_1.numeric" => "Поле ℃ вих. I повинне бути числовим.",
            "temperature_2.required" => "Поле ℃ вих. II обов'язкове для заповнення.",
            "temperature_2.numeric" => "Поле ℃ вих. II повинне бути числовим.",
            "odorant_value_1.numeric" => "Поле Рівень одоранту I повинне бути числовим.",
            "odorant_value_2.numeric" => "Поле Рівень одоранту II повинне бути числовим.",
            "gas_heater_temperature_in.numeric" => "Поле ℃ вх. ПГ повинне бути числовим.",
            "gas_heater_temperature_out.numeric" => "Поле ℃ вих. ПГ повинне бути числовим.",
        ]);


        $journal = new Journal();
        $journal->pressure_in = $request->input('pressure_in');
        $journal->pressure_out_1 = $request->input('pressure_out_1');
        $journal->pressure_out_2 = $request->input('pressure_out_2');
        $journal->temperature_1 = $request->input('temperature_1');
        $journal->temperature_2 = $request->input('temperature_2');
        $journal->odorant_value_1 = $request->input('odorant_value_1');
        $journal->odorant_value_2 = $request->input('odorant_value_2');
        $journal->gas_heater_temperature_in = $request->input('gas_heater_temperature_in');
        $journal->gas_heater_temperature_out = $request->input('gas_heater_temperature_out');
        $journal->user_id = Auth::id();
        $journal->user_station_id = Auth::user()->station_id;
        $journal->save();

        return redirect()->route('journals.index')->with('success', 'Запис створено!');
    }

    public function edit(Journal $journal)
    {
        return view('journals.edit', compact('journal'));
    }

    public function update(Request $request, Journal $journal)
    {
        $request->validate([
            'pressure_in' => 'required|numeric',
            'pressure_out_1' => 'required|numeric',
            'pressure_out_2' => 'nullable|numeric',
            'temperature_1' => 'required|numeric',
            'temperature_2' => 'required|numeric',
            'odorant_value_1' => 'nullable|numeric',
            'odorant_value_2' => 'nullable|numeric',
            'gas_heater_temperature_in' => 'nullable|numeric',
            'gas_heater_temperature_out' => 'nullable|numeric',
        ], [
            "pressure_in.required" => "Поле Тиск вх. обов'язкове для заповнення.",
            "pressure_in.numeric" => "Поле Тиск вх. повинне бути числовим.",
            "pressure_out_1.required" => "Поле Тиск вих. I обов'язкове для заповнення.",
            "pressure_out_1.numeric" => "Поле Тиск вих. I повинне бути числовим.",
            "pressure_out_2.numeric" => "Поле Тиск вих. II повинне бути числовим.",
            "temperature_1.required" => "Поле ℃ вих. I обов'язкове для заповнення.",
            "temperature_1.numeric" => "Поле ℃ вих. I повинне бути числовим.",
            "temperature_2.required" => "Поле ℃ вих. II обов'язкове для заповнення.",
            "temperature_2.numeric" => "Поле ℃ вих. II повинне бути числовим.",
            "odorant_value_1.numeric" => "Поле Рівень одоранту I повинне бути числовим.",
            "odorant_value_2.numeric" => "Поле Рівень одоранту II повинне бути числовим.",
            "gas_heater_temperature_in.numeric" => "Поле ℃ вх. ПГ повинне бути числовим.",
            "gas_heater_temperature_out.numeric" => "Поле ℃ вих. ПГ повинне бути числовим.",
        ]);

        $journal->pressure_in = $request->input('pressure_in');
        $journal->pressure_out_1 = $request->input('pressure_out_1');
        $journal->pressure_out_2 = $request->input('pressure_out_2');
        $journal->temperature_1 = $request->input('temperature_1');
        $journal->temperature_2 = $request->input('temperature_2');
        $journal->odorant_value_1 = $request->input('odorant_value_1');
        $journal->odorant_value_2 = $request->input('odorant_value_2');
        $journal->gas_heater_temperature_in = $request->input('gas_heater_temperature_in');
        $journal->gas_heater_temperature_out = $request->input('gas_heater_temperature_out');
        $journal->user_id = Auth::id();
        $journal->user_station_id = Auth::user()->station_id;
        $journal->save();

        return redirect()->route('journals.index')->with('success', 'Запис оновлено!');
    }

    public function destroy(Journal $journal)
    {
        $journal->delete();

        return redirect()->route('journals.index')->with('success', 'Запис видалено!');
    }
}
