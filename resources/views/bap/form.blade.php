@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('bap.store') }}" novalidate>
@csrf

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
