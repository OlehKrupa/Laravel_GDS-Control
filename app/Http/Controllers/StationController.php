<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'label');
        $direction = $request->get('direction', 'asc');

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
        ], [
            'label.required' => __('Поле мітка є обов\'язковим.'),
            'city.required' => __('Поле місто є обов\'язковим.'),
            'region.required' => __('Поле регіон є обов\'язковим.'),
            'type.required' => __('Поле тип є обов\'язковим.'),
        ]);

        $station = Station::create($request->all());

        // Логирование создания
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => 'station',
            'new_data' => $station->toJson()
        ]);

        return redirect()->route('stations.index')
            ->with('success', __('Station created successfully.'));
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
        ], [
            'label.required' => __('Поле мітка є обов\'язковим.'),
            'city.required' => __('Поле місто є обов\'язковим.'),
            'region.required' => __('Поле регіон є обов\'язковим.'),
            'type.required' => __('Поле тип є обов\'язковим.'),
        ]);

        $oldData = $station->toJson();
        $station->update($request->all());

        // Логирование обновления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'table_name' => 'station',
            'old_data' => $oldData,
            'new_data' => $station->toJson()
        ]);

        return redirect()->route('stations.index')
            ->with('success', __('Station updated successfully'));
    }

    public function destroy(Station $station)
    {
        $oldData = $station->toJson();
        $station->delete();

        // Логирование удаления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => 'station',
            'old_data' => $oldData
        ]);

        return redirect()->route('stations.index')
            ->with('success', __('Station deleted successfully'));
    }

    public function generate(Request $request)
    {
        $reportType = $request->input('report_type');

        switch ($reportType) {
            case 'stations_by_city':
                $stations = Station::select('city', DB::raw('count(*) as count'))
                    ->groupBy('city')
                    ->get();
                $reportData = $stations->map(function ($station) {
                    return [
                        __('City') => $station->city,
                        __('Count') => $station->count,
                    ];
                });
                $fileName = "Станції_по_місту.xlsx";
                break;
            case 'stations_by_region':
                $stations = Station::select('region', DB::raw('count(*) as count'))
                    ->groupBy('region')
                    ->get();
                $reportData = $stations->map(function ($station) {
                    return [
                        __('Region') => $station->region,
                        __('Count') => $station->count,
                    ];
                });
                $fileName = "Станції_по_регіону.xlsx";
                break;
            case 'stations_by_type':
                $stations = Station::select('type', DB::raw('count(*) as count'))
                    ->groupBy('type')
                    ->get();
                $reportData = $stations->map(function ($station) {
                    return [
                        __('Type') => $station->type,
                        __('Count') => $station->count,
                    ];
                });
                $fileName = "Станції_по_типу.xlsx";
                break;
            default:
                return redirect()->back()->withErrors([__('Invalid report type')]);
        }

        // Додавання рядка зі сумою станцій
        $totalStations = $stations->sum('count');
        $totalRow = [__('Total Stations'), $totalStations];
        $reportData->push($totalRow);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $header = [];
        foreach ($reportData->first() as $key => $value) {
            $header[] = $key;
        }
        $sheet->fromArray($header, null, 'A1');

        $data = $reportData->toArray();
        $sheet->fromArray($data, null, 'A2');

        $writer = new Xlsx($spreadsheet);

        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        $fileContent = file_get_contents($tempFile);

        unlink($tempFile);

        $response = response($fileContent, 200);
        $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->header('Content-Disposition', "attachment; filename={$fileName}");
        return $response;
    }
}
