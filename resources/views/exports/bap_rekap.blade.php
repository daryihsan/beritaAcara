<table>
    <thead>
        {{-- BARIS 1: JUDUL UTAMA --}}
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14pt;">
                REKAPITULASI BERITA ACARA - {{ $labelHeader }}
            </th>
        </tr>

        {{-- BARIS 2: INFO PETUGAS (Kondisional) --}}
        <tr>
            <th colspan="7" style="text-align: center; font-style: italic; font-size: 11pt;">
                {{ $infoPetugas ?? '' }}  {{-- Hanya muncul jika variable ini ada isinya --}}
            </th>
        </tr>

        {{-- Spasi --}}
        <tr></tr>

        {{-- BARIS 4: HEADER TABEL --}}
        <tr style="background-color: #cccccc; border: 1px solid #000000;">
            <th style="border: 1px solid #000000; width: 5px; font-weight:bold;">No</th>
            <th style="border: 1px solid #000000; width: 25px; font-weight:bold;">No. Surat Tugas</th>
            <th style="border: 1px solid #000000; width: 15px; font-weight:bold;">Tgl Pemeriksaan</th>
            <th style="border: 1px solid #000000; width: 30px; font-weight:bold;">Nama Objek</th>
            <th style="border: 1px solid #000000; width: 35px; font-weight:bold;">Alamat</th>
            <th style="border: 1px solid #000000; width: 20px; font-weight:bold;">Kota/Kab</th>
            <th style="border: 1px solid #000000; width: 40px; font-weight:bold;">Petugas Pemeriksa</th>
        </tr>
    </thead>
    <tbody>
        {{-- ... (Isi Body sama seperti sebelumnya) ... --}}
        @foreach($data as $key => $ba)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $key + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $ba->no_surat_tugas }}</td>
                <td style="border: 1px solid #000000;">{{ \Carbon\Carbon::parse($ba->tanggal_pemeriksaan)->format('d-m-Y') }}</td>
                <td style="border: 1px solid #000000;">{{ $ba->objek_nama }}</td>
                <td style="border: 1px solid #000000;">{{ $ba->objek_alamat }}</td>
                <td style="border: 1px solid #000000;">{{ $ba->objek_kota }}</td>
                <td style="border: 1px solid #000000;">
                    @foreach($ba->petugas as $p)
                        - {{ $p->name }} <br>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>