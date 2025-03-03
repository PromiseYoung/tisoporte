<?php

namespace App\Http\Controllers\Admin;

use App\AuditLog;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AuditLogsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = AuditLog::query()->select(sprintf('%s.*', (new AuditLog)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'audit_log_show';
                $editGate = 'audit_log_edit';
                $deleteGate = 'audit_log_delete';
                $crudRoutePart = 'audit-logs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : "";
            });
            $table->editColumn('subject_id', function ($row) {
                return $row->subject_id ? $row->subject_id : "";
            });
            $table->editColumn('subject_type', function ($row) {
                return $row->subject_type ? class_basename($row->subject_type) : "";
            });
            $table->editColumn('user_id', function ($row) {
                return $row->user ? $row->user->name : "";
            });
            $table->editColumn('host', function ($row) {
                return $row->host ? $row->host : "";
            });
            $table->editColumn('created_at', function ($row) {
                // Usar Carbon para formatear la fecha
                return \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i'); // Ejemplo: 03 Mar 2025, 14:30
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.auditLogs.index');
    }

    public function show(AuditLog $auditLog)
    {
        abort_if(Gate::denies('audit_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.auditLogs.show', compact('auditLog'));
    }
}
