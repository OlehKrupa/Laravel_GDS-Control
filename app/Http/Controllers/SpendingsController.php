<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Notes;
use App\Models\Spendings;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpendingsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $days = $request->input('days', 1);
        $user = Auth::user();
        $query = Spendings::where('created_at', '>=', now()->subDays($days))
            ->with(['station', 'user']); // Загрузить связанные модели

        if ($user->hasRole('OPERATOR') && $user->roles->count() === 1) {
            $query->where('user_id', $user->id)->where('user_station_id', $user->station_id);
        } else {
            $userStationId = $request->input('user_station_id');
            if ($userStationId) {
                $query->where('user_station_id', $userStationId);
            }
        }

        $spendings = $query->orderBy($sort, $direction)->paginate(8);
        $stations = Station::all();

        return view('spendings.index', compact('spendings', 'stations'));
    }

    public function create()
    {
        return view('spendings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gas' => 'required|numeric',
            'odorant' => 'required|numeric',
        ], [
            'gas.required' => 'Поле :attribute є обов\'язковим.',
            'gas.numeric' => 'Поле :attribute повинне бути числовим.',
            'odorant.required' => 'Поле :attribute є обов\'язковим.',
            'odorant.numeric' => 'Поле :attribute повинне бути числовим.',
        ]);

        $spending = new Spendings($request->all());
        $spending->user_id = Auth::id();
        $spending->user_station_id = Auth::user()->station_id;

        $spending->save();

        // Логирование создания
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => 'spendings',
            'new_data' => $spending->toJson()
        ]);

        return redirect()->route('spendings.index')
            ->with('success', 'Spending created successfully.');
    }

    public function update(Request $request, Spendings $spending)
    {
        $request->validate([
            'gas' => 'required|numeric',
            'odorant' => 'required|numeric',
        ], [
            'gas.required' => 'Поле :attribute є обов\'язковим.',
            'gas.numeric' => 'Поле :attribute повинне бути числовим.',
            'odorant.required' => 'Поле :attribute є обов\'язковим.',
            'odorant.numeric' => 'Поле :attribute повинне бути числовим.',
        ]);

        $oldData = $spending->toJson();
        $spending->update($request->all());

        // Логирование обновления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'table_name' => 'spendings',
            'old_data' => $oldData,
            'new_data' => $spending->toJson()
        ]);

        return redirect()->route('spendings.index')
            ->with('success', 'Spending updated successfully');
    }

    public function destroy(Spendings $spending)
    {
        $oldData = $spending->toJson();
        $spending->delete();

        // Логирование удаления
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => 'spendings',
            'old_data' => $oldData
        ]);

        return redirect()->route('spendings.index')
            ->with('success', 'Spending deleted successfully');
    }

    public function edit(Spendings $spending)
    {
        return view('spendings.edit', compact('spending'));
    }

    public function generateReport(Request $request)
    {
        $days = $request->input('days', 1);
        $stationId = $request->input('user_station_id');

        $query = Spendings::where('created_at', '>=', now()->subDays($days));

        if ($stationId) {
            $query->where('user_station_id', $stationId);
        }

        $groupedData = $query->select(
            'user_station_id',
            DB::raw('AVG(gas) as avg_gas'),
            DB::raw('AVG(odorant) as avg_odorant'),
            DB::raw('SUM(gas) as total_gas'),
            DB::raw('SUM(odorant) as total_odorant')
        )
            ->groupBy('user_station_id')
            ->get();

        $stations = Station::pluck('label', 'id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(__('Report'));

        $header = [__('Station'), __('Average Gas'), __('Average Odorant'), __('Total Gas'), __('Total Odorant')];
        $sheet->fromArray($header, null, 'A1');

        $row = 2;
        foreach ($groupedData as $data) {
            $stationLabel = $stations[$data->user_station_id] ?? $data->user_station_id;
            $sheet->setCellValue('A' . $row, $stationLabel);
            $sheet->setCellValue('B' . $row, $data->avg_gas);
            $sheet->setCellValue('C' . $row, $data->avg_odorant);
            $sheet->setCellValue('D' . $row, $data->total_gas);
            $sheet->setCellValue('E' . $row, $data->total_odorant);
            $row++;
        }

        $reportTitle = __('Expense Report');
        $reportDate = now()->format('Y-m-d');
        $reportDays = __('for the last :days days', ['days' => $days]);
        $reportStation = $stationId ? __('for station :station', ['station' => $stations[$stationId] ?? $stationId]) : __('for all stations');

        $fileName = "{$reportTitle}_{$reportDate}_{$reportDays}_{$reportStation}.xlsx";

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'report');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

}
