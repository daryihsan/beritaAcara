<div class="flex flex-wrap items-center justify-center gap-1 w-full">
    <!-- Tombol PDF -->
    <a href="{{ route('berita-acara.pdf', $row->id) }}" target="_blank" class="btn btn-primary btn-sm flex-1 whitespace-nowrap">
        <span class="glyphicon glyphicon-print"></span> PDF
    </a>

    <!-- Cek hak akses edit -->
    @php
        $isPetugas = $row->petugas->contains('nip', $user->nip);
    @endphp

    @if($user->isAdmin() || $isPetugas)
        <a href="{{ route('berita-acara.edit', $row->id) }}" class="btn btn-warning btn-sm text-white flex-1 whitespace-nowrap">
            <span class="glyphicon glyphicon-edit"></span> Edit
        </a>
    @endif

    <!-- Tombol hapus (admin only) -->
    @if($user->isAdmin())
        <form id="delete-form-{{ $row->id }}" action="{{ route('berita-acara.destroy', $row->id) }}" method="POST"
            class="m-0 inline-block flex-1" style="min-width: fit-content;">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger btn-sm w-full whitespace-nowrap"
                onclick="confirmDelete(event, 'delete-form-{{ $row->id }}')">
                <span class="glyphicon glyphicon-trash"></span> Hapus
            </button>
        </form>
    @endif
</div>