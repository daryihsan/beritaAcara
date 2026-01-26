<div id="kepala" class="tab-pane fade">
    <h4>Otorisasi Kepala Balai</h4>
    <div class="form-group">
        <label>Jabatan Penandatangan (Surat Tugas)</label>
        <select name="kepala_balai_text" class="form-control" required>
            <option value="" disabled selected>-- Pilih Jabatan --</option>

            <option value="Balai Besar POM di Semarang" {{ (old('kepala_balai_text', $ba->kepala_balai_text ?? '') == 'Balai Besar POM di Semarang') ? 'selected' : '' }}>
                Kepala Balai
            </option>

            <option value="Plh. Kepala Balai" {{ (old('kepala_balai_text', $ba->kepala_balai_text ?? '') == 'Plh. Kepala Balai') ? 'selected' : '' }}>
                Plh. Kepala Balai
            </option>

            <option value="Plt. Kepala Balai" {{ (old('kepala_balai_text', $ba->kepala_balai_text ?? '') == 'Plt. Kepala Balai') ? 'selected' : '' }}>
                Plt. Kepala Balai
            </option>
        </select>
    </div>
    <hr>
    <div class="alert alert-info">
        <p><span class="glyphicon glyphicon-info-sign"></span> Pastikan semua data sudah benar sebelum menekan tombol
            Simpan & Cetak.</p>
    </div>
    <div style="display:flex; justify-content:space-between; margin-top:15px;">
        <button type="button" class="btn btn-default btn-next" data-next="#hasil">← Back</button>
        <button type="submit" class="btn btn-success font-bold">
            <span class="glyphicon glyphicon-floppy-disk"></span>
            {{ isset($ba) ? 'Simpan Perubahan BAP' : 'Simpan & Cetak BAP' }}
        </button>
    </div>
</div>