<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $days = $request->input('days', 1);
        $user = Auth::user();
        $query = Journal::where('created_at', '>=', now()->subDays($days))
            ->with(['station', 'user']); // Загрузить связанные модели

        if ($user->hasRole('OPERATOR') && $user->roles->count() === 1) {
            $query->where('user_id', $user->id)->where('user_station_id', $user->station_id);
        } else {
            $userStationId = $request->input('user_station_id');
            if ($userStationId) {
                $query->where('user_station_id', $userStationId);
            }
        }

        $journals = $query->orderBy($sort, $direction)->paginate(8);
        $stations = Station::all();

        return view('journals.index', compact('journals', 'stations'));
    }

    public function create()
    {
        return view('journals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pressure_in' => 'required|numeric',
            'pressure_out_1' => 'required|numeric',
            'pressure_out_2' => 'nullable|numeric',
            'temperature_1' => 'required|numeric',
            'temperature_2' => 'required|numeric',
            'odorant_value_1' => 'nullable|numeric',
            'odorant_value_2' => 'nullable|numeric',
            'gas_heater_temperature_in' => 'nullable|numeric',
            'gas_heater_temperature_out' => 'nullable|numeric',
        ], [
            "pressure_in.required" => "Поле Тиск вх. обов'язкове для заповнення.",
            "pressure_in.numeric" => "Поле Тиск вх. повинне бути числовим.",
            "pressure_out_1.required" => "Поле Тиск вих. I обов'язкове для заповнення.",
            "pressure_out_1.numeric" => "Поле Тиск вих. I повинне бути числовим.",
            "pressure_out_2.numeric" => "Поле Тиск вих. II повинне бути числовим.",
            "temperature_1.required" => "Поле ℃ вих. I обов'язкове для заповнення.",
            "temperature_1.numeric" => "Поле ℃ вих. I повинне бути числовим.",
            "temperature_2.required" => "Поле ℃ вих. II обов'язкове для заповнення.",
            "temperature_2.numeric" => "Поле ℃ вих. II повинне бути числовим.",
            "odorant_value_1.numeric" => "Поле Рівень одоранту I повинне бути числовим.",
            "odorant_value_2.numeric" => "Поле Рівень одоранту II повинне бути числовим.",
            "gas_heater_temperature_in.numeric" => "Поле ℃ вх. ПГ повинне бути числовим.",
            "gas_heater_temperature_out.numeric" => "Поле ℃ вих. ПГ повинне бути числовим.",
        ]);

        $journal = new Journal();
        $journal->pressure_in = $request->input('pressure_in');
        $journal->pressure_out_1 = $request->input('pressure_out_1');
        $journal->pressure_out_2 = $request->input('pressure_out_2');
        $journal->temperature_1 = $request->input('temperature_1');
        $journal->temperature_2 = $request->input('temperature_2');
        $journal->odorant_value_1 = $request->input('odorant_value_1');
        $journal->odorant_value_2 = $request->input('odorant_value_2');
        $journal->gas_heater_temperature_in = $request->input('gas_heater_temperature_in');
        $journal->gas_heater_temperature_out = $request->input('gas_heater_temperature_out');
        $journal->user_id = Auth::id();
        $journal->user_station_id = Auth::user()->station_id;
        $journal->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => 'journal',
            'new_data' => $journal->toJson()
        ]);

        return redirect()->route('journals.index')->with('success', 'Запис створено!');
    }

    public function edit(Journal $journal)
    {
        return view('journals.edit', compact('journal'));
    }

    public function update(Request $request, Journal $journal)
    {
        $request->validate([
            'pressure_in' => 'required|numeric',
            'pressure_out_1' => 'required|numeric',
            'pressure_out_2' => 'nullable|numeric',
            'temperature_1' => 'required|numeric',
            'temperature_2' => 'required|numeric',
            'odorant_value_1' => 'nullable|numeric',
            'odorant_value_2' => 'nullable|numeric',
            'gas_heater_temperature_in' => 'nullable|numeric',
            'gas_heater_temperature_out' => 'nullable|numeric',
        ], [
            "pressure_in.required" => "Поле Тиск вх. обов'язкове для заповнення.",
            "pressure_in.numeric" => "Поле Тиск вх. повинне бути числовим.",
            "pressure_out_1.required" => "Поле Тиск вих. I обов'язкове для заповнення.",
            "pressure_out_1.numeric" => "Поле Тиск вих. I повинне бути числовим.",
            "pressure_out_2.numeric" => "Поле Тиск вих. II повинне бути числовим.",
            "temperature_1.required" => "Поле ℃ вих. I обов'язкове для заповнення.",
            "temperature_1.numeric" => "Поле ℃ вих. I повинне бути числовим.",
            "temperature_2.required" => "Поле ℃ вих. II обов'язкове для заповнення.",
            "temperature_2.numeric" => "Поле ℃ вих. II повинне бути числовим.",
            "odorant_value_1.numeric" => "Поле Рівень одоранту I повинне бути числовим.",
            "odorant_value_2.numeric" => "Поле Рівень одоранту II повинне бути числовим.",
            "gas_heater_temperature_in.numeric" => "Поле ℃ вх. ПГ повинне бути числовим.",
            "gas_heater_temperature_out.numeric" => "Поле ℃ вих. ПГ повинне бути числовим.",
        ]);

        $oldData = $journal->toJson();

        $journal->pressure_in = $request->input('pressure_in');
        $journal->pressure_out_1 = $request->input('pressure_out_1');
        $journal->pressure_out_2 = $request->input('pressure_out_2');
        $journal->temperature_1 = $request->input('temperature_1');
        $journal->temperature_2 = $request->input('temperature_2');
        $journal->odorant_value_1 = $request->input('odorant_value_1');
        $journal->odorant_value_2 = $request->input('odorant_value_2');
        $journal->gas_heater_temperature_in = $request->input('gas_heater_temperature_in');
        $journal->gas_heater_temperature_out = $request->input('gas_heater_temperature_out');
        $journal->user_id = Auth::id();
        $journal->user_station_id = Auth::user()->station_id;
        $journal->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'table_name' => 'journal',
            'old_data' => $oldData,
            'new_data' => $journal->toJson()
        ]);

        return redirect()->route('journals.index')->with('success', 'Запис оновлено!');
    }

    public function destroy(Journal $journal)
    {
        $oldData = $journal->toJson();
        $journal->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => 'journal',
            'old_data' => $oldData
        ]);

        return redirect()->route('journals.index')->with('success', 'Запис видалено!');
    }

    public function generateReport(Request $request)
    {
        $days = $request->input('days', 1);
        $stationId = $request->input('user_station_id', Auth::user()->station_id);
        $user = Auth::user();
        $parameter = $request->input('parameter', 'all');

        $query = Journal::where('created_at', '>=', now()->subDays($days));

        if ($user->hasRole('OPERATOR') && $user->roles->count() === 1) {
            $query->where('user_station_id', $user->station_id);
        } else {
            $userStationId = $request->input('user_station_id');
            if ($userStationId) {
                $query->where('user_station_id', $userStationId);
            }
        }

        $groupedData = $query->select(
            'user_station_id',
            DB::raw('AVG(pressure_in) as avg_pressure_in'),
            DB::raw('AVG(pressure_out_1) as avg_pressure_out_1'),
            DB::raw('AVG(pressure_out_2) as avg_pressure_out_2'),
            DB::raw('AVG(temperature_1) as avg_temperature_1'),
            DB::raw('AVG(temperature_2) as avg_temperature_2'),
            DB::raw('AVG(odorant_value_1) as avg_odorant_value_1'),
            DB::raw('AVG(odorant_value_2) as avg_odorant_value_2'),
            DB::raw('AVG(gas_heater_temperature_in) as avg_gas_heater_temperature_in'),
            DB::raw('AVG(gas_heater_temperature_out) as avg_gas_heater_temperature_out')
        )
            ->groupBy('user_station_id')
            ->get();

        $overallAverages = $query->select(
            DB::raw('AVG(pressure_in) as avg_pressure_in'),
            DB::raw('AVG(pressure_out_1) as avg_pressure_out_1'),
            DB::raw('AVG(pressure_out_2) as avg_pressure_out_2'),
            DB::raw('AVG(temperature_1) as avg_temperature_1'),
            DB::raw('AVG(temperature_2) as avg_temperature_2'),
            DB::raw('AVG(odorant_value_1) as avg_odorant_value_1'),
            DB::raw('AVG(odorant_value_2) as avg_odorant_value_2'),
            DB::raw('AVG(gas_heater_temperature_in) as avg_gas_heater_temperature_in'),
            DB::raw('AVG(gas_heater_temperature_out) as avg_gas_heater_temperature_out')
        )->first();

        // Получаем метки станций
        $stations = Station::pluck('label', 'id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(__('Report'));

        if ($parameter == 'all') {
            $header = [__('Station'), __('Pressure In'), __('Pressure Out 1'), __('Pressure Out 2'), __('Temperature 1'), __('Temperature 2'), __('Odorant Value 1'), __('Odorant Value 2'), __('Gas Heater Temperature In'), __('Gas Heater Temperature Out')];
            $sheet->fromArray($header, null, 'A1');

            $row = 2;
            foreach ($groupedData as $data) {
                $stationLabel = $stations[$data->user_station_id] ?? $data->user_station_id;
                $sheet->setCellValue('A' . $row, $stationLabel);
                $sheet->setCellValue('B' . $row, $data->avg_pressure_in);
                $sheet->setCellValue('C' . $row, $data->avg_pressure_out_1);
                $sheet->setCellValue('D' . $row, $data->avg_pressure_out_2);
                $sheet->setCellValue('E' . $row, $data->avg_temperature_1);
                $sheet->setCellValue('F' . $row, $data->avg_temperature_2);
                $sheet->setCellValue('G' . $row, $data->avg_odorant_value_1);
                $sheet->setCellValue('H' . $row, $data->avg_odorant_value_2);
                $sheet->setCellValue('I' . $row, $data->avg_gas_heater_temperature_in);
                $sheet->setCellValue('J' . $row, $data->avg_gas_heater_temperature_out);
                $row++;
            }

            $sheet->setCellValue('A' . $row, __('Overall'));
            $sheet->setCellValue('B' . $row, $overallAverages->avg_pressure_in);
            $sheet->setCellValue('C' . $row, $overallAverages->avg_pressure_out_1);
            $sheet->setCellValue('D' . $row, $overallAverages->avg_pressure_out_2);
            $sheet->setCellValue('E' . $row, $overallAverages->avg_temperature_1);
            $sheet->setCellValue('F' . $row, $overallAverages->avg_temperature_2);
            $sheet->setCellValue('G' . $row, $overallAverages->avg_odorant_value_1);
            $sheet->setCellValue('H' . $row, $overallAverages->avg_odorant_value_2);
            $sheet->setCellValue('I' . $row, $overallAverages->avg_gas_heater_temperature_in);
            $sheet->setCellValue('J' . $row, $overallAverages->avg_gas_heater_temperature_out);
        } else {
            $header = [__('Station'), __(ucfirst(str_replace('_', ' ', $parameter)))];
            $sheet->fromArray($header, null, 'A1');

            $row = 2;
            foreach ($groupedData as $data) {
                $stationLabel = $stations[$data->user_station_id] ?? $data->user_station_id;
                $sheet->setCellValue('A' . $row, $stationLabel);
                $sheet->setCellValue('B' . $row, $data->{'avg_' . $parameter});
                $row++;
            }

            $sheet->setCellValue('A' . $row, __('Overall'));
            $sheet->setCellValue('B' . $row, $overallAverages->{'avg_' . $parameter});
        }

        $stationLabel = __('All stations');
        if ($userStationId) {
            $station = Station::find($userStationId);
            if ($station) {
                $stationLabel = $station->label;
            }
        }

        $fileName = __('Report') . '_' . __($parameter) . '_' . __('for') . '_' . $stationLabel . '_' . __('for') . '_' . $days . '_' . __('days') . '.xlsx';

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
