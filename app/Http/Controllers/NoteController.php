<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at'); // Default sort by 'created_at'
        $direction = $request->get('direction', 'asc'); // Default direction 'asc'
        $days = $request->input('days', 1);
        $user = Auth::user();
        $query = Notes::where('created_at', '>=', now()->subDays($days));

        if ($user->hasRole('OPERATOR') && $user->roles->count() === 1) {
            $query->where('user_station_id', $user->station_id);
        } else {
            $userStationId = $request->input('user_station_id');
            if ($userStationId) {
                $query->where('user_station_id', $userStationId);
            }
        }

        $notes = $query->orderBy($sort, $direction)->paginate(8);
        $stations = Station::all(); // Get all stations

        return view('notes.index', compact('notes', 'stations'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'operational_switching' => 'nullable|string|required_without_all:received_orders,completed_works,visits_by_outsiders,inspection_of_pressure_tanks',
            'received_orders' => 'nullable|string|required_without_all:operational_switching,completed_works,visits_by_outsiders,inspection_of_pressure_tanks',
            'completed_works' => 'nullable|string|required_without_all:operational_switching,received_orders,visits_by_outsiders,inspection_of_pressure_tanks',
            'visits_by_outsiders' => 'nullable|string|required_without_all:operational_switching,received_orders,completed_works,inspection_of_pressure_tanks',
            'inspection_of_pressure_tanks' => 'nullable|string|required_without_all:operational_switching,received_orders,completed_works,visits_by_outsiders',
        ], [
            'required_without_all' => __('one_field_required'),
        ]);

        $note = new Notes();
        $note->operational_switching = $request->input('operational_switching');
        $note->received_orders = $request->input('received_orders');
        $note->completed_works = $request->input('completed_works');
        $note->visits_by_outsiders = $request->input('visits_by_outsiders');
        $note->inspection_of_pressure_tanks = $request->input('inspection_of_pressure_tanks');
        $note->user_id = Auth::id();
        $note->user_station_id = Auth::user()->station_id;
        $note->save();

        // Log creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'table_name' => 'notes',
            'new_data' => $note->toJson()
        ]);

        return redirect()->route('notes.index')->with('success', 'Note created successfully!');
    }

    public function edit(Notes $note)
    {
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Notes $note)
    {
        $request->validate([
            'operational_switching' => 'nullable|string|required_without:received_orders,completed_works,visits_by_outsiders,inspection_of_pressure_tanks',
            'received_orders' => 'nullable|string|required_without:operational_switching,completed_works,visits_by_outsiders,inspection_of_pressure_tanks',
            'completed_works' => 'nullable|string|required_without:operational_switching,received_orders,visits_by_outsiders,inspection_of_pressure_tanks',
            'visits_by_outsiders' => 'nullable|string|required_without:operational_switching,received_orders,completed_works,inspection_of_pressure_tanks',
            'inspection_of_pressure_tanks' => 'nullable|string|required_without:operational_switching,received_orders,completed_works,visits_by_outsiders',
        ], [
            'required_without' => __('one_field_required'),
        ]);

        $oldData = $note->toJson();

        $note->operational_switching = $request->input('operational_switching');
        $note->received_orders = $request->input('received_orders');
        $note->completed_works = $request->input('completed_works');
        $note->visits_by_outsiders = $request->input('visits_by_outsiders');
        $note->inspection_of_pressure_tanks = $request->input('inspection_of_pressure_tanks');
        $note->user_id = Auth::id();
        $note->user_station_id = Auth::user()->station_id;
        $note->save();

        // Log update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'table_name' => 'notes',
            'old_data' => $oldData,
            'new_data' => $note->toJson()
        ]);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully!');
    }

    public function destroy(Notes $note)
    {
        $oldData = $note->toJson();
        $note->delete();

        // Log deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'table_name' => 'notes',
            'old_data' => $oldData
        ]);

        return redirect()->route('notes.index')->with('success', 'Note deleted successfully!');
    }

    public function generateReport(Request $request)
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Проверка роли пользователя
        if ($user->hasRole('OPERATOR') && $user->roles->count() === 1) {
            $stationId = $user->station_id;
        } else {
            $stationId = $request->input('user_station_id', Auth::user()->station_id);
        }

        // Получаем количество дней для отчета
        $days = $request->input('days', 1);

        // Создаем запрос к базе данных
        $query = Notes::where('created_at', '>=', now()->subDays($days));

        // Фильтр по станции, если указан
        if ($stationId) {
            $query->where('user_station_id', $stationId);
            $station = Station::find($stationId);
            $stationLabel = $station ? $station->label : __('All stations');
        } else {
            $stationLabel = __('All stations');
        }

        // Получаем данные
        $notes = $query->get();

        // Создаем новый электронный лист
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(__('Notes Report'));

        // Заголовки колонок
        $header = [
            __('Date'),
            __('Station'),
            __('Operational Switching'),
            __('Received Orders'),
            __('Completed Works'),
            __('Visits by Outsiders'),
            __('Inspection of Pressure Tanks')
        ];
        $sheet->fromArray($header, null, 'A1');

        // Заполняем данные
        $row = 2;
        foreach ($notes as $note) {
            $sheet->setCellValue('A' . $row, $note->created_at->format('Y-m-d'));
            $sheet->setCellValue('B' . $row, $note->station->label ?? 'Unknown');
            $sheet->setCellValue('C' . $row, $note->operational_switching);
            $sheet->setCellValue('D' . $row, $note->received_orders);
            $sheet->setCellValue('E' . $row, $note->completed_works);
            $sheet->setCellValue('F' . $row, $note->visits_by_outsiders);
            $sheet->setCellValue('G' . $row, $note->inspection_of_pressure_tanks);
            $row++;
        }

        // Имя файла
        $reportTitle = __('Notes Report');
        $reportDate = now()->format('Y-m-d');
        $reportDays = __('for the last :days days', ['days' => $days]);
        $reportStation = $stationId ? __('for station :station', ['station' => Station::find($stationId)->label]) : __('for all stations');

        $fileName = "{$reportTitle}_{$reportDate}_{$reportDays}_{$reportStation}.xlsx";

        // Создаем временный файл и записываем в него данные
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        // Возвращаем файл на скачивание и удаляем его после отправки
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

}
