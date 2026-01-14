<!DOCTYPE html>
<html>
<head>
    <style>
        /* CSS KHUSUS PDF */
        table {
            width: 100%;
            border-collapse: collapse; 
            font-family: sans-serif;
        }
        
        th, td {
            border: 1px solid #000000;
            padding: 5px;
            vertical-align: top;
            font-size: 10pt;
        }

        /* KHUSUS HEADER KOLOM (WARNA ABU) */
        /* Gunakan background-color hex code standar dengan !important */
        .bg-abu {
            background-color: #d3d3d3 !important; /* Abu-abu terang */
            font-weight: bold;
            text-align: center;
        }

        /* KHUSUS JUDUL (TANPA GARIS) */
        .no-border {
            border: none !important;
            background-color: #ffffff !important; /* Pastikan putih */
        }
    </style>
</head>
<body>
    <table>
        <thead>
            {{-- BARIS 1: JUDUL UTAMA --}}
            <tr>
                <th colspan="7" class="no-border" style="text-align: center; font-weight: bold; font-size: 14pt; padding-bottom: 5px;">
                    REKAPITULASI BERITA ACARA - {{ $labelHeader }}
                </th>
            </tr>

            {{-- BARIS 2: INFO PETUGAS (Kondisional) --}}
            @if(!empty($infoPetugas))
                <tr>
                    <th colspan="7" class="no-border" style="text-align: center; font-style: italic; font-size: 11pt; padding-bottom: 10px;">
                        {{ $infoPetugas }}
                    </th>
                </tr>
            @endif

            {{-- BARIS HEADER KOLOM (DENGAN CLASS BG-ABU) --}}
            <tr>
                <th class="bg-abu" style="width: 30px;">No</th>
                <th class="bg-abu" style="width: 120px;">No. Surat Tugas</th>
                <th class="bg-abu" style="width: 210px;">Petugas Pemeriksa</th>
                <th class="bg-abu" style="width: 90px;">Tgl Periksa</th>
                <th class="bg-abu" style="width: 120px;">Objek</th>
                <th class="bg-abu" style="width: 180px;">Alamat</th>
                <th class="bg-abu" style="width: 100px;">Kota/Kab</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $ba)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td>{{ $ba->no_surat_tugas }}</td>
                    <td>
                        <ul style="margin: 0; padding-left: 15px;"> 
                        @foreach($ba->petugas as $p)
                            <li>{{ $p->name }}</li>
                        @endforeach
                        </ul>
                    </td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($ba->tanggal_pemeriksaan)->format('d-m-Y') }}</td>
                    <td>{{ $ba->objek_nama }}</td>
                    <td>{{ $ba->objek_alamat }}</td>
                    <td>{{ $ba->objek_kota }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>