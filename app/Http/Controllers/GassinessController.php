<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use App\Models\Gassiness;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GassinessController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $days = $request->input('days', 1);
        $user = Auth::user();
        $query = Gassiness::where('created_at', '>=', now()->subDays($days))
            ->with(['station', 'user']); // Загрузить связанные модели

        if ($user->hasRole('OPERATOR') && $user->roles->count() === 1) {
            $query->where('user_id', $user->id)->where('user_station_id', $user->station_id);
        } else {
            $userStationId = $request->input('user_station_id');
            if ($userStationId) {
                $query->where('user_station_id', $userStationId);
            }
        }

        $gassinesses = $query->orderBy($sort, $direction)->paginate(8);
        $stations = Station::all();

        return view('gassiness.index', compact('gassinesses', 'stations'));
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

    public function generateReport(Request $request)
    {
        $days = $request->input('days', 1);
        $userStationId = $request->input('user_station_id');

        $query = Gassiness::where('created_at', '>=', now()->subDays($days));

        if ($userStationId) {
            $query->where('user_station_id', $userStationId);
        }

        $gassinesses = $query->get();
        $stations = Station::pluck('label', 'id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(__('Gassiness Report'));

        $reportPeriod = __('for the last ') . $days . __(' days');

        $row = 2;
        $foundIssue = false;

        foreach ($gassinesses as $gassiness) {
            $header = [__('Station'), __('Created At'), __('MPR'), __('Measurements')];
            $sheet->fromArray($header, null, 'A1');

            $hasProblem = false;
            foreach ($gassiness->measurements as $measurement) {
                if ($measurement >= $gassiness->MPR) {
                    $stationLabel = $stations[$gassiness->user_station_id] ?? $gassiness->user_station_id;
                    $sheet->setCellValue('A' . $row, $stationLabel);
                    $sheet->setCellValue('B' . $row, $gassiness->created_at);
                    $sheet->setCellValue('C' . $row, $gassiness->MPR);
                    $formattedMeasurements = implode(', ', $gassiness->measurements);
                    $sheet->setCellValue('D' . $row, $formattedMeasurements);

                    $row++;
                    $foundIssue = true;
                    $hasProblem = true;
                    break;
                }
            }

            if (!$hasProblem) {
                $stationLabel = $stations[$gassiness->user_station_id] ?? $gassiness->user_station_id;
                $sheet->setCellValue('A' . $row, $stationLabel);
                $sheet->setCellValue('B' . $row, $gassiness->created_at);
                $sheet->setCellValue('C' . $row, $gassiness->MPR);
                $sheet->setCellValue('D' . $row, __('No problems with gassiness at ') . $stationLabel);
//                $sheet->setCellValue('D' . $row, __('No problems with gassiness at ') . $stationLabel . ' ' . $reportPeriod);

                $row++;
            }
        }

        $fileName = 'Звіт_загазованність_' . now()->format('Y_m_d') . '_за_останні_' . $days . '_днів.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
