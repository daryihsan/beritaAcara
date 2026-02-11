<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BeritaAcara;
use App\Models\User;

class BeritaAcaraSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Kalau user admin belum di-seed, ada on-the-fly
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Fallback',
                'nip' => '999999',
                'role' => 'admin',
                'password' => bcrypt('password')
            ]);
        }

        $records = [
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2022.001',
                'tgl_surat_tugas' => '2022-01-06',
                'tanggal_pemeriksaan' => '2022-01-07',
                'hari' => 'Rabu',
                'objek_nama' => 'Apotek Sehat Sentosa',
                'objek_alamat' => 'Jl. Pandanaran No. 45, Kota Semarang',
                'hasil_pemeriksaan' => 'Ditemukan beberapa obat tanpa etiket lengkap, dilakukan pembinaan dan peringatan tertulis.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2023.002',
                'tgl_surat_tugas' => '2023-01-09',
                'tanggal_pemeriksaan' => '2023-01-10',
                'hari' => 'Sabtu',
                'objek_nama' => 'Toko Obat Berkah Farma',
                'objek_alamat' => 'Jl. Wolter Monginsidi No. 12, Semarang Selatan',
                'hasil_pemeriksaan' => 'Sarana memiliki izin namun penyimpanan belum sesuai (suhu/kelembaban). Dilakukan pembinaan.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2024.003',
                'tgl_surat_tugas' => '2024-01-14',
                'tanggal_pemeriksaan' => '2024-01-15',
                'hari' => 'Kamis',
                'objek_nama' => 'Klinik Pratama Medika Nusantara',
                'objek_alamat' => 'Jl. Setiabudi No. 88, Banyumanik, Semarang',
                'hasil_pemeriksaan' => 'Tidak ditemukan pelanggaran kritis. Administrasi dan penyimpanan obat sesuai ketentuan.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2025.004',
                'tgl_surat_tugas' => '2025-01-20',
                'tanggal_pemeriksaan' => '2025-01-21',
                'hari' => 'Rabu',
                'objek_nama' => 'Gudang PBF Sinar Jaya',
                'objek_alamat' => 'Kawasan Industri Terboyo, Jl. Kaligawe Km 5, Semarang',
                'hasil_pemeriksaan' => 'Ditemukan ketidaksesuaian pencatatan keluar-masuk barang. Dilakukan pembinaan dan diminta perbaikan CAPA.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2026.005',
                'tgl_surat_tugas' => '2026-02-03',
                'tanggal_pemeriksaan' => '2026-02-04',
                'hari' => 'Rabu',
                'objek_nama' => 'Toko Kosmetik Cantik Glow',
                'objek_alamat' => 'Jl. MT Haryono No. 33, Semarang Tengah',
                'hasil_pemeriksaan' => 'Ditemukan kosmetik tanpa notifikasi BPOM. Dilakukan pengamanan produk dan pembinaan kepada pemilik sarana.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2022.006',
                'tgl_surat_tugas' => '2022-02-11',
                'tanggal_pemeriksaan' => '2022-02-12',
                'hari' => 'Kamis',
                'objek_nama' => 'Pasar Johar - Kios Jamu Tradisional',
                'objek_alamat' => 'Kompleks Pasar Johar, Semarang',
                'hasil_pemeriksaan' => 'Ditemukan jamu mengandung BKO (indikasi kuat). Sampel diambil untuk uji laboratorium dan dilakukan pembinaan.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2023.007',
                'tgl_surat_tugas' => '2023-02-18',
                'tanggal_pemeriksaan' => '2023-02-19',
                'hari' => 'Kamis',
                'objek_nama' => 'Minimarket Sembako Makmur',
                'objek_alamat' => 'Jl. Majapahit No. 120, Pedurungan, Semarang',
                'hasil_pemeriksaan' => 'Ditemukan pangan olahan kedaluwarsa dan tanpa label bahasa Indonesia. Dilakukan penarikan dari etalase dan pembinaan.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2024.008',
                'tgl_surat_tugas' => '2024-03-03',
                'tanggal_pemeriksaan' => '2024-03-04',
                'hari' => 'Rabu',
                'objek_nama' => 'Rumah Produksi Kue “Rasa Kita”',
                'objek_alamat' => 'Jl. Menoreh Raya No. 17, Gajahmungkur, Semarang',
                'hasil_pemeriksaan' => 'Higiene sanitasi cukup baik, namun perlu perbaikan pencatatan produksi dan label kemasan.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2025.009',
                'tgl_surat_tugas' => '2025-03-17',
                'tanggal_pemeriksaan' => '2025-03-18',
                'hari' => 'Rabu',
                'objek_nama' => 'Apotek Kimia Farma (Cabang Semarang)',
                'objek_alamat' => 'Jl. Pemuda No. 55, Semarang Tengah',
                'hasil_pemeriksaan' => 'Sarana memenuhi ketentuan. Tidak ditemukan pelanggaran. Dilakukan penguatan edukasi dan dokumentasi.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2026.010',
                'tgl_surat_tugas' => '2026-03-25',
                'tanggal_pemeriksaan' => '2026-03-26',
                'hari' => 'Kamis',
                'objek_nama' => 'Toko Herbal Sejahtera',
                'objek_alamat' => 'Jl. Dr. Cipto No. 101, Semarang Timur',
                'hasil_pemeriksaan' => 'Ditemukan produk suplemen tanpa izin edar dan klaim berlebihan. Dilakukan pembinaan dan pengamanan produk.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2026.011',
                'tgl_surat_tugas' => '2026-04-01',
                'tanggal_pemeriksaan' => '2026-04-02',
                'hari' => 'Kamis',
                'objek_nama' => 'Toko Kosmetik “Beauty House”',
                'objek_alamat' => 'Jl. Gajahmada No. 27, Semarang Tengah',
                'hasil_pemeriksaan' => 'Ditemukan produk kosmetik impor tanpa label bahasa Indonesia. Dilakukan pembinaan dan pengamanan produk.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2026.012',
                'tgl_surat_tugas' => '2026-04-07',
                'tanggal_pemeriksaan' => '2026-04-08',
                'hari' => 'Rabu',
                'objek_nama' => 'Depot Air Minum Isi Ulang “Tirta Bersih”',
                'objek_alamat' => 'Jl. Sukun Raya No. 9, Tembalang, Semarang',
                'hasil_pemeriksaan' => 'Sarana beroperasi, namun pencatatan sanitasi dan jadwal penggantian filter belum lengkap. Dilakukan pembinaan.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2026.013',
                'tgl_surat_tugas' => '2026-04-14',
                'tanggal_pemeriksaan' => '2026-04-15',
                'hari' => 'Rabu',
                'objek_nama' => 'Pabrik Pangan Rumah Tangga “Keripik Bu Rini”',
                'objek_alamat' => 'Jl. Durian Raya No. 18, Banyumanik, Semarang',
                'hasil_pemeriksaan' => 'Ditemukan ketidaksesuaian label (komposisi dan berat bersih). Dilakukan pembinaan dan diminta perbaikan label.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2022.014',
                'tgl_surat_tugas' => '2022-04-21',
                'tanggal_pemeriksaan' => '2022-04-22',
                'hari' => 'Rabu',
                'objek_nama' => 'Apotek “Keluarga Sehat”',
                'objek_alamat' => 'Jl. Brigjen Sudiarto No. 66, Semarang Timur',
                'hasil_pemeriksaan' => 'Ditemukan obat keras tersimpan tidak terpisah. Dilakukan pembinaan dan perbaikan tata kelola penyimpanan.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2023.015',
                'tgl_surat_tugas' => '2023-05-05',
                'tanggal_pemeriksaan' => '2023-05-06',
                'hari' => 'Rabu',
                'objek_nama' => 'Toko Oleh-Oleh “Bandeng Juwana”',
                'objek_alamat' => 'Jl. Pamularsih No. 20, Semarang Barat',
                'hasil_pemeriksaan' => 'Ditemukan pangan olahan tanpa nomor izin edar pada beberapa produk titipan UMKM. Dilakukan pembinaan dan edukasi.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2024.016',
                'tgl_surat_tugas' => '2024-05-12',
                'tanggal_pemeriksaan' => '2024-05-13',
                'hari' => 'Rabu',
                'objek_nama' => 'Gudang Distributor Obat “PT Maju Farma”',
                'objek_alamat' => 'Jl. Industri Raya No. 5, Genuk, Semarang',
                'hasil_pemeriksaan' => 'Dokumentasi penerimaan dan pengeluaran barang belum konsisten. Dilakukan pembinaan dan diminta penyesuaian SOP.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2025.017',
                'tgl_surat_tugas' => '2025-05-19',
                'tanggal_pemeriksaan' => '2025-05-20',
                'hari' => 'Rabu',
                'objek_nama' => 'Toko Jamu “Sehat Alami”',
                'objek_alamat' => 'Jl. Kartini No. 14, Semarang Tengah',
                'hasil_pemeriksaan' => 'Ditemukan jamu racikan tanpa informasi komposisi dan aturan pakai. Dilakukan pembinaan dan peringatan tertulis.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2026.018',
                'tgl_surat_tugas' => '2026-06-02',
                'tanggal_pemeriksaan' => '2026-06-03',
                'hari' => 'Rabu',
                'objek_nama' => 'Klinik Kecantikan “Glow Clinic”',
                'objek_alamat' => 'Jl. S. Parman No. 99, Semarang Barat',
                'hasil_pemeriksaan' => 'Ditemukan beberapa produk kosmetik tanpa izin edar digunakan untuk pelayanan. Dilakukan pembinaan dan pengamanan produk.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2026.019',
                'tgl_surat_tugas' => '2026-06-09',
                'tanggal_pemeriksaan' => '2026-06-10',
                'hari' => 'Rabu',
                'objek_nama' => 'Supermarket “Segar Jaya”',
                'objek_alamat' => 'Jl. Ahmad Yani No. 150, Semarang Selatan',
                'hasil_pemeriksaan' => 'Ditemukan pangan impor tanpa label bahasa Indonesia pada beberapa item. Dilakukan pembinaan dan penataan ulang produk.',
            ],
            [
                'no_surat_tugas' => 'ST.01.05.1A.01.01.2026.020',
                'tgl_surat_tugas' => '2026-06-16',
                'tanggal_pemeriksaan' => '2026-06-17',
                'hari' => 'Rabu',
                'objek_nama' => 'Rumah Produksi Sambal “Pedas Mantap”',
                'objek_alamat' => 'Jl. Kedungmundu No. 7, Semarang',
                'hasil_pemeriksaan' => 'Sarana cukup baik, namun perlu peningkatan higiene sanitasi dan penambahan informasi label produksi.',
            ],
        ];

        $petugasPembuat = User::where('role', 'petugas')->get();
        $opsiKepala = ['Balai Besar POM di Semarang', 'Plh. Kepala Balai', 'Plt. Kepala Balai'];
        
        // Ambil SEMUA petugas yang ada di DB untuk dirandom nanti
        $petugasMaster = User::where('role', 'petugas')->get();

        foreach ($records as $record) {
            // Lengkapi data default
            $record['kepala_balai_text'] = $opsiKepala[array_rand($opsiKepala)];
            $record['objek_kota'] = 'Semarang';
            $record['dalam_rangka'] = 'Pengawasan Rutin Sarana';
            $record['yang_diperiksa'] = 'Penanggung Jawab Sarana';
            if ($petugasPembuat->count() > 0) {
                $record['created_by'] = $petugasPembuat->random()->id; // Random petugas
            } else {
                $record['created_by'] = $admin->id; // fallback kalau petugas kosong
            }

            $ba = BeritaAcara::create($record);

            // Pasangkan 1-3 petugas secara acak
            if ($petugasMaster->count() > 0) {
                $jumlahPetugas = rand(1, min(3, $petugasMaster->count()));
                $randomPetugas = $petugasMaster->random($jumlahPetugas);
                
                foreach ($randomPetugas as $p) {
                    $ba->petugas()->attach($p->nip, [
                        'pangkat' => $p->pangkat,
                        'jabatan' => $p->jabatan,
                    ]);
                }
            }
        }
    }
}