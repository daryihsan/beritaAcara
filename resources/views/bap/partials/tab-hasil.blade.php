<div id="hasil" class="tab-pane fade">
    <h4>Hasil Pemeriksaan</h4>
    <div class="form-group">
        <label>Detail Hasil Pemeriksaan</label>
        <textarea name="hasil_pemeriksaan" class="form-control" rows="5" required>{{ old('hasil_pemeriksaan', $isEdit ? $ba->hasil_pemeriksaan : '') }}</textarea>
    </div>
    <div class="form-group">
        <label>Nama Penanggung Jawab/Pemilik</label>
        <input type="text" name="yang_diperiksa" class="form-control" required value="{{ old('yang_diperiksa', $isEdit ? $ba->yang_diperiksa : '') }}" placeholder="Nama pimpinan/apoteker">
    </div>
    <div style="display:flex; justify-content:flex-end; margin-top:15px; gap: 10px;">
        <button type="button" class="btn btn-default btn-next" data-next="#objek">← Back</button>    
        <button type="button" class="btn btn-primary btn-next pull-right" data-next="#kepala">
            Next →
        </button>
    </div>
</div>