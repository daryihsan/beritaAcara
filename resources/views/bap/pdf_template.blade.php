<html>
<head>
    <style>
        @page { margin: 4.5cm 2.54cm 2.54cm 2.54cm; }
        header { position: fixed; top: -4.2cm; left: 0cm; right: 0cm; height: 4cm; text-align:center; }
        footer { position: fixed; bottom: 2cm; left: 0cm; right: -1.4cm; height: 4cm; text-align:center; }
        body { font-family: "serif"; font-size: 12pt; line-height: 1.5; }
        .judul { text-align:center; font-weight:bold; text-decoration:underline; font-size: 14pt; margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; }
    </style>
</head>
<body>
    <header><img src="{{ $logo }}" style="width: 130%;"></header>
    <footer><img src="{{ $footer }}" style="width: 135%;"></footer>

    <main>
        <div class="judul">BERITA ACARA PEMERIKSAAN SETEMPAT</div>

        <p>Pada hari ini <b>{{ $data['hari'] }}</b>, tanggal <b>{{ $teks_tgl['tgl'] }}</b>, bulan <b>{{ $teks_tgl['bln'] }}</b>, tahun <b>{{ $teks_tgl['thn'] }}</b>, kami yang bertanda tangan dibawah ini:</p>

        <table>
            @foreach($list_petugas as $i => $ptg)
            <tr><td width="5%">{{ $i+1 }}.</td><td width="20%">Nama</td><td>: {{ $ptg['nama'] }}</td></tr>
            <tr><td></td><td>Pangkat/Gol</td><td>: {{ $ptg['pangkat'] }}</td></tr>
            <tr><td></td><td>Jabatan</td><td>: {{ $ptg['jabatan'] }}</td></tr>
            @endforeach
        </table>

        <p>Berdasarkan Surat Tugas Nomor: <b>{{ $data['no_surat_tugas'] }}</b>...</p>
        
        <p>Hasil: {{ $data['hasil_pemeriksaan'] }}</p>

        <table style="margin-top:30px">
            <tr>
                <td width="50%">Yang diperiksa,<br><br><br><br><b>{{ $data['yang_diperiksa'] }}</b></td>
                <td>Yang Membuat Berita Acara:<br>
                    @foreach($list_petugas as $i => $ptg)
                        {{ $i+1 }}. {{ $ptg['nama'] }}<br>
                    @endforeach
                </td>
            </tr>
        </table>
    </main>
</body>
</html>