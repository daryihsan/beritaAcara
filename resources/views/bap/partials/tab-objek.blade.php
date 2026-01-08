<div id="objek" class="tab-pane fade">
    <h4>Data Objek Pemeriksaan</h4>
    <div class="form-group">
        <label>Nama Objek/Sarana</label>
        <input type="text" name="objek_nama" class="form-control" required value="{{ old('objek_nama') }}">
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="objek_alamat" class="form-control" required value="{{ old('objek_alamat') }}">
    </div>
    <div class="form-group">
        <label>Kabupaten / Kota</label>
        <input type="text" name="objek_kota" class="form-control" required value="{{ old('objek_kota') }}">
    </div>
    <div class="form-group">
        <label>Dalam Rangka (Maksud)</label>
        <input type="text" name="dalam_rangka" class="form-control" required  value="{{ old('dalam_rangka') }}" placeholder="Pemeriksaan rutin...">
    </div>
    <div style="display:flex; justify-content:flex-end; margin-top:15px; gap: 10px;">
        <button type="button" class="btn btn-default btn-next" data-next="#petugas">← Back</button>    
        <button type="button" class="btn btn-primary btn-next pull-right" data-next="#hasil">
            Next →
        </button>
    </div>
</div>