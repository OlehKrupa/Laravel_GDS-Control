<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at'); // По умолчанию сортировка по 'created_at'
        $direction = $request->get('direction', 'desc'); // По умолчанию 'desc'

        $journals = Journal::orderBy($sort, $direction)->paginate(8);

        return view('journals.index', compact('journals'));
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
            'pressure_out_2' => 'required|numeric',
            'temperature_1' => 'required|numeric',
            'temperature_2'=> 'required|numeric',
            'odorant_value_1' => 'required|numeric',
            'odorant_value_2' => 'required|numeric',
            'gas_heater_temperature_in' => 'required|numeric',
            'gas_heater_temperature_out' => 'required|numeric',
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

        return redirect()->route('journals.index')->with('success', 'Journal created successfully!');
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
            'pressure_out_2' => 'required|numeric',
            'temperature_1' => 'required|numeric',
            'temperature_2' => 'required|numeric',
            'odorant_value_1' => 'required|numeric',
            'odorant_value_2' => 'required|numeric',
            'gas_heater_temperature_in' => 'required|numeric',
            'gas_heater_temperature_out' => 'required|numeric',
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

        return redirect()->route('journals.index')->with('success', 'Journal updated successfully!');
    }

    public function destroy(Journal $journal)
    {
        $journal->delete();

        return redirect()->route('journals.index')->with('success', 'Journal deleted successfully!');
    }
}
