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
            'value' => 'required|numeric|max:255',
        ], [
            "value.required" => "Значення обов'язкове для заповнення",
            "value.string" => "Значення має бути числом",
            "value.max" => "Значення занадто довге",
        ]);

        $setting->update(['value' => $request->value]);
        return redirect()->route('settings.index')->with('success', 'Значення оновлено вдало!');
    }

    public function destroy(Settings $setting)
    {
        $setting->delete();
        return redirect()->route('settings.index')->with('success', 'Значення видалено!');
    }
}
