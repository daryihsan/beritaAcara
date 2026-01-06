<div id="petugas" class="tab-pane fade">
    <h4>Daftar Petugas Pemeriksa</h4>
    <div id="petugas-container">
        <div class="petugas-row" id="row-0">
            <div class="row">
                <div class="col-md-4">
                    <label>Nama Petugas</label>
                    <input type="text" name="petugas_nama[]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Pangkat/Gol</label>
                    <input type="text" name="petugas_pangkat[]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>NIP</label>
                    <input type="text" name="petugas_nip[]" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>Aksi</label><br>
                    <button type="button" class="btn btn-danger btn-sm" disabled>Hapus</button>
                </div>
            </div>
            <div class="row" style="margin-top:5px;">
                <div class="col-md-12">
                    <input type="text" name="petugas_jabatan[]" class="form-control" placeholder="Jabatan">
                </div>
            </div>
        </div>
    </div>

    <br>
    <button type="button" class="btn btn-success" id="btn-tambah-petugas">
        <span class="glyphicon glyphicon-plus"></span> Tambah Petugas
    </button>

    <div style="display:flex; justify-content:flex-end; margin-top:15px;">
        <button type="button" class="btn btn-primary btn-next pull-right" data-next="#kepala">
            Next →
        </button>
    </div>
</div>


