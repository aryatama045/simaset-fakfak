@extends('Master.Layouts.app', ['title' => $title])
@section('content')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Tambah Barang Masuk<br><br>
            <small>No. Pengadaan Barang : {{ $header[0]->pb_kode }}</small></h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-gray">Barang Masuk</li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
<!-- ROW -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Form Barang Masuk</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bmkode" class="form-label">Kode Barang Masuk <span class="text-danger">*</span></label>
                            <input type="text" name="bmkode" readonly class="form-control" value="{{ $bmkode }}">
                            <input type="text">
                        </div>
                        <div class="form-group">
                            <label for="pb_kode" class="form-label">Kode Pengadaan <span class="text-danger">*</span></label>
                            <input type="text" name="pb_kode" readonly class="form-control" value="{{ $header[0]->pb_kode }}">
                            <input type="text" name="pb_id" hidden value="{{ $header[0]->pb_id }}">
                        </div>
                        <div class="form-group">
                            <label for="tglmasuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="text" name="tglmasuk" class="form-control datepicker-date" value="">
                        </div>
                    </div>

                    <div class="col-md-6">

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END ROW -->
@endsection
