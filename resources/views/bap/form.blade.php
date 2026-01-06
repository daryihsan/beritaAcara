@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Berita Acara</div>
    <div class="panel-body">
        <form method="POST" action="{{ route('bap.cetak') }}">
            @csrf
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#kelengkapan">Kelengkapan Surat</a></li>
                <li><a data-toggle="tab" href="#petugas">Petugas</a></li>
                <li><a data-toggle="tab" href="#objek">Objek/Sarana</a></li>
                <li><a data-toggle="tab" href="#hasil">Hasil</a></li>
                <li><a data-toggle="tab" href="#kepala">Kepala Balai</a></li>
            </ul>

            <div class="tab-content">
                <div id="kelengkapan" class="tab-pane fade in active">
                    <h4>Informasi Surat Tugas</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <label>No. Surat Tugas</label>
                            <input type="text" name="no_surat_tugas" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Surat Tugas</label>
                            <input type="date" name="tgl_surat_tugas" class="form-control" required>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-6">
                            <label>Hari Pemeriksaan</label>
                            <input type="text" name="hari" class="form-control" placeholder="Senin" required>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Pemeriksaan</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div id="petugas" class="tab-pane fade">
                    <div id="petugas-container">
                        <div class="petugas-row">
                            <div class="row">
                                <div class="col-md-4"><label>Nama</label><input type="text" name="petugas_nama[]" class="form-control" required></div>
                                <div class="col-md-3"><label>Pangkat</label><input type="text" name="petugas_pangkat[]" class="form-control"></div>
                                <div class="col-md-3"><label>NIP</label><input type="text" name="petugas_nip[]" class="form-control"></div>
                                <div class="col-md-2"><label>Aksi</label><br><button type="button" class="btn btn-danger btn-sm" disabled>Hapus</button></div>
                            </div>
                            <input type="text" name="petugas_jabatan[]" class="form-control" placeholder="Jabatan" style="margin-top:5px">
                        </div>
                    </div>
                    <button type="button" id="btn-tambah-petugas" class="btn btn-success">Tambah Petugas</button>
                </div>

                <div id="objek" class="tab-pane fade">
                    <div class="form-group"><label>Nama Objek</label><input type="text" name="objek_nama" class="form-control" required></div>
                    <div class="form-group"><label>Alamat</label><input type="text" name="objek_alamat" class="form-control" required></div>
                    <div class="form-group"><label>Kab/Kota</label><input type="text" name="objek_kota" class="form-control"></div>
                    <div class="form-group"><label>Dalam Rangka</label><input type="text" name="dalam_rangka" class="form-control"></div>
                </div>

                <div id="hasil" class="tab-pane fade">
                    <textarea name="hasil_pemeriksaan" class="form-control" rows="6"></textarea>
                    <div class="form-group" style="margin-top:10px"><label>Nama Penanggung Jawab</label><input type="text" name="yang_diperiksa" class="form-control"></div>
                </div>

                <div id="kepala" class="tab-pane fade">
                    <select name="kepala_balai_text" class="form-control" required>
                        <option value="Balai Besar POM di Semarang">Kepala Balai</option>
                        <option value="Plh. Kepala Balai">Plh. Kepala Balai</option>
                        <option value="Plt. Kepala Balai">Plt. Kepala Balai</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-block" style="margin-top:20px">PROSES & CETAK BAP</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Taruh script jQuery Tambah/Hapus Petugas kamu di sini
    $("#btn-tambah-petugas").click(function(){
        let html = `... (sama seperti kode native kamu) ...`;
        $("#petugas-container").append(html);
    });
</script>
@endpush