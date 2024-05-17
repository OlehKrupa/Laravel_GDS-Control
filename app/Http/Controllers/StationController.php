<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'label'); // По умолчанию сортировка по 'label'
        $direction = $request->get('direction', 'asc'); // По умолчанию 'asc'

        $stations = Station::orderBy($sort, $direction)->paginate(8);

        return view('stations.index', compact('stations'));
    }

    public function create()
    {
        return view('stations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required',
            'city' => 'required',
            'region' => 'required',
            'type' => 'required',
        ]);

        Station::create($request->all());

        return redirect()->route('stations.index')
            ->with('success', 'Station created successfully.');
    }

    public function edit(Station $station)
    {
        return view('stations.edit', compact('station'));
    }

    public function update(Request $request, Station $station)
    {
        $request->validate([
            'label' => 'required',
            'city' => 'required',
            'region' => 'required',
            'type' => 'required',
        ]);

        $station->update($request->all());

        return redirect()->route('stations.index')
            ->with('success', 'Station updated successfully');
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return redirect()->route('stations.index')
            ->with('success', 'Station deleted successfully');
    }
}
