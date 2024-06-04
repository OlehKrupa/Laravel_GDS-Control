<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use App\Models\Gassiness;
use Illuminate\Support\Facades\Auth;

class GassinessController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'MPR'); // По умолчанию сортировка по 'MPR'
        $direction = $request->get('direction', 'asc'); // По умолчанию 'asc'
        $days = $request->input('days', 1);
        $userStationId = $request->input('user_station_id');

        $query = Gassiness::orderBy($sort, $direction);

        if ($userStationId) {
            $query->where('user_station_id', $userStationId);
        }

        // Применяем фильтр по дате
        $gassinesses = $query->where('created_at', '>=', now()->subDays($days))->paginate(8);

        $stations = Station::all(); // Получаем все станции

        return view('gassiness.index', compact('gassinesses', 'sort', 'direction', 'stations'));
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
        ], [
            "MPR.required" => "Поле :attribute є обов'язковим.",
            "measurements.required" => "Поле :attribute є обов'язковим.",
            "measurements.array" => "Поле :attribute має бути масивом.",
            "measurements.min" => "Поле :attribute повинне містити принаймні :min значень.",
            "device.required" => "Поле :attribute є обов'язковим.",
            "factory_number.required" => "Поле :attribute є обов'язковим.",
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
        ], [
            "MPR.required" => "Поле :attribute є обов'язковим.",
            "measurements.required" => "Поле :attribute є обов'язковим.",
            "measurements.array" => "Поле :attribute має бути масивом.",
            "measurements.min" => "Поле :attribute повинне містити принаймні :min значень.",
            "device.required" => "Поле :attribute є обов'язковим.",
            "factory_number.required" => "Поле :attribute є обов'язковим.",
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
