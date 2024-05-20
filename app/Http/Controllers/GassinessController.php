<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gassiness;

class GassinessController extends Controller
{
    public function index()
    {
        $gassinesses = Gassiness::all();
        return view('gassiness.index', compact('gassinesses'));
    }

    public function create()
    {
        return view('gassiness.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'MPR' => 'required',
            'measurements' => 'required|array|min:20',
            'device' => 'required',
            'factory_number' => 'required',
            'user_id' => 'required',
            'user_station_id' => 'required',
        ]);

        $gassiness = new Gassiness();
        $gassiness->MPR = $request->input('MPR');
        $gassiness->measurements = $request->input('measurements');
        $gassiness->device = $request->input('device');
        $gassiness->factory_number = $request->input('factory_number');
        $gassiness->user_id = $request->input('user_id');
        $gassiness->user_station_id = $request->input('user_station_id');
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
            'MPR' => 'equired',
            'measurements' => 'equired|array|min:20',
            'device' => 'equired',
            'factory_number' => 'equired',
            'user_id' => 'equired',
            'user_station_id' => 'equired',
        ]);

        $gassiness->MPR = $request->input('MPR');
        $gassiness->measurements = $request->input('measurements');
        $gassiness->device = $request->input('device');
        $gassiness->factory_number = $request->input('factory_number');
        $gassiness->user_id = $request->input('user_id');
        $gassiness->user_station_id = $request->input('user_station_id');
        $gassiness->save();

        return redirect()->route('gassiness.index')->with('success', 'Gassiness updated successfully!');
    }

    public function destroy(Gassiness $gassiness)
    {
        $gassiness->delete();
        return redirect()->route('gassiness.index')->with('success', 'Gassiness deleted successfully!');
    }
}
