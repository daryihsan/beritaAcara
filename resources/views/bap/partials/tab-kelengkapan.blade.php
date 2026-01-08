<div id="kelengkapan" class="tab-pane fade in active">
    <h4>Informasi Surat Tugas</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>No. Surat Tugas</label>
                <input type="text" name="no_surat_tugas" class="form-control" required value="{{ old('no_surat_tugas') }}" placeholder="Contoh: ST-123/BPOM/..."
                    required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Surat Tugas</label>
                <input type="date" name="tgl_surat_tugas" class="form-control" required value="{{ old('tgl_surat_tugas') }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Hari Pemeriksaan</label>
                <input type="text" name="hari" class="form-control" required value="{{ old('hari') }}" placeholder="Senin" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Pemeriksaan (Hari H)</label>
                <input type="date" name="tanggal" class="form-control" required value="{{ old('tanggal') }}">
            </div>
        </div>
    </div>
    <div style="display:flex; justify-content:flex-end; margin-top:15px;">
        <button type="button" class="btn btn-primary btn-next pull-right" data-next="#petugas">
            Next →
        </button>
    </div>
</div>