<table>
    <thead>
        {{-- BARIS 1: JUDUL UTAMA --}}
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14pt;">
                REKAPITULASI BERITA ACARA - {{ $labelHeader }}
            </th>
        </tr>

        {{-- BARIS 2: INFO PETUGAS (Kondisional) --}}
        @if(!empty($infoPetugas))
            <tr>
                <th colspan="7" class="no-border"
                    style="text-align: center; font-style: italic; font-size: 11pt; padding-bottom: 10px;">
                    {{ $infoPetugas }}
                </th>
            </tr>
        @endif


        {{-- Spasi --}}
        <tr></tr>

        {{-- BARIS 4: HEADER TABEL --}}
        <tr>
            <th style="border: 1px solid #000000; font-weight:bold; text-align: center;">No</th>
            <th style="border: 1px solid #000000; font-weight:bold; text-align: center;">No. Surat Tugas</th>
            <th style="border: 1px solid #000000; font-weight:bold; text-align: center;">Petugas Pemeriksa</th>
            <th style="border: 1px solid #000000; font-weight:bold; text-align: center;">Tgl Pemeriksaan</th>
            <th style="border: 1px solid #000000; font-weight:bold; text-align: center;">Objek</th>
            <th style="border: 1px solid #000000; font-weight:bold; text-align: center;">Alamat</th>
            <th style="border: 1px solid #000000; font-weight:bold; text-align: center;">Kota/Kab</th>
        </tr>
    </thead>
    <tbody>
        {{-- ... (Isi Body sama seperti sebelumnya) ... --}}
        @foreach($data as $key => $ba)
            <tr>
                <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">{{ $key + 1 }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $ba->no_surat_tugas }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">
                    @foreach($ba->petugas as $p)
                        - {{ $p->name }} <br>
                    @endforeach
                </td>
                <td style="border: 1px solid #000000; vertical-align: top; ">
                    {{ \Carbon\Carbon::parse($ba->tanggal_pemeriksaan)->format('d-m-Y') }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $ba->objek_nama }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $ba->objek_alamat }}</td>
                <td style="border: 1px solid #000000; vertical-align: top;">{{ $ba->objek_kota }}</td>

            </tr>
        @endforeach
    </tbody>
</table>