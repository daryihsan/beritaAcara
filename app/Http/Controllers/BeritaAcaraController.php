<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeritaAcara;
use App\Models\User; // Tambahkan ini agar tidak error saat memanggil model User
use App\Helpers\DateHelper;
use Barryvdh\DomPDF\Facade\Pdf;

class BeritaAcaraController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = BeritaAcara::query();

        if ($request->has('tahun')) {
            $query->whereYear('tanggal_pemeriksaan', $request->tahun);
        }

        if (!$user->isAdmin()) {
            $query->whereHas(
                'petugas',
                fn($q) =>
                $q->where('users.nip', $user->nip)
            );
        }

        $data = $query->latest()->get();

        return view('bap.index', compact('data'));
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

    public function create()
    {
        $petugas = User::where('role', 'petugas')->get();
        return view('bap.form', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat_tugas' => 'required',
            'tgl_surat_tugas' => 'required|date',
            'tanggal' => 'required|date',
            'hari' => 'required',
            'objek_nama' => 'required',
            'objek_alamat' => 'required',
            'hasil_pemeriksaan' => 'required',
            'petugas_nip.*' => 'required|exists:users,nip',
        ], [
            'petugas_nip.*.exists' => 'NIP Petugas tidak terdaftar di sistem!'
        ]);
        $ba = BeritaAcara::create([
            'no_surat_tugas' => $request->no_surat_tugas,
            'tgl_surat_tugas' => $request->tgl_surat_tugas,
            'tanggal_pemeriksaan' => $request->tanggal,
            'hari' => $request->hari,
            'objek_nama' => $request->objek_nama,
            'objek_alamat' => $request->objek_alamat,
            'hasil_pemeriksaan' => $request->hasil_pemeriksaan,
            'created_by' => auth()->id(),
        ]);

        $ba->petugas()->sync($request->petugas_nip);

        return $this->cetak($request);
    }

    public function assignPetugas(Request $request)
    {
        $ba = BeritaAcara::findOrFail($request->berita_acara_id);
        $ba->petugas()->syncWithoutDetaching($request->user_ids);

        return back();
    }

    public function pdf($id)
    {
        $ba = BeritaAcara::with('petugas')->findOrFail($id);

        // PETUGAS TIDAK BOLEH AKSES PUNYA ORANG LAIN
        abort_if(
            !auth()->user()->isAdmin() &&
            !$ba->petugas->contains(auth()->id()),
            403
        );

        $pdf = Pdf::loadView('berita_acara.pdf', [
            'ba' => $ba,
            'teks_tgl' => DateHelper::teksTanggal($ba->tanggal_pemeriksaan),
            'tgl_st' => DateHelper::indoLengkap($ba->tgl_surat_tugas),
        ]);

        return $pdf->stream('BAP.pdf');
    }
}

// class BapController extends Controller
// {
//     public function index()
//     {
//         $user = auth()->user();

//         $query = BeritaAcara::query();

//         if (!$user->isAdmin()) {
//             $query->whereHas('petugas', function ($q) use ($user) {
//                 $q->where('users.id', $user->id);
//             });
//         }

//         $data = $query->latest()->get();

//         return view('berita_acara.index', compact('data'));
//     }

//     public function cetak(Request $request)
//     {
//         $request->validate([
//             'tanggal' => 'required|date',
//             'hari' => 'required',
//             'no_surat_tugas' => 'required',
//             'tgl_surat_tugas' => 'required|date',
//             'kepala_balai_text' => 'required',
//             'objek_nama' => 'required',
//             'objek_alamat' => 'required',
//             'hasil_pemeriksaan' => 'required',
//             'petugas_nama.0' => 'required'
//         ], [
//             'petugas_nama.0.required' => 'Minimal 1 petugas harus diisi'
//         ]);

//         $list_petugas = [];
//         foreach ($request->petugas_nama as $i => $nama) {
//             if ($nama) {
//                 $list_petugas[] = [
//                     'nama' => $nama,
//                     'pangkat' => $request->petugas_pangkat[$i] ?? '-',
//                     'jabatan' => $request->petugas_jabatan[$i] ?? '-',
//                     'nip' => $request->petugas_nip[$i] ?? '-'
//                 ];
//             }
//         }

//         $pdf = Pdf::loadView('bap.pdf', [
//             'data' => $request->all(),
//             'list_petugas' => $list_petugas,
//             'teks_tgl' => DateHelper::teksTanggal($request->tanggal),
//             'tgl_st' => DateHelper::indoLengkap($request->tgl_surat_tugas),
//             'logo' => $this->imgBase64('logo_bpom.png'),
//             'footer' => $this->imgBase64('border.png')
//         ]);

//         return $pdf->setPaper('A4', 'portrait')->stream('BAP_Setempat.pdf');
//     }

//     private function imgBase64($file)
//     {
//         $path = public_path('assets/img/' . $file);
//         if (!file_exists($path))
//             return '';
//         $type = pathinfo($path, PATHINFO_EXTENSION);
//         $data = file_get_contents($path);
//         return 'data:image/' . $type . ';base64,' . base64_encode($data);
//     }

//     public function create()
//     {
//         $petugas = User::where('role', 'petugas')->get();
//         return view('berita_acara.create', compact('petugas'));
//     }
//     public function store(Request $request)
//     {
//         $request->validate([
//             'no_surat_tugas' => 'required',
//             'tgl_surat_tugas' => 'required|date',
//             'tanggal' => 'required|date',
//             'hari' => 'required',
//             'objek_nama' => 'required',
//             'objek_alamat' => 'required',
//             'hasil_pemeriksaan' => 'required',
//             'petugas_ids' => 'required|array|min:1'
//         ]);
//         $ba = BeritaAcara::create([
//             'no_surat_tugas' => $request->no_surat_tugas,
//             'tgl_surat_tugas' => $request->tgl_surat_tugas,
//             'tanggal_pemeriksaan' => $request->tanggal,
//             'hari' => $request->hari,
//             'objek_nama' => $request->objek_nama,
//             'objek_alamat' => $request->objek_alamat,
//             'hasil_pemeriksaan' => $request->hasil_pemeriksaan,
//             'created_by' => auth()->id(),
//         ]);

//         // petugas = array user_id
//         $ba->petugas()->sync($request->petugas_ids);

//         return redirect('/berita-acara')->with('success', 'Berita Acara berhasil disimpan');

//     }

//     public function assignPetugas(Request $request)
//     {
//         $ba = BeritaAcara::findOrFail($request->berita_acara_id);
//         $ba->petugas()->syncWithoutDetaching($request->user_ids);

//         return back();
//     }

//     public function pdf($id)
//     {
//         $ba = BeritaAcara::with('petugas')->findOrFail($id);

//         // PETUGAS TIDAK BOLEH AKSES PUNYA ORANG LAIN
//         abort_if(
//             !auth()->user()->isAdmin() &&
//             !$ba->petugas->contains(auth()->id()),
//             403
//         );

//         $pdf = Pdf::loadView('berita_acara.pdf', [
//             'ba' => $ba,
//             'teks_tgl' => DateHelper::teksTanggal($ba->tanggal_pemeriksaan),
//             'tgl_st' => DateHelper::indoLengkap($ba->tgl_surat_tugas),
//         ]);

//         return $pdf->stream('BAP.pdf');
//     }
// }
