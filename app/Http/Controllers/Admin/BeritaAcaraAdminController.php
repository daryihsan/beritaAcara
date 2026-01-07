<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeritaAcara;

class BeritaAcaraAdminController extends Controller
{
    public function index()
    {
        // ADMIN lihat SEMUA berita acara
        $data = BeritaAcara::latest()->get();

        return view('bap.index', compact('data'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'berita_acara_id' => 'required',
            'user_ids' => 'required|array'
        ]);

        $ba = BeritaAcara::findOrFail($request->berita_acara_id);

        $ba->petugas()->syncWithoutDetaching($request->user_ids);

        return back()->with('success', 'Petugas berhasil ditambahkan');
    }
}
