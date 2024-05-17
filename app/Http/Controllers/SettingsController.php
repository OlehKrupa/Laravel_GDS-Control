<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::all();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request, Settings $setting)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $setting->update(['value' => $request->value]);
        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }

    public function destroy(Settings $setting)
    {
        $setting->delete();
        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully.');
    }
}
