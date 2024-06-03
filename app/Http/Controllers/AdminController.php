<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Station;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')->paginate(10);
        return view('admin.logs', compact('logs'));
    }

    public function undo(AuditLog $log)
    {
        $oldData = json_decode($log->old_data, true);
        $model = Station::withTrashed()->find($oldData['id']);

        if ($model) {
            $model->restore();
            $model->update($oldData);
        } else {
            Station::create($oldData);
        }

        $log->delete();

        return redirect()->back()->with('success', 'Действие отменено!');
    }

    public function delete(AuditLog $log)
    {
        $log->delete();
        return redirect()->back()->with('success', 'Log deleted successfully!');
    }

}
