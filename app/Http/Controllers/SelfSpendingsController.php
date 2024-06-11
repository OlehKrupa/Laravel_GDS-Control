<?php

namespace App\Http\Controllers;

use App\Models\SelfSpendings;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
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
            $query->where('user_station_id', $user->station_id);
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
        $userStationId = $request->input('user_station_id');

        $query = SelfSpendings::orderBy('created_at', 'desc');

        if ($userStationId) {
            $query->where('user_station_id', $userStationId);
        }

        $selfSpendings = $query->where('created_at', '>=', now()->subDays($days))->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(__('Self Spendings Report'));

        $header = [__('Date'), __('Heater Time'), __('Boiler Time'), __('Heater Gas'), __('Boiler Gas')];
        $sheet->fromArray($header, null, 'A1');

        $row = 2;
        foreach ($selfSpendings as $data) {
            $sheet->setCellValue('A' . $row, $data->created_at);
            $sheet->setCellValue('B' . $row, $data->heater_time);
            $sheet->setCellValue('C' . $row, $data->boiler_time);
            $sheet->setCellValue('D' . $row, $data->heater_gas);
            $sheet->setCellValue('E' . $row, $data->boiler_gas);
            $row++;
        }

        $reportTitle = __('Self Spendings Report');
        $reportDate = now()->format('Y-m-d');
        $reportDays = __('for the last :days days', ['days' => $days]);
        $reportStation = $userStationId ? __('for station :station', ['station' => Station::find($userStationId)->label]) : __('for all stations');

        $fileName = "{$reportTitle}_{$reportDate}_{$reportDays}_{$reportStation}.xlsx";

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'self_spending_report');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
