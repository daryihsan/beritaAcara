<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>{{ $judulTab ?? 'Rekapitulasi BAP' }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: sans-serif;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 5px;
            vertical-align: top;
            font-size: 10pt;
        }

        .bg-abu {
            background-color: #d3d3d3 !important;
            font-weight: bold;
            text-align: center;
        }

        .no-border {
            border: none !important;
            background-color: #ffffff !important;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <!-- Judul utama -->
            <tr>
                <th colspan="7" class="no-border"
                    style="text-align: center; font-weight: bold; font-size: 14pt; padding-bottom: 5px;">
                    REKAPITULASI BERITA ACARA - {{ $labelHeader }}
                </th>
            </tr>

            <!-- Info petugas -->
            @if(!empty($infoPetugas))
                <tr>
                    <th colspan="7" class="no-border"
                        style="text-align: center; font-style: italic; font-size: 11pt; padding-bottom: 10px;">
                        {{ $infoPetugas }}
                    </th>
                </tr>
            @endif

            <!-- Header kolom -->
            <tr>
                <th class="bg-abu" style="width: 30px;">No</th>
                <th class="bg-abu" style="width: 120px;">No. Surat Tugas</th>
                <th class="bg-abu" style="width: 180px;">Petugas Pemeriksa</th>
                <th class="bg-abu" style="width: 80px;">Tgl Periksa</th>
                <th class="bg-abu" style="width: 80px;">Tgl BAP</th>
                <th class="bg-abu" style="width: 110px;">Objek</th>
                <th class="bg-abu" style="width: 150px;">Alamat</th>
                <th class="bg-abu" style="width: 90px;">Kota/Kab</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $ba)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td>{{ $ba->no_surat_tugas }}</td>
                    <td>
                        <ul style="margin: 0; padding-left: 15px;">
                            @foreach ($ba->petugas as $p)
                                <li>{{ $p->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td style="text-align: center;">
                        {{ \Carbon\Carbon::parse($ba->tanggal_pemeriksaan)->format('d-m-Y') }}
                    </td>

                    <td style="text-align: center;">
                        {{ \Carbon\Carbon::parse($ba->created_at)->format('d-m-Y') }}
                    </td>

                    <td>{{ $ba->objek_nama }}</td>
                    <td>{{ $ba->objek_alamat }}</td>
                    <td>{{ $ba->objek_kota }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>