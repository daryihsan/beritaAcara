<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\DateHelper;

class BapController extends Controller
{
    public function index()
    {
        return view('bap.form');
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'hari' => 'required',
            'no_surat_tugas' => 'required',
            'tgl_surat_tugas' => 'required|date',
            'kepala_balai_text' => 'required',
            'objek_nama' => 'required',
            'objek_alamat' => 'required',
            'hasil_pemeriksaan' => 'required',
            'petugas_nama.0' => 'required'
        ], [
            'petugas_nama.0.required' => 'Minimal 1 petugas harus diisi'
        ]);

        $list_petugas = [];
        foreach ($request->petugas_nama as $i => $nama) {
            if ($nama) {
                $list_petugas[] = [
                    'nama' => $nama,
                    'pangkat' => $request->petugas_pangkat[$i] ?? '-',
                    'jabatan' => $request->petugas_jabatan[$i] ?? '-',
                    'nip' => $request->petugas_nip[$i] ?? '-'
                ];
            }
        }

        $pdf = Pdf::loadView('bap.pdf', [
            'data' => $request->all(),
            'list_petugas' => $list_petugas,
            'teks_tgl' => DateHelper::teksTanggal($request->tanggal),
            'tgl_st' => DateHelper::indoLengkap($request->tgl_surat_tugas),
            'logo' => $this->imgBase64('logo_bpom.png'),
            'footer' => $this->imgBase64('border.png')
        ]);

        return $pdf->setPaper('A4', 'portrait')->stream('BAP_Setempat.pdf');
    }

    private function imgBase64($file)
    {
        $path = public_path('assets/img/' . $file);
        if (!file_exists($path))
            return '';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
