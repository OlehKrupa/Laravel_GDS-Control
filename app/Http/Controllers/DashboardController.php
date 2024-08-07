<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Models\Journal;
use App\Models\Station;
use App\Models\Spendings;
use App\Models\SelfSpendings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->can('use stations') && !$user->hasRole('OPERATOR')) {
            $stations = Station::all();
        } else {
            $stations = Station::where('id', $user->station_id)->get();
        }

        $models = [
            'Journal' => 'journal',
            'Spendings' => 'spendings',
            'SelfSpendings' => 'self_spendings',
        ];

        return view('dashboard', compact('stations', 'models'));
    }


    public function getFields(Request $request)
    {
        $model = $request->input('model');
        $fields = $this->getFieldsByModel($model);

        $translatedFields = [];
        foreach ($fields as $field) {
            $translatedFields[] = [
                'original' => $field,
                'translated' => __($field),
            ];
        }

        return response()->json(['fields' => $translatedFields]);
    }

    private function getFieldsByModel($model)
    {
        $fields = [];

        switch ($model) {
            case 'Journal':
                $fields = [
                    'pressure_in',
                    'pressure_out_1',
                    'pressure_out_2',
                    'temperature_1',
                    'temperature_2',
                    'odorant_value_1',
                    'odorant_value_2',
                    'gas_heater_temperature_in',
                    'gas_heater_temperature_out',
                ];
                break;
            case 'Spendings':
                $fields = [
                    'gas',
                    'odorant',
                ];
                break;
            case 'SelfSpendings':
                $fields = [
                    'heater_time',
                    'boiler_time',
                    'heater_gas',
                    'boiler_gas',
                ];
                break;
            default:
                break;
        }

        return $fields;
    }

    public function getChartData(Request $request)
    {
        $user = Auth::user();

        if ($user->cannot('use stations') || $user->hasRole('OPERATOR')) {
            $station_id = $user->station_id;
        } else {
            $station_id = $request->input('station_id');
        }

        $days = $request->input('days');
        $model = $request->input('model');
        $field = $request->input('field');

        $data = $this->getData($station_id, $days, $model, $field);

        return response()->json($data);
    }

    private function getData($station_id, $days, $model, $field)
    {
        $query = $this->getModelQuery($model)
            ->where('user_station_id', $station_id)
            ->whereBetween('created_at', [Carbon::now()->subDays($days), Carbon::now()]);

        $data = $query->get()->pluck($field, 'created_at')->toArray();

        return $data;
    }

    private function getModelQuery($model)
    {
        switch ($model) {
            case 'Journal':
                return Journal::query();
            case 'Spendings':
                return Spendings::query();
            case 'SelfSpendings':
                return SelfSpendings::query();
            default:
                break;
        }
    }

    public function getForecastData(Request $request)
    {
        $user = Auth::user();

        if ($user->cannot('use stations') || $user->hasRole('OPERATOR')) {
            $station_id = $user->station_id;
        } else {
            $station_id = $request->input('station_id');
        }

        $days = $request->input('days');
        $model = $request->input('model');
        $field = $request->input('field');
        $forecastDepth = $request->input('forecastDepth', 1);

        $actualData = $this->getData($station_id, $days, $model, $field);
        $forecastData = $this->getForecast(array_values($actualData), $forecastDepth);

        return response()->json([
            'actualData' => $actualData,
            'forecastData' => $forecastData,
        ]);
    }


    private function getForecast($data, $depth)
    {
        $alphaSetting = Settings::where('name', 'alpha')->first();
        $betaSetting = Settings::where('name', 'beta')->first();

        if ($alphaSetting && $betaSetting) {
            $alpha = (float)$alphaSetting->value;
            $beta = (float)$betaSetting->value;
        } else {
            $alpha = 0.5;
            $beta = 0.5;
        }

        // Начальные значения уровня и тренда
        $level = $data[0];
        $trend = $data[1] - $data[0];

        $forecastData = [];

        // Прогнозирование с использованием исторических данных
        foreach ($data as $value) {
            $prev_level = $level;
            $level = $alpha * $value + (1 - $alpha) * ($level + $trend);
            $trend = $beta * ($level - $prev_level) + (1 - $beta) * $trend;
            $forecastData[] = round($level + $trend, 2);
        }

        // Прогнозирование будущих значений
        $last_level = end($forecastData);
        for ($i = 0; $i < $depth; $i++) {
            $forecast = $last_level + ($i + 1) * $trend;
            $forecastData[] = round($forecast, 2);
        }

        return $forecastData;
    }

}
