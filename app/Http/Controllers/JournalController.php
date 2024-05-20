<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $journals = Journal::where('user_id', $request->user()->id)
            ->orderBy($request->sort ?? 'pressure_in', $request->direction ?? 'asc')
            ->paginate(8);
        return view('journals.index', compact('journals'));
    }

    public function create()
    {
        return view('journals.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pressure_in' => 'required|numeric',
            'pressure_out_1' => 'required|numeric',
            'pressure_out_2' => 'nullable|numeric',
            'temperature_1' => 'required|numeric',
            'temperature_2' => 'nullable|numeric',
            'odorant_value_1' => 'required|numeric',
            'odorant_value_2' => 'nullable|numeric',
            'gas_heater_temperature_in' => 'required|numeric',
            'gas_heater_temperature_out' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'user_station_id' => 'required|exists:station,id',
        ]);

        Journal::create($validatedData);

        return redirect()->route('journals.index')
            ->with('success','Journal created successfully.');
    }

    public function show(Journal $journal)
    {
        return view('journals.show', compact('journal'));
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
            'temperature_2' => 'nullable|numeric',
            'odorant_value_1' => 'required|numeric',
            'odorant_value_2' => 'nullable|numeric',
            'gas_heater_temperature_in' => 'required|numeric',
            'gas_heater_temperature_out' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'user_station_id' => 'required|exists:station,id',
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
        $journal->user_id = $request->input('user_id');
        $journal->user_station_id = $request->input('user_station_id');
        $journal->save();

        return redirect()->route('journals.index')->with('success', 'Journal updated successfully');
    }

    public function destroy(Journal $journal)
    {
        $journal->delete();

        return redirect()->route('journals.index')
            ->with('success','Journal deleted successfully');
    }
}
