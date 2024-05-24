<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'label'); // По умолчанию сортировка по 'label'
        $direction = $request->get('direction', 'asc'); // По умолчанию 'asc'

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
        'label.required' => 'Поле мітка є обов\'язковим.',
        'city.required' => 'Поле місто є обов\'язковим.',
        'region.required' => 'Поле регіон є обов\'язковим.',
        'type.required' => 'Поле тип є обов\'язковим.',
    ]);

        Station::create($request->all());

        return redirect()->route('stations.index')
            ->with('success', 'Station created successfully.');
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
            'label.required' => 'Поле мітка є обов\'язковим.',
            'city.required' => 'Поле місто є обов\'язковим.',
            'region.required' => 'Поле регіон є обов\'язковим.',
            'type.required' => 'Поле тип є обов\'язковим.',
        ]);

        $station->update($request->all());

        return redirect()->route('stations.index')
            ->with('success', 'Station updated successfully');
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return redirect()->route('stations.index')
            ->with('success', 'Station deleted successfully');
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
                        'City' => $station->city,
                        'Count' => $station->count,
                    ];
                });
                break;
            case 'stations_by_region':
                $stations = Station::select('region', DB::raw('count(*) as count'))
                    ->groupBy('region')
                    ->get();
                $reportData = $stations->map(function ($station) {
                    return [
                        'Region' => $station->region,
                        'Count' => $station->count,
                    ];
                });
                break;
            case 'stations_by_type':
                $stations = Station::select('type', DB::raw('count(*) as count'))
                    ->groupBy('type')
                    ->get();
                $reportData = $stations->map(function ($station) {
                    return [
                        'Type' => $station->type,
                        'Count' => $station->count,
                    ];
                });
                break;
            default:
                return redirect()->back()->withErrors(['Invalid report type']);
        }

        $fileName = "stations_{$reportType}.xlsx";

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

        // Сохранение файла во временное место
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        // Чтение содержимого файла
        $fileContent = file_get_contents($tempFile);

        // Удаление временного файла
        unlink($tempFile);

        $response = response($fileContent, 200);
        $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->header('Content-Disposition', "attachment; filename={$fileName}");
        return $response;
    }
}
