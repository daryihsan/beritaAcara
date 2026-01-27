<div class="flex items-center justify-center gap-2">
    {{-- Tombol PDF --}}
    <a href="{{ route('berita-acara.pdf', $row->id) }}" 
       target="_blank" 
       class="btn btn-primary btn-sm">
        <span class="glyphicon glyphicon-print"></span> PDF
    </a>

    {{-- Cek Hak Akses Edit --}}
    @php
        $isPetugas = $row->petugas->contains('nip', $user->nip);
    @endphp

    @if($user->isAdmin() || $isPetugas)
        <a href="{{ route('berita-acara.edit', $row->id) }}" 
           class="btn btn-warning btn-sm text-white">
            <span class="glyphicon glyphicon-edit"></span> Edit
        </a>
    @endif

    {{-- Tombol Hapus (Admin Only) --}}
    @if($user->isAdmin())
        <form id="delete-form-{{ $row->id }}"
              action="{{ route('berita-acara.destroy', $row->id) }}" 
              method="POST" 
              class="m-0 inline-block">
            @csrf
            @method('DELETE')
            <button type="button" 
                    class="btn btn-danger btn-sm" 
                    onclick="confirmDelete(event, 'delete-form-{{ $row->id }}')">
                <span class="glyphicon glyphicon-trash"></span> Hapus
            </button>
        </form>
    @endif
</div>