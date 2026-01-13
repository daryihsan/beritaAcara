@extends('layouts.app')

@section('content')

@php $isEdit = isset($ba); @endphp
<form method="POST" 
    action="{{ $isEdit ? route('berita-acara.update', $ba->id) : route('berita-acara.store') }}" 
    id="formBeritaAcara" novalidate>
    @csrf
    
    @if($isEdit)
        @method('PUT')
    @endif

    @include('bap.partials.alert')

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#kelengkapan">Kelengkapan</a></li>
        <li><a data-toggle="tab" href="#petugas">Petugas</a></li>
        <li><a data-toggle="tab" href="#objek">Objek</a></li>
        <li><a data-toggle="tab" href="#hasil">Hasil</a></li>
        <li><a data-toggle="tab" href="#kepala">Kepala</a></li>
    </ul>

    <div class="tab-content">
        @include('bap.partials.tab-kelengkapan')
        @include('bap.partials.tab-petugas')
        @include('bap.partials.tab-objek')
        @include('bap.partials.tab-hasil')
        @include('bap.partials.tab-kepala')
    </div>
</form>
@endsection
