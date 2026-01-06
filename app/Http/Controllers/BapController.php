<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BapController extends Controller
{
    public function index() {
        return view('bap.form');
    }

    public function cetak(Request $request) {
        $data = $request->all();

        // Ambil list petugas dari array input
        $list_petugas = [];
        if(isset($data['petugas_nama'])){
            foreach($data['petugas_nama'] as $key => $val){
                if(!empty($val)){
                    $list_petugas[] = [
                        'nama' => $val,
                        'pangkat' => $data['petugas_pangkat'][$key] ?? '-',
                        'jabatan' => $data['petugas_jabatan'][$key] ?? '-',
                        'nip' => $data['petugas_nip'][$key] ?? '-'
                    ];
                }
            }
        }

        // Logic Tanggal Terbilang (Sesuai kode asli kamu)
        $teks_tgl = $this->getTeksTanggal($data['tanggal']);

        // Data untuk PDF
        $pdf_data = [
            'data' => $data,
            'list_petugas' => $list_petugas,
            'teks_tgl' => $teks_tgl,
            'logo' => $this->imageToBase64(public_path('assets/img/logo_bpom.png')),
            'footer' => $this->imageToBase64(public_path('assets/img/border.png'))
        ];

        $pdf = Pdf::loadView('bap.pdf_template', $pdf_data);
        return $pdf->setPaper('a4', 'portrait')->stream('BAP_Setempat.pdf');
    }

    // --- HELPER FUNCTIONS (KODE ASLI KAMU) ---
    private function imageToBase64($path) {
        if (!file_exists($path)) return '';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    private function getTeksTanggal($tgl_input) {
        if(empty($tgl_input)) return ['tgl' => '...', 'bln' => '...', 'thn' => '...'];
        $parts = explode('-', $tgl_input);
        $nama_bulan_arr = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        
        return [
            'tgl' => $this->terbilang((int)$parts[2]),
            'bln' => $nama_bulan_arr[(int)$parts[1] - 1],
            'thn' => $this->terbilang((int)$parts[0])
        ];
    }

    public function tglIndoLengkap($tgl){
        if(empty($tgl)) return "-";
        $bulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $x = explode('-', $tgl);
        return $x[2].' '.$bulan[(int)$x[1]-1].' '.$x[0];
    }

    private function penyebut($nilai) {
        $nilai = abs((int)$nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) $temp = " ". $huruf[$nilai];
        else if ($nilai < 20) $temp = $this->penyebut($nilai - 10). " Belas";
        else if ($nilai < 100) $temp = $this->penyebut($nilai/10)." Puluh". $this->penyebut($nilai % 10);
        else if ($nilai < 200) $temp = " Seratus" . $this->penyebut($nilai - 100);
        else if ($nilai < 1000) $temp = $this->penyebut($nilai/100) . " Ratus" . $this->penyebut($nilai % 100);
        else if ($nilai < 2000) $temp = " Seribu" . $this->penyebut($nilai - 1000);
        else if ($nilai < 1000000) $temp = $this->penyebut($nilai/1000) . " Ribu" . $this->penyebut($nilai % 1000);
        return $temp;
    }

    private function terbilang($nilai) {
        if($nilai == 0) return "Nol";
        return ucwords(strtolower(trim($this->penyebut($nilai))));
    }
}