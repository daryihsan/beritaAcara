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

        // --- LOGIKA PEMISAH ---
        // Jika TIDAK ADA parameter 'tahun' di URL, tampilkan MENU UTAMA (Kotak-kotak)
        if (!$request->has('tahun')) {
            return view('dashboard.menu');
        }

        // Jika ADA parameter 'tahun', baru jalankan logika ambil data untuk TABEL
        $query = BeritaAcara::query();

        // Filter berdasarkan tahun yang dipilih dari sidebar
        $query->whereYear('tanggal_pemeriksaan', $request->tahun);

        // Filter hak akses (jika bukan admin, hanya lihat punya sendiri)
        if (!$user->isAdmin()) {
            $query->whereHas(
                'petugas',
                fn($q) => $q->where('users.nip', $user->nip)
            );
        }

        $data = $query->latest()->get();
        $tahun = $request->tahun; // Simpan tahun untuk ditampilkan di judul tabel jika perlu

        // Kembalikan ke view TABEL
        return view('bap.index', compact('data', 'tahun'));
    }

    public function dashboard()
    {
        // Jika hanya buka /dashboard, tampilkan menu kotak-kotak
        if (!request()->has('tahun')) {
            return view('dashboard.menu');
        }

        // Jika ada parameter ?tahun=2025, baru ambil data untuk tabel
        $tahun = request('tahun');
        $data = BeritaAcara::whereYear('tanggal_pemeriksaan', $tahun)->get();

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
            'kepala_balai_text' => 'required',
            'objek_kota' => 'required',
            'dalam_rangka' => 'required',
            'yang_diperiksa' => 'required',
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
            'kepala_balai_text' => $request->kepala_balai_text,
            'objek_kota' => $request->objek_kota,
            'dalam_rangka' => $request->dalam_rangka,
            'yang_diperiksa' => $request->yang_diperiksa,
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

        // Proteksi akses
        if (!auth()->user()->isAdmin()) {
            if (!$ba->petugas->contains('nip', auth()->user()->nip)) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }
        }

        // Siapkan list petugas agar strukturnya sama dengan fungsi cetak (preview)
        $list_petugas = $ba->petugas->map(function ($p) {
            return [
                'nama' => $p->name,
                'pangkat' => $p->pivot->pangkat ?? '-',
                'jabatan' => $p->pivot->jabatan ?? '-',
                'nip' => $p->nip
            ];
        });

        $pdf = Pdf::loadView('bap.pdf', [
            'ba' => $ba,
            // Mapping data agar variabel {{ $data['hari'] }} dkk tidak error
            'data' => [
                'tanggal' => $ba->tanggal_pemeriksaan,
                'hari' => $ba->hari,
                'no_surat_tugas' => $ba->no_surat_tugas,
                'tgl_surat_tugas' => $ba->tgl_surat_tugas,
                'kepala_balai_text' => $ba->kepala_balai_text,
                'objek_nama' => $ba->objek_nama,
                'objek_alamat' => $ba->objek_alamat,
                'objek_kota' => $ba->objek_kota,
                'dalam_rangka' => $ba->dalam_rangka,
                'hasil_pemeriksaan' => $ba->hasil_pemeriksaan,
                'yang_diperiksa' => $ba->yang_diperiksa,
            ],
            'list_petugas' => $list_petugas,
            'teks_tgl' => DateHelper::teksTanggal($ba->tanggal_pemeriksaan),
            'tgl_st' => DateHelper::indoLengkap($ba->tgl_surat_tugas),
            'logo' => $this->imgBase64('logo_bpom.png'),
            'footer' => $this->imgBase64('border.png') // INI YANG TADI KURANG
        ]);

        return $pdf->setPaper('A4', 'portrait')->stream('BAP_Digital.pdf');
    }

    public function destroy($id)
    {
        // Hanya Admin yang boleh menghapus
        abort_if(!auth()->user()->isAdmin(), 403);

        $ba = BeritaAcara::findOrFail($id);

        // Hapus relasi di tabel pivot petugas_berita_acara dulu agar tidak error (foreign key)
        $ba->petugas()->detach();

        // Hapus data utama
        $ba->delete();

        return back()->with('success', 'Berita Acara berhasil dihapus.');
    }
}
