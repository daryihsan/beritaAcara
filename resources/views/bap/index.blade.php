@extends('layouts.app')

@section('content')

<h1 class="text-xl font-semibold mb-4">
    Daftar Berita Acara
</h1>

<div class="bg-white rounded-lg shadow overflow-hidden">

<table class="w-full">
    <thead class="bg-gray-50 text-left text-sm">
        <tr>
            <th class="p-3">No Surat</th>
            <th class="p-3">Objek</th>
            <th class="p-3">Aksi</th>
        </tr>
    </thead>

    <tbody class="text-sm">
        @forelse($data as $ba)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">{{ $ba->no_surat }}</td>
                <td class="p-3">{{ $ba->objek }}</td>
                <td class="p-3">
<a href="{{ url('/berita-acara/'.$ba->id.'/pdf') }}" class="text-blue-600">PDF</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-6 text-center text-gray-400">
                    Belum ada data
                </td>
            </tr>
        @endforelse
    </tbody>

</table>

</div>

@endsection
