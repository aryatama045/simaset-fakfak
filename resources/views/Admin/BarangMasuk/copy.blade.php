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
                        </div>
                        <div class="form-group">
                            <label for="tglmasuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="text" name="tglmasuk" class="form-control datepicker-date" value="">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <input type="text" readonly class="form-control" placeholder="{{ $header[0]->supplier_nama }}">
                        </div>
                        <div class="form-group">
                            <label  class="form-label">Tanggal Pengadaan <span class="text-danger">*</span></label>
                            <input type="text" readonly class="form-control" placeholder="{{ date('d-M-Y', strtotime($header[0]->pb_tanggal)) }}">
                        </div>

                    </div>
                </div>

                <div class="border-bottom"></div>
                <br><br>

                <table class="table table-bordered responsive" id="barang_table" width="100%" >
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th width="20%">Nama Barang</th>
                            <th>Satuan</th>
                            <th>Spek</th>
                            <th width="20%">Jumlah</th>
                            <th >Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail as $k => $v)
                        <tr>
                            <td>{{ $v->barang_kode }}</td>
                            <td></td>
                            <td>{{ $v->satuan }}</td>
                            <td>{{ $v->spek }}</td>
                            <td><input type="text" name="jumlah_masuk" class="form-control" value="{{ $v->pb_jumlah }}"></td>
                            <td>{{ $v->pb_harga }}</td>
                        </tr>
                        @endforeach
                    </tbody>


                </table>

            </div>
        </div>
    </div>
</div>
<!-- END ROW -->
@endsection
