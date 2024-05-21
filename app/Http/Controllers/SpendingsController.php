<?php

namespace App\Http\Controllers;

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
        ]);

        $spending = new Spendings($request->all());
        $spending->user_id = Auth::id();
        $spending->user_station_id = Auth::user()->station_id; // Assuming user has a station_id

        $spending->save();

        return redirect()->route('spendings.index')
            ->with('success', 'Spending created successfully.');
    }

    public function edit(Spendings $spending)
    {
        return view('spendings.edit', compact('spending'));
    }

    public function update(Request $request, Spendings $spending)
    {
        $request->validate([
            'gas' => 'required|numeric',
            'odorant' => 'required|numeric',
        ]);

        $spending->update($request->all());

        return redirect()->route('spendings.index')
            ->with('success', 'Spending updated successfully');
    }

    public function destroy(Spendings $spending)
    {
        $spending->delete();

        return redirect()->route('spendings.index')
            ->with('success', 'Spending deleted successfully');
    }
}
