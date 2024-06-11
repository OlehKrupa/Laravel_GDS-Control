<?php

namespace App\Http\Controllers;

use App\Models\SelfSpendings;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SelfSpendingsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $days = $request->input('days', 1);
        $user = Auth::user();
        $query = SelfSpendings::where('created_at', '>=', now()->subDays($days))
            ->with(['station', 'user']); // Загрузить связанные модели

        if ($user->hasRole('OPERATOR') && $user->roles->count() === 1) {
            $query->where('user_id', $user->id)->where('user_station_id', $user->station_id);
        } else {
            $userStationId = $request->input('user_station_id');
            if ($userStationId) {
                $query->where('user_station_id', $userStationId);
            }
        }

        $selfSpendings = $query->orderBy($sort, $direction)->paginate(8);
        $stations = Station::all();

        return view('selfSpendings.index', compact('selfSpendings', 'stations'));
    }

    public function create()
    {
        return view('selfSpendings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "heater_time" => "required|numeric",
            "boiler_time" => "required|numeric",
            "heater_gas" => "required|numeric",
            "boiler_gas" => "required|numeric",
        ], [
            "heater_time.required" => "Поле :attribute є обов'язковим.",
            "heater_time.numeric" => "Поле :attribute повинне бути числовим.",
            "boiler_time.required" => "Поле :attribute є обов'язковим.",
            "boiler_time.numeric" => "Поле :attribute повинне бути числовим.",
            "heater_gas.required" => "Поле :attribute є обов'язковим.",
            "heater_gas.numeric" => "Поле :attribute повинне бути числовим.",
            "boiler_gas.required" => "Поле :attribute є обов'язковим.",
            "boiler_gas.numeric" => "Поле :attribute повинне бути числовим.",
        ]);

        $selfSpending = new SelfSpendings($request->all());
        $selfSpending->user_id = auth()->user()->id;
        $selfSpending->user_station_id = auth()->user()->station_id;
        $selfSpending->save();

        // Логирование создания
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => 'self_spendings',
            'new_data' => $selfSpending->toJson()
        ]);

        return redirect()->route('selfSpendings.index')
            ->with('success', 'Self Spending created successfully.');
    }

    public function edit(SelfSpendings $selfSpending)
    {
        return view('selfSpendings.edit', compact('selfSpending'));
    }

    public function update(Request $request, SelfSpendings $selfSpending)
    {
        $request->validate([
            "heater_time" => "required|numeric",
            "boiler_time" => "required|numeric",
            "heater_gas" => "required|numeric",
            "boiler_gas" => "required|numeric",
        ], [
            "heater_time.required" => "Поле :attribute є обов'язковим.",
            "heater_time.numeric" => "Поле :attribute повинне бути числовим.",
            "boiler_time.required" => "Поле :attribute є обов'язковим.",
            "boiler_time.numeric" => "Поле :attribute повинне бути числовим.",
            "heater_gas.required" => "Поле :attribute є обов'язковим.",
            "heater_gas.numeric" => "Поле :attribute повинне бути числовим.",
            "boiler_gas.required" => "Поле :attribute є обов'язковим.",
            "boiler_gas.numeric" => "Поле :attribute повинне бути числовим.",
        ]);

        $oldData = $selfSpending->toJson();
        $selfSpending->update($request->all());

        // Логирование обновления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'table_name' => 'self_spendings',
            'old_data' => $oldData,
            'new_data' => $selfSpending->toJson()
        ]);

        return redirect()->route('selfSpendings.index')
            ->with('success', 'Self Spending updated successfully.');
    }

    public function destroy(SelfSpendings $selfSpending)
    {
        $oldData = $selfSpending->toJson();
        $selfSpending->delete();

        // Логирование удаления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => 'self_spendings',
            'old_data' => $oldData
        ]);

        return redirect()->route('selfSpendings.index')
            ->with('success', 'Self Spending deleted successfully.');
    }

    public function generateReport(Request $request)
    {
        $days = $request->input('days', 1);
        $stationId = $request->input('user_station_id');

        $query = SelfSpendings::where('created_at', '>=', now()->subDays($days));

        if ($stationId) {
            $query->where('user_station_id', $stationId);
        }

        $groupedData = $query->select(
            'user_station_id',
            DB::raw('AVG(heater_time) as avg_heater_time'),
            DB::raw('AVG(boiler_time) as avg_boiler_time'),
            DB::raw('AVG(heater_gas) as avg_heater_gas'),
            DB::raw('AVG(boiler_gas) as avg_boiler_gas'),
            DB::raw('SUM(heater_time) as total_heater_time'),
            DB::raw('SUM(boiler_time) as total_boiler_time'),
            DB::raw('SUM(heater_gas) as total_heater_gas'),
            DB::raw('SUM(boiler_gas) as total_boiler_gas')
        )
            ->groupBy('user_station_id')
            ->get();

        $stations = Station::pluck('label', 'id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(__('Self Spendings Report'));

        $header = [
            __('Station'),
            __('AVG Heater Time'),
            __('AVG Heater Gas'),
            __('AVG Boiler Time'),
            __('AVG Boiler Gas'),
            __('Total Heater Time'),
            __('Total Heater Gas'),
            __('Total Boiler Time'),
            __('Total Boiler Gas')
        ];
        $sheet->fromArray($header, null, 'A1');

        $row = 2;
        foreach ($groupedData as $data) {
            $stationLabel = $stations[$data->user_station_id] ?? $data->user_station_id;
            $sheet->setCellValue('A' . $row, $stationLabel);
            $sheet->setCellValue('B' . $row, $data->avg_heater_time);
            $sheet->setCellValue('C' . $row, $data->avg_heater_gas);
            $sheet->setCellValue('D' . $row, $data->avg_boiler_time);
            $sheet->setCellValue('E' . $row, $data->avg_boiler_gas);
            $sheet->setCellValue('F' . $row, $data->total_heater_time);
            $sheet->setCellValue('G' . $row, $data->total_heater_gas);
            $sheet->setCellValue('H' . $row, $data->total_boiler_time);
            $sheet->setCellValue('I' . $row, $data->total_boiler_gas);
            $row++;
        }

        $reportTitle = __('Self Spendings Report');
        $reportDate = now()->format('Y-m-d');
        $reportDays = __('for the last :days days', ['days' => $days]);
        $reportStation = $stationId ? __('for station :station', ['station' => Station::find($stationId)->label]) : __('for all stations');

        $fileName = "{$reportTitle}_{$reportDate}_{$reportDays}_{$reportStation}.xlsx";

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'self_spending_report');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
