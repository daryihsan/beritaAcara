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

        <!-- Area tombol & filter -->
        <div class="flex flex-wrap gap-2 items-center">

            <!-- Form filter rekap -->
            <form method="GET"
                class="flex flex flex-col md:flex-row gap-2 bg-white p-2 rounded-xl shadow-lg border border-slate-200 w-full md:w-auto">

                <!-- Dropdown pilih tahun -->
                <select name="tahun"
                    class="form-select border-0 focus:ring-0 text-sm font-semibold text-slate-600 bg-transparent py-2 pl-3 pr-8 cursor-pointer outline-none">
                    <option value="{{ date('Y') }}" selected>Tahun Ini ({{ date('Y') }})</option>
                    <option value="semua">Semua Data (Urut Waktu)</option>
                    @foreach(range(date('Y') - 1, 2020) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>

                <div class="w-px h-13 bg-slate-300 mx-1"></div>

                <!-- Dropdown petugas -->
                @if(auth()->user()->isAdmin())
                    <select name="filter_petugas"
                        class="form-select border-0 focus:ring-0 text-sm font-semibold text-slate-600 bg-transparent py-2 pl-3 pr-8 cursor-pointer outline-none"
                        style="max-width: 150px;">
                        <option value="semua" selected>Semua Petugas</option>
                        @foreach($allPetugas as $p)
                            <option value="{{ $p->nip }}">{{ Str::limit($p->name, 15) }}</option>
                        @endforeach
                    </select>
                @endif

                <div class="flex gap-2 justify-center w-full md:w-auto">
                    <!-- Tombol excel -->
                    <button type="submit" formaction="{{ route('berita-acara.export.excel') }}"
                        style="color: #fff !important"
                        class="bg-green-500 hover:bg-green-700 px-6 py-3 rounded-xl transition-all ring-1 ring-black/5 hover:shadow-xl transition-all transform hover:-translate-y-1 font-bold no-underline flex items-center gap-2"
                        title="Download Excel">
                        <span class="glyphicon glyphicon-save-file" style="color: #fff !important"></span>
                        <span style="color: #fff !important;">Excel</span>
                    </button>

                    <!-- Tombol PDF -->
                    <button type="submit" formaction="{{ route('berita-acara.export.pdflist') }}" formtarget="_blank"
                        style="color: #fff !important"
                        class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl transition-all ring-1 ring-black/5 hover:shadow-xl transition-all transform hover:-translate-y-1 font-bold no-underline flex items-center gap-2"
                        title="Download PDF">
                        <span class="glyphicon glyphicon-print" style="color: #fff !important"></span>
                        <span style="color: #fff !important;">PDF</span>
                    </button>
                </div>
            </form>

            <!-- Pemisah -->
            <div class="w-px h-8 bg-slate-300 mx-1"></div>

            <!-- Tombol tambah data -->
            <div class="btn-tambah-wrapper">
                <a href="{{ route('berita-acara.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all ring-1 ring-black/5 hover:shadow-xl transition-all transform hover:-translate-y-1 font-bold no-underline flex items-center gap-2">
                    <span class="glyphicon glyphicon-plus mr-2"></span>
                    <span>Tambah</span>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-200">
        <div class="dt-top"></div>

        <div class="table-scroll-x" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
            <table id="tableBap" class="w-full table-auto md:table-fixed border-collapse">
                <thead class="bg-slate-50">
                    <tr class="text-slate-700">

                        <th
                            class="min-w-[180px] md:w-[18%] p-3 text-left text-xl font-bold uppercase tracking-wider border-r border-slate-100 break-words">
                            No. Surat Tugas
                        </th>

                        <th
                            class="min-w-[250px] md:w-[25%] p-3 text-left text-xl font-bold uppercase tracking-wider border-r border-slate-100 break-words">
                            Nama Petugas
                        </th>

                        <th
                            class="min-w-[200px] md:w-[15%] p-3 text-left text-xl font-bold uppercase tracking-wider border-r border-slate-100 break-words">
                            Objek
                        </th>

                        <th
                            class="min-w-[120px] md:w-[10%] p-3 text-left text-xl font-bold uppercase tracking-wider border-r border-slate-100">
                            Tanggal Periksa
                        </th>

                        <th
                            class="min-w-[120px] md:w-[10%] p-3 text-left text-xl font-bold uppercase tracking-wider border-r border-slate-100">
                            Tanggal BAP
                        </th>

                        <th
                            class="min-w-[100px] md:w-[7%] p-3 text-center text-xl font-bold uppercase tracking-wider border-r border-slate-100">
                            Status
                        </th>

                        <th class="min-w-[150px] md:w-[15%] p-3 text-center text-xl font-bold uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                </tbody>
            </table>
        </div>

        <div class="dt-bottom"></div>
    </div>

    <!-- Modal Upload Pengesahan -->
    <div id="uploadModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        {{-- Backdrop: glass blur effect --}}
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeUploadModal()"></div>

        {{-- Centering wrapper --}}
        <div class="fixed inset-0 flex items-center justify-center p-4">

            {{-- Modal Box --}}
            <div
                class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-md md:max-w-lg lg:max-w-xl ring-1 ring-black/10 overflow-hidden">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="glyphicon glyphicon-cloud-upload text-3xl md:text-4xl" style="color:white !important"></span>
                        <div class="px-3">
                            <h3 class="text-base md:text-xl font-bold leading-tight" style="color:white !important"
                                id="modal-title">
                                Upload Dokumen Sah
                            </h3>
                            <p class="text-blue-100 text-xs md:text-base mt-0.5">Scan atau foto BAP yang sudah ditandatangani
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeUploadModal()"
                        class="hover:bg-white/20 rounded-lg p-1.5 transition-all border-0 bg-transparent"
                        style="color:rgba(255,255,255,0.7) !important">
                        <span class="glyphicon glyphicon-remove text-base md:text-lg"></span>
                    </button>
                </div>

                {{-- Body --}}
                <form id="formUpload" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-4 py-4 md:px-6 md:py-5">

                        {{-- Info No Surat --}}
                        <div
                            class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-5 overflow-hidden">
                            <span class="glyphicon glyphicon-file text-blue-400 text-lg flex-shrink-0"></span>
                            <span
                                class="text-sm md:text-base text-slate-600 whitespace-nowrap overflow-hidden text-ellipsis">
                                No. Surat: <span id="uploadNoSurat" class="font-bold text-blue-500"></span>
                            </span>
                        </div>

                        {{-- Dropzone --}}
                        <label class="block w-full cursor-pointer group">
                            <div id="dropzone"
                                class="w-full border-2 border-dashed border-slate-300 rounded-xl p-4 md:p-8 flex flex-col items-center justify-center gap-3 group-hover:border-blue-400 group-hover:bg-blue-50/50 bg-slate-50 transition-all min-h-[120px] md:min-h-[200px]">

                                {{-- Preview area --}}
                                <div id="previewContainer" class="flex flex-col items-center gap-2 w-full">
                                    <span id="previewIcon"
                                        class="glyphicon glyphicon-camera text-3xl md:text-6xl text-slate-300 group-hover:text-blue-400 transition-colors"></span>
                                    <img id="imagePreview" src=""
                                        class="hidden max-h-32 md:max-h-64 rounded-xl shadow-md object-contain w-full">
                                    <div id="pdfPreview" class="hidden flex-col items-center gap-1 text-red-500">
                                        <span class="glyphicon glyphicon-file text-4xl md:text-6xl"></span>
                                        <span
                                            class="text-xs font-bold uppercase tracking-wider bg-red-100 text-red-600 px-3 py-1 rounded-full">PDF
                                            Terdeteksi</span>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <p id="instructionText"
                                        class="text-base md:text-xl font-semibold text-slate-500 group-hover:text-blue-600 transition-colors">
                                        Klik untuk memilih file
                                    </p>
                                    <p class="text-xs md:text-base text-slate-400 mt-0.5">JPG, PNG, atau PDF • Maks. 5MB</p>
                                </div>

                                <input type="file" name="file_pengesahan" class="hidden" accept="image/*,application/pdf"
                                    required onchange="window.handleFilePreview(this)">
                            </div>
                        </label>

                        {{-- Nama file terpilih --}}
                        <div id="fileNameDisplay"
                            class="hidden mt-3 flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                            <span class="glyphicon glyphicon-ok-circle text-green-500 text-sm flex-shrink-0"></span>
                            <span id="fileNameText" class="text-xs md:text-base text-green-700 font-medium truncate"></span>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeUploadModal()"
                            class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm md:text-base font-medium text-slate-700 hover:bg-slate-100 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-sm md:text-base font-semibold shadow-sm hover:shadow-md transition-all flex items-center gap-2"
                            style="color:white !important; border:none;">
                            <span class="glyphicon glyphicon-cloud-upload" style="color:white !important"></span>
                            Upload Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if(session('print_pdf_id'))
        <script>
            $(document).ready(function () {
                // URL PDF 
                var url = "{{ route('berita-acara.pdf', session('print_pdf_id')) }}";

                // Buka di tab baru
                window.open(url, '_blank');

                // Tampilkan notifikasi (toast)
                toastr.success('PDF sedang dibuka di tab baru...');
            });
        </script>
    @endif
@endpush