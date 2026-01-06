@if ($errors->any())
    <div class="alert alert-danger" id="formAlertGlobal">
        <strong>Perhatian!</strong>
        <ul>
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div id="formAlert" class="alert alert-danger" style="display:none;">
    <strong>Perhatian!</strong> Mohon lengkapi semua isian pada bagian ini terlebih dahulu.
</div>
<div id="formAlertGlobal" class="alert alert-danger" style="display:none;">
    <strong>Perhatian!</strong> Masih ada data yang belum diisi. Silakan lengkapi terlebih dahulu.
</div>