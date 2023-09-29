@extends('Master.Layouts.app', ['title' => $title])
@section('content')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Tambah <br>
            <small>Pengadaan Barang</small></h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-gray">Pengajuan</li>
                <li class="breadcrumb-item text-gray">Pengadaan Barang</li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

@include('Admin.PengadaanBarang.barang')

@endsection