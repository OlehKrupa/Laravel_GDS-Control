<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use App\Models\Station;
use App\Models\Spendings;
use App\Models\SelfSpendings;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stations = Station::all();
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

        return response()->json(['fields' => $fields]);
    }

    private function getFieldsByModel($model)
    {
        $fields = [];

        switch ($model) {
            case 'Journal':
                $fields = [
                    'pressure_in' ,
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
                // добавить логику для других моделей
                break;
        }

        return $fields;
    }

    public function getChartData(Request $request)
    {
        $station_id = $request->input('station_id');
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
                // add logic for other models
                break;
        }
    }
}
