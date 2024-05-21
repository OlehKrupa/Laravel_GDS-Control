<?php

namespace App\Http\Controllers;

use App\Models\SelfSpendings;
use Illuminate\Http\Request;

class SelfSpendingsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'heater_time'); // По умолчанию сортировка по 'heater_time'
        $direction = $request->get('direction', 'asc'); // По умолчанию 'asc'

        $selfSpendings = SelfSpendings::orderBy($sort, $direction)->paginate(8);

        return view('selfSpendings.index', compact('selfSpendings'));
    }

    public function create()
    {
        return view('selfSpendings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'heater_time' => 'required|numeric',
            'boiler_time' => 'required|numeric',
            'heater_gas' => 'required|numeric',
            'boiler_gas' => 'required|numeric',
        ]);

        $selfSpending = new SelfSpendings($request->all());
        $selfSpending->user_id = auth()->user()->id;
        $selfSpending->user_station_id = auth()->user()->station_id;
        $selfSpending->save();

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
            'heater_time' => 'required|numeric',
            'boiler_time' => 'required|numeric',
            'heater_gas' => 'required|numeric',
            'boiler_gas' => 'required|numeric',
        ]);

        $selfSpending->update($request->all());

        return redirect()->route('selfSpendings.index')
            ->with('success', 'Self Spending updated successfully.');
    }

    public function destroy(SelfSpendings $selfSpending)
    {
        $selfSpending->delete();

        return redirect()->route('selfSpendings.index')
            ->with('success', 'Self Spending deleted successfully.');
    }
}
