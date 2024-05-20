<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gassiness;
use Illuminate\Support\Facades\Auth;

class GassinessController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'MPR'); // По умолчанию сортировка по 'MPR'
        $direction = $request->get('direction', 'asc'); // По умолчанию 'asc'

        $gassinesses = Gassiness::orderBy($sort, $direction)->paginate(8);

        return view('gassiness.index', compact('gassinesses', 'sort', 'direction'));
    }
    public function create()
    {
        return view('gassiness.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'MPR' => 'required',
            'measurements' => 'required|array|min:10',
            'device' => 'required',
            'factory_number' => 'required',
        ]);

        $gassiness = new Gassiness();
        $gassiness->MPR = $request->input('MPR');
        $gassiness->measurements = $request->input('measurements');
        $gassiness->device = $request->input('device');
        $gassiness->factory_number = $request->input('factory_number');
        $gassiness->user_id = Auth::id();
        $gassiness->user_station_id = Auth::user()->station_id;
        $gassiness->save();

        return redirect()->route('gassiness.index')->with('success', 'Gassiness created successfully!');
    }

    public function edit(Gassiness $gassiness)
    {
        return view('gassiness.edit', compact('gassiness'));
    }

    public function update(Request $request, Gassiness $gassiness)
    {
        $request->validate([
            'MPR' => 'required',
            'measurements' => 'required|array|min:10',
            'device' => 'required',
            'factory_number' => 'required',
        ]);

        $gassiness->MPR = $request->input('MPR');
        $gassiness->measurements = $request->input('measurements');
        $gassiness->device = $request->input('device');
        $gassiness->factory_number = $request->input('factory_number');
        $gassiness->user_id = Auth::id();
        $gassiness->user_station_id = Auth::user()->station_id;
        $gassiness->save();

        return redirect()->route('gassiness.index')->with('success', 'Gassiness updated successfully!');
    }

    public function destroy(Gassiness $gassiness)
    {
        $gassiness->delete();
        return redirect()->route('gassiness.index')->with('success', 'Gassiness deleted successfully!');
    }
}
