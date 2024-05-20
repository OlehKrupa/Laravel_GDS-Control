<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Notes::latest()->paginate(10);
        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'operational_switching' => 'nullable|string|required_without_all:received_orders,completed_works,visits_by_outsiders,inspection_of_pressure_tanks',
            'received_orders' => 'nullable|string|required_without_all:operational_switching,completed_works,visits_by_outsiders,inspection_of_pressure_tanks',
            'completed_works' => 'nullable|string|required_without_all:operational_switching,received_orders,visits_by_outsiders,inspection_of_pressure_tanks',
            'visits_by_outsiders' => 'nullable|string|required_without_all:operational_switching,received_orders,completed_works,inspection_of_pressure_tanks',
            'inspection_of_pressure_tanks' => 'nullable|string|required_without_all:operational_switching,received_orders,completed_works,visits_by_outsiders',
        ], [
            'required_without_all' => __('one_field_required'),
        ]);

        $note = new Notes();
        $note->operational_switching = $request->input('operational_switching');
        $note->received_orders = $request->input('received_orders');
        $note->completed_works = $request->input('completed_works');
        $note->visits_by_outsiders = $request->input('visits_by_outsiders');
        $note->inspection_of_pressure_tanks = $request->input('inspection_of_pressure_tanks');
        $note->user_id = Auth::id();
        $note->user_station_id = Auth::user()->station_id;
        $note->save();

        return redirect()->route('notes.index')->with('success', 'Note created successfully!');
    }

    public function edit(Notes $note)
    {
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Notes $note)
    {
        $request->validate([
            'operational_switching' => 'nullable|string|required_without:received_orders,completed_works,visits_by_outsiders,inspection_of_pressure_tanks',
            'received_orders' => 'nullable|string|required_without:operational_switching,completed_works,visits_by_outsiders,inspection_of_pressure_tanks',
            'completed_works' => 'nullable|string|required_without:operational_switching,received_orders,visits_by_outsiders,inspection_of_pressure_tanks',
            'visits_by_outsiders' => 'nullable|string|required_without:operational_switching,received_orders,completed_works,inspection_of_pressure_tanks',
            'inspection_of_pressure_tanks' => 'nullable|string|required_without:operational_switching,received_orders,completed_works,visits_by_outsiders',
        ], [
            'required_without' => __('one_field_required'),
        ]);

        $note->operational_switching = $request->input('operational_switching');
        $note->received_orders = $request->input('received_orders');
        $note->completed_works = $request->input('completed_works');
        $note->visits_by_outsiders = $request->input('visits_by_outsiders');
        $note->inspection_of_pressure_tanks = $request->input('inspection_of_pressure_tanks');
        $note->user_id = Auth::id();
        $note->user_station_id = Auth::user()->station_id;
        $note->save();

        return redirect()->route('notes.index')->with('success', 'Note updated successfully!');
    }

    public function destroy(Notes $note)
    {
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note deleted successfully!');
    }
}
