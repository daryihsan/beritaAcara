<div id="petugas" class="tab-pane fade">
    <h4>Daftar Petugas Pemeriksa</h4>
    <div id="petugas-container">
        <div class="petugas-row" id="row-0"
            style="margin-bottom: 15px; border-bottom: 1px solid #f4f4f4; padding-bottom: 10px;">
            <div class="row">
                <div class="col-md-4">
                    <label>Nama Petugas</label>
                    <input type="text" name="petugas_nama[]" class="form-control input-nama" list="list-petugas"
                        autocomplete="off" required value="{{ old('petugas_nama.0') }}" placeholder="Ketik nama...">
                </div>
                <div class="col-md-3">
                    <label>Pangkat/Gol</label>
                    <input type="text" name="petugas_pangkat[]" class="form-control input-pangkat" readonly
                        value="{{ old('petugas_pangkat.0') }}" placeholder="Penata...">
                </div>
                <div class="col-md-3">
                    <label>NIP</label>
                    <input type="text" name="petugas_nip[]" class="form-control input-nip" readonly
                        value="{{ old('petugas_nip.0') }}" placeholder="1234...">
                </div>
                <div class="col-md-2">
                    <label>Aksi</label><br>
                    <button type="button" class="btn btn-danger btn-sm btn-hapus" disabled>Hapus</button>
                </div>
            </div>
            <div class="row" style="margin-top:5px;">
                <div class="col-md-12">
                    <input type="text" name="petugas_jabatan[]" class="form-control input-jabatan" readonly
                        value="{{ old('petugas_jabatan.0') }}" placeholder="Pengawas...">
                </div>
            </div>
        </div>
    </div>

    <datalist id="list-petugas">
        @foreach($petugas as $p)
            <option value="{{ $p->name }}" data-nip="{{ $p->nip }}" data-pangkat="{{ $p->pangkat }}"
                data-jabatan="{{ $p->jabatan }}">
        @endforeach
    </datalist>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
        <button type="button" class="btn btn-success" id="btn-tambah-petugas">
            <span class="glyphicon glyphicon-plus"></span> Tambah Petugas
        </button>

        <div style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-default btn-next" data-next="#kelengkapan">← Back</button>
            <button type="button" class="btn btn-primary btn-next" data-next="#objek">Next →</button>
        </div>
    </div>
</div>