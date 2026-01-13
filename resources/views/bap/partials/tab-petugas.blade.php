<div id="petugas" class="tab-pane fade">
    <h4>Daftar Petugas Pemeriksa</h4>

    <div id="petugas-container">
        @php
            $listPetugas = (isset($ba) && $ba->petugas->count() > 0) ? $ba->petugas : [null];
        @endphp

        @foreach($listPetugas as $index => $petugasExisting)
            <div class="petugas-row" id="row-{{ $index }}" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                
                {{-- INPUT HIDDEN TTD (Wajib ada untuk JS) --}}
                <input type="hidden" name="petugas_ttd[]" class="input-ttd-base64" 
                       value="{{ old('petugas_ttd.'.$index, optional($petugasExisting)->pivot->ttd ?? '') }}">

                <div class="row" style="display: flex; flex-wrap: wrap;">
                    
                    {{-- BAGIAN 1: DATA DIRI (KIRI) --}}
                    <div class="col-md-7">
                        
                        {{-- Baris Atas: Nama & NIP --}}
                        <div class="row">
                            <div class="col-md-8">
                                <label class="text">Nama Petugas</label>
                                <input type="text" name="petugas_nama[]" class="form-control input-nama" list="list-petugas"
                                    autocomplete="off" required 
                                    value="{{ old('petugas_nama.'.$index, optional($petugasExisting)->name) }}" 
                                    placeholder="Ketik nama...">
                            </div>
                            <div class="col-md-4">
                                <label class="text">NIP</label>
                                <input type="text" name="petugas_nip[]" class="form-control input-nip" readonly
                                    style="background-color: #f9fafb;"
                                    value="{{ old('petugas_nip.'.$index, optional($petugasExisting)->nip) }}">
                            </div>
                        </div>

                        {{-- Baris Bawah: Jabatan & Pangkat --}}
                        <div class="row" style="margin-top: 8px;">
                            <div class="col-md-8">
                                <label class="text">Jabatan</label>
                                <input type="text" name="petugas_jabatan[]" class="form-control input-jabatan" readonly
                                    style="background-color: #f9fafb;"
                                    value="{{ old('petugas_jabatan.'.$index, optional($petugasExisting)->pivot->jabatan ?? optional($petugasExisting)->jabatan) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="text">Pangkat/Gol</label>
                                <input type="text" name="petugas_pangkat[]" class="form-control input-pangkat" readonly
                                    style="background-color: #f9fafb;"
                                    value="{{ old('petugas_pangkat.'.$index, optional($petugasExisting)->pivot->pangkat ?? optional($petugasExisting)->pangkat) }}">
                            </div>
                        </div>
                    </div>

                    {{-- BAGIAN 2: TANDA TANGAN (TENGAH) --}}
                    <div class="col-md-4" style="border-left: 1px dashed #ddd; padding-left: 15px;">
                        <label class="text" style="display:block; text-align:center;">Tanda Tangan</label>
                        
                        <div class="ttd-preview-area" 
                             style="border: 1px solid #e5e7eb; height: 95px; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: #fff; position: relative;">
                            
                            {{-- Gambar Preview TTD --}}
                            <img src="{{ old('petugas_ttd.'.$index, optional($petugasExisting)->pivot->ttd ?? '') }}" 
                                 class="img-ttd-preview" 
                                 style="max-height: 80px; max-width: 90%; {{ (old('petugas_ttd.'.$index, optional($petugasExisting)->pivot->ttd ?? '') ) ? '' : 'display:none;' }}">
                            
                            {{-- Placeholder Text --}}
                            <span class="text-placeholder text-gray-400 small" 
                                  style="font-size: 14px; color: #999; {{ (old('petugas_ttd.'.$index, optional($petugasExisting)->pivot->ttd ?? '') ) ? 'display:none;' : '' }}">
                                (Kosong)
                            </span>

                            {{-- TOMBOL PEN (OVERLAY POJOK KANAN BAWAH) --}}
                            @php
                                $user = auth()->user();
                                $isMyRow = (optional($petugasExisting)->nip == $user->nip);
                                $canSign = $user->isAdmin() || $isMyRow || !isset($ba); 
                            @endphp

                            @if($canSign)
                                <button type="button" class="btn btn-xs btn-primary btn-open-signature" 
                                        style="position: absolute; bottom: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display:flex; align-items:center; justify-content:center;">
                                    <span class="glyphicon glyphicon-pencil" style="font-size: 10px;"></span>
                                </button>
                            @endif
                        </div>
                         @if(!$canSign && isset($ba))
                            <div class="text-center" style="margin-top:2px;">
                                <small class="text-danger" style="font-size: 9px;"><i>Read-only</i></small>
                            </div>
                         @endif
                    </div>

                    {{-- BAGIAN 3: TOMBOL HAPUS (KANAN) --}}
                    <div class="col-md-1" style="display: flex; align-items: center; justify-content: center;">
                        <button type="button" class="btn btn-danger btn-sm btn-hapus" 
                                style="margin-top: 15px;"
                                {{ $loop->count <= 1 ? 'disabled' : '' }} 
                                title="Hapus Petugas">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </div>

                </div> {{-- End Row --}}
            </div>
        @endforeach
    </div>

    {{-- DATALIST --}}
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

{{-- MODAL DI LUAR (PUSH STACK) --}}
@push('modals')
<div class="modal fade" id="modalSignature" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambahkan Tanda Tangan</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                    <li class="active"><a data-toggle="tab" href="#tab-draw">TTD Manual</a></li>
                    <li><a data-toggle="tab" href="#tab-image">Upload Gambar</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-draw" class="tab-pane fade in active">
                        <div style="border: 1px solid #ccc; background: #fff;">
                            <canvas id="signature-pad" width="500" height="200" style="width: 100%; height: 200px; touch-action: none;"></canvas>
                        </div>
                        <div class="text-right" style="margin-top: 5px;">
                            <button type="button" class="btn btn-default btn-xs" id="clear-signature">Bersihkan</button>
                        </div>
                    </div>
                    <div id="tab-image" class="tab-pane fade">
                        <div class="form-group">
                            <label>Upload Gambar Tanda Tangan (PNG/JPG)</label>
                            <input type="file" class="form-control" id="upload-signature" accept="image/*">
                        </div>
                        <div id="image-preview-container" style="display:none; text-align:center;">
                            <img id="image-preview" src="" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="save-signature">Simpan Tanda Tangan</button>
            </div>
        </div>
    </div>
</div>
@endpush