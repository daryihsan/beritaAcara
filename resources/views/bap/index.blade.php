@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-5xl font-extrabold text-slate-800 tracking-tight">Daftar Berita Acara</h1>
    <a href="/berita-acara/create" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 font-bold">
        <span class="glyphicon glyphicon-plus mr-2"></span> Tambah Data
    </a>
</div>

<div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
    <table id="tableBap" class="w-full">
        <thead class="bg-slate-50">
            <tr class="text-slate-700">
                <th style="min-width: 180px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">No. Surat Tugas</th>
                <th style="min-width: 300px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Nama Petugas</th>
                <th style="min-width: 200px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Objek</th>
                <th style="min-width: 120px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Tanggal Periksa</th>
                <th style="min-width: 120px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Tanggal BAP</th>
                <th style="min-width: 100px;" class="text-xl font-bold uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-slate-700">
            @forelse($data as $ba)
            <tr class="hover:bg-blue-50/50 transition border-b border-slate-100">
                <td class="p-5 text-left">{{ $ba->no_surat_tugas }}</td>
                <td class="p-5 text-left">
                    <div>
                        @foreach($ba->petugas as $p) <span>{{ $p->name }}</span><br> @endforeach
                    </div>
                </td>
                <td class="p-5 text-left">{{ $ba->objek_nama }}</td>
                <td class="p-5 text-left">{{ \Carbon\Carbon::parse($ba->tanggal_pemeriksaan)->format('d M Y') }}</td>
                <td class="p-5 text-left">{{ \Carbon\Carbon::parse($ba->tanggal_berita_acara)->format('d M Y') }}</td>
                <td class="p-5 text-center flex justify-center gap-2">
    <a href="{{ route('berita-acara.pdf', $ba->id) }}" target="_blank" class="btn btn-primary btn-sm">
        <span class="glyphicon glyphicon-print"></span> PDF
    </a>

    @if(auth()->user()->isAdmin())
        <form action="{{ route('berita-acara.destroy', $ba->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data palsu/rekayasa ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <span class="glyphicon glyphicon-trash"></span> Hapus
            </button>
        </form>
    @endif
</td>
            </tr>
            @empty
            {{-- DataTables akan menangani tampilan kosong lewat bahasa Indonesia --}}
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tableBap').DataTable({
        "paging": true,
        "ordering": true,
        "info": true,
        "columnDefs": [
            { "orderable": false, "targets": [1, 5] } 
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });
});
</script>
@endpush