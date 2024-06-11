<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Gassiness;
use App\Models\Journal;
use App\Models\Notes;
use App\Models\SelfSpendings;
use App\Models\Spendings;
use App\Models\Station;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')->paginate(10);
        return view('admin.logs', compact('logs'));
    }

    public function undo($model, AuditLog $log)
    {
        $oldData = json_decode($log->old_data, true);
        $tableName = $log->table_name;

        // Use a more elegant way to handle model instances
        $models = [
            'station' => Station::class,
            'gassiness' => Gassiness::class,
            'journal' => Journal::class,
            'notes' => Notes::class,
            'self_spendings' => SelfSpendings::class,
            'spendings' => Spendings::class,
        ];

        if (!isset($models[$tableName])) {
            return Redirect::back()->with('error', 'Model not found for table name: ' . $tableName);
        }

        $modelClass = $models[$tableName];
        $model = $modelClass::withTrashed()->find($oldData['id']);

        if (!$model) {
            return Redirect::back()->with('error', 'Model not found for ID: ' . $oldData['id']);
        }

        try {
            $model->restore();
            $model->update($oldData);
            $log->delete();
            return Redirect::back()->with('success', 'Action undone!');
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->with('error', 'Failed to undo action: ' . $e->getMessage());
        }
    }

    public function delete(AuditLog $log)
    {
        $log->delete();
        return Redirect::back()->with('success', 'Log deleted successfully!');
    }
}
