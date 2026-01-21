@extends('layouts.app')
@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
@endsection
@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="w-full md:w-auto text-left">
            <h1 class="text-5xl font-extrabold text-slate-800 tracking-tight">
                Daftar Berita Acara
                @if(request('tahun') && request('tahun') !== 'semua')
                    Tahun {{ request('tahun') }}
                @endif
            </h1>
            <p class="text-slate-500 mt-2">
                Semua berita acara  
                @if(request('tahun') && request('tahun') !== 'semua')
                    pada tahun {{ request('tahun') }} yang dibuat.
                @else
                    yang telah dibuat.
                @endif
            </p>
        </div>

        {{-- AREA TOMBOL & FILTER --}}
        <div class="flex flex-wrap gap-2 items-center">
            
            {{-- FORM FILTER REKAP --}}
            <form method="GET" class="flex flex flex-col md:flex-row gap-2 bg-white p-2 rounded-xl shadow-lg border border-slate-200 w-full md:w-auto">
                
                {{-- Dropdown Pilih Tahun --}}
                <select name="tahun" class="form-select border-0 focus:ring-0 text-sm font-semibold text-slate-600 bg-transparent py-2 pl-3 pr-8 cursor-pointer outline-none">
                    <option value="{{ date('Y') }}" selected>Tahun Ini ({{ date('Y') }})</option>
                    <option value="semua">Semua Data (Urut Waktu)</option>
                    @foreach(range(date('Y')-1, 2020) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>

                <div class="w-px h-13 bg-slate-300 mx-1"></div>

                {{-- 2. Dropdown Petugas (KHUSUS ADMIN) --}}
                @if(auth()->user()->isAdmin())
                    <select name="filter_petugas" class="form-select border-0 focus:ring-0 text-sm font-semibold text-slate-600 bg-transparent py-2 pl-3 pr-8 cursor-pointer outline-none" style="max-width: 150px;">
                        <option value="semua" selected>Semua Petugas</option>
                        @foreach($allPetugas as $p)
                            <option value="{{ $p->nip }}">{{ Str::limit($p->name, 15) }}</option>
                        @endforeach
                    </select>
                @endif

                <div class="flex gap-2 justify-center w-full md:w-auto">
                    {{-- Tombol Excel --}}    
                    <button type="submit" formaction="{{ route('berita-acara.export.excel') }}" 
                            style="color: #fff !important"
                            class="bg-green-500 hover:bg-green-700 px-6 py-3 rounded-xl transition-all ring-1 ring-black/5 hover:shadow-xl transition-all transform hover:-translate-y-1 font-bold no-underline flex items-center gap-2" 
                            title="Download Excel">
                        <span class="glyphicon glyphicon-save-file" style="color: #fff !important"></span> 
                        <span style="color: #fff !important;">Excel</span>
                    </button>

                    {{-- Tombol PDF --}}
                    <button type="submit" formaction="{{ route('berita-acara.export.pdflist') }}" formtarget="_blank"
                            style="color: #fff !important"
                            class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl transition-all ring-1 ring-black/5 hover:shadow-xl transition-all transform hover:-translate-y-1 font-bold no-underline flex items-center gap-2" 
                            title="Download PDF">
                        <span class="glyphicon glyphicon-print" style="color: #fff !important"></span> 
                        <span style="color: #fff !important;">PDF</span>
                    </button>
                </div>
            </form>

            {{-- PEMISAH --}}
            <div class="w-px h-8 bg-slate-300 mx-1"></div>

            {{-- TOMBOL TAMBAH DATA (UTAMA) --}}
            <div class="btn-tambah-wrapper">
                <a href="/berita-acara/create"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all ring-1 ring-black/5 hover:shadow-xl transition-all transform hover:-translate-y-1 font-bold no-underline flex items-center gap-2">
                    <span class="glyphicon glyphicon-plus mr-2"></span> 
                    <span>Tambah</span>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-200">
        <!-- HORIZONTAL SCROLL CONTAINER -->
        <div class="dt-top"></div>

        <div class="table-scroll-x" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
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
                            <td class="p-5 text-left">
                                {{ 
                                    \Carbon\Carbon::parse(
                                        $ba->created_at ?? $ba->tanggal_pemeriksaan
                                    )->format('d M Y') 
                                }}
                            </td>
                            <td class="p-5 text-center border-l border-slate-100">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('berita-acara.pdf', $ba->id) }}" target="_blank" class="btn btn-primary btn-sm">
                                        <span class="glyphicon glyphicon-print"></span> PDF
                                    </a>

                                    @if(auth()->user()->isAdmin() || $ba->petugas->contains('nip', auth()->user()->nip))
                                        <a href="{{ route('berita-acara.edit', $ba->id) }}" class="btn btn-warning btn-sm text-white">
                                            <span class="glyphicon glyphicon-edit"></span> Edit
                                        </a>
                                    @endif

                                    @if(auth()->user()->isAdmin())
                                        <form action="{{ route('berita-acara.destroy', $ba->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data rusak/palsu/rekayasa ini?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <span class="glyphicon glyphicon-trash"></span> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- DataTables akan menangani tampilan kosong lewat bahasa Indonesia --}}
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dt-bottom"></div>
    </div>

@endsection

@push('scripts') {{-- Atau langsung taruh di bawah jika tidak pakai stack --}}
    @if(session('print_pdf_id'))
        <script>
            $(document).ready(function() {
                // URL PDF (Sesuaikan dengan nama route Anda)
                var url = "{{ route('berita-acara.pdf', session('print_pdf_id')) }}";
                
                // Buka di Tab Baru
                window.open(url, '_blank');
                
                // Opsional: Tampilkan notifikasi (Toast)
                // toastr.success('PDF sedang dibuka di tab baru...');
            });
        </script>
    @endif
@endpush