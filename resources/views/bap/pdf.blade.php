<html>

<head>
    <style>
        @page {
            margin: 4.5cm 2.54cm 2.54cm 2.54cm;
        }

        header {
            position: fixed;
            top: -4.2cm;
            left: 0cm;
            right: 0cm;
            height: 4cm;
        }

        footer {
            position: fixed;
            bottom: 2cm;
            left: 0cm;
            right: -1.4cm;
            height: 4cm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Fix typo: width:100%: collapse -> width:100%; border-collapse:collapse */
        .kop-img {
            width: 130%;
            height: auto;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            margin-top: -25px;
            margin-bottom: 40px;
        }

        .ttd-table {
            margin-top: 30px;
            width: 100%;
        }

        .content {
            width: 100%;
            line-height: 1;
        }
    </style>
</head>

<body>

    <header>
        <table style="width: 100%; padding-bottom: 5px;">
            <td align="center"><img src="{{ $logo }}" class="kop-img" alt="Kop Surat"></td>
        </table>
    </header>

    <footer>
        <table style="width: 100%;">
            <td align="center" valign="top"><img src="{{ $footer }}" style="width:135%;"></td>
        </table>
    </footer>

    <main>
        <div class="judul">BERITA ACARA PEMERIKSAAN SETEMPAT</div>

        <p style="text-align: justify; line-height: 1.5;">
            Pada hari ini <b>{{ $data['hari'] }}</b>,
            tanggal <b>{{ $teks_tgl['tgl'] }}</b>,
            bulan <b>{{ $teks_tgl['bln'] }}</b>,
            tahun <b>{{ $teks_tgl['thn'] }}</b>,
            kami yang bertanda tangan dibawah ini:
        </p>

        <table class="content">
            @foreach($list_petugas as $i => $p)
                <tr>
                    <td width="5%" valign="top">{{ $i + 1 }}.</td>
                    <td width="20%" valign="top">Nama</td>
                    <td width="3%" valign="top">: </td>
                    <td valign="top">{{ $p['nama'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td valign="top">Pangkat</td>
                    <td valign="top">: </td>
                    <td valign="top">{{ $p['pangkat'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td valign="top">Jabatan</td>
                    <td valign="top">: </td>
                    <td valign="top">{{ $p['jabatan'] }}</td>
                </tr>
                <tr>
                    <td colspan="4" height="10"></td>
                </tr>
            @endforeach
        </table>

        <p style="text-align: justify; line-height: 1.5;">
            Berdasarkan Surat Tugas Kepala <b>{{ $data['kepala_balai_text'] }}</b>
            Nomor : <b>{{ $data['no_surat_tugas'] }}</b>
            Tanggal <b>{{ $tgl_st }}</b>
            telah melakukan pemeriksaan setempat terhadap:
        </p>

        <table class="content">
            <tr>
                <td width="25%">Nama</td>
                <td width="3%">:</td>
                <td><?= $data['objek_nama'] ?></td>
            </tr>
            <td height="1%"></td>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= $data['objek_alamat'] ?></td>
            </tr>
            <td height="1%"></td>
            <tr>
                <td>Kab/Kota</td>
                <td>:</td>
                <td><?= $data['objek_kota'] ?></td>
            </tr>
            <td height="1%"></td>
            <tr>
                <td>Dalam rangka</td>
                <td>:</td>
                <td><?= $data['dalam_rangka'] ?></td>
            </tr>
        </table>

        <p>Dengan hasil sebagai berikut :</p>
        <div style="padding: 0px; min-height: 100px;">
            {!! nl2br($data['hasil_pemeriksaan']) !!}
        </div>

        <p style="text-align: justify;">
            Demikian Berita Acara ini kami buat dengan sesungguhnya dan penuh tanggung jawab,
            setelah disetujui kemudian ditutup dengan ditanda tangani masing-masing petugas yang memeriksa.
        </p>

        <table style="margin-top:30px">
            <tr>
                <td width="50%" align="left" valign="top">Yang diperiksa,<br> Pemilik/ Penangggung
                    Jawab<br><br><br><br><b>{{ $data['yang_diperiksa'] }}</b></td>
                <td width="50%" align="left" valign="top">Yang Membuat Berita Acara:<br>
                    <table width="100%">
                        @foreach($list_petugas as $i => $p)
                            <tr>
                                <td height="35" style="vertical-align:bottom;">
                                    {{ $i + 1 }}. {{ $p['nama'] }}<br>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>

    </main>
</body>

</html>