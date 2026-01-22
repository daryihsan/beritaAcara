<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables; // <--- WAJIB
use Illuminate\Support\Str;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Activity::with('causer')->latest();

            return DataTables::of($query)
                ->editColumn('created_at', function($log) {
                    return '<div class="font-bold">' . $log->created_at->format('d M Y') . '</div>
                            <div class="text-sm text-slate-400">' . $log->created_at->format('H:i:s') . '</div>';
                })
                ->addColumn('pelaku', function($log) {
                    if($log->causer) {
                        return '<div class="font-bold">' . e($log->causer->name) . '</div>
                                <div class="text-xs text-slate-400">' . e($log->causer->nip) . '</div>';
                    }
                    return '<span class="text-red-400 italic">Sistem/Terhapus</span>';
                })
                ->editColumn('event', function($log) {
                    if($log->event == 'created') return '<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">TAMBAH</span>';
                    if($log->event == 'updated') return '<span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border border-blue-200">UPDATE</span>';
                    if($log->event == 'deleted') return '<span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">HAPUS</span>';
                    return '<span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold uppercase">'.e($log->event).'</span>';
                })
                ->addColumn('detail', function($log) {
                    if($log->properties && $log->properties->has('attributes')) {
                        $html = '<div class="max-h-32 overflow-y-auto custom-scrollbar">';
                        foreach($log->properties['attributes'] as $key => $val) {
                            if(!in_array($key, ['updated_at', 'created_at'])) {
                                $html .= '<div class="mb-1 border-b border-slate-100 pb-1">
                                            <span class="font-bold text-blue-600">' . ucwords(str_replace('_', ' ', $key)) . ':</span> 
                                            <span class="break-all">' . Str::limit((string)$val, 150) . '</span>
                                          </div>';
                            }
                        }
                        $html .= '</div>';
                        return $html;
                    }
                    return '<span class="text-slate-400">- Tidak ada detail -</span>';
                })
                ->rawColumns(['created_at', 'pelaku', 'event', 'detail'])
                ->make(true);
        }

        return view('admin.activity_log.index');
    }
}