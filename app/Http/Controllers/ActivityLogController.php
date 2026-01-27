<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Activity::with('causer')->latest();

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            return DataTables::of($query)
                ->editColumn('created_at', function ($log) {
                    return view('admin.activity_log.partials.date', compact('log'))->render();
                })
                ->addColumn('pelaku', function ($log) {
                    return view('admin.activity_log.partials.pelaku', compact('log'))->render();
                })
                ->editColumn('event', function ($log) {
                    return view('admin.activity_log.partials.event', compact('log'))->render();
                })
                ->addColumn('detail', function ($log) {
                    return view('admin.activity_log.partials.detail', compact('log'))->render();
                })
                ->rawColumns(['created_at', 'pelaku', 'event', 'detail'])
                ->make(true);
        }

        return view('admin.activity_log.index');
    }
}