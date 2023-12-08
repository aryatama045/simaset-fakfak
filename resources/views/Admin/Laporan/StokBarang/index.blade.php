@extends('Master.Layouts.app', ['title' => $title])

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Laporan Stok Barang</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-gray">Laporan</li>
            <li class="breadcrumb-item active" aria-current="page">Stok Barang</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Data</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="" class="fw-bold">Filter Tanggal</label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-inline ">
                            <div class="form-group mb-2">
                                <label for="tglawal" class="sr-only">Tanggal Awal</label>
                                <input type="text" name="tglawal" class="form-control datepicker-date" placeholder="Tanggal Awal">
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="tglakhir" class="sr-only">Tanggal Akhir</label>
                                <input type="text" name="tglakhir" class="form-control datepicker-date" placeholder="Tanggal Akhir">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success-light" onclick="filter()"><i class="fe fe-filter"></i> Filter</button>
                            <button class="btn btn-secondary-light" onclick="reset()"><i class="fe fe-refresh-ccw"></i> Reset</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <a class="modal-effect btn btn-default-light"  data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modalexport">Export <i class="fe fe-download"></i></a>
                            <!-- <button class="btn btn-primary-light" onclick="print()"><i class="fe fe-printer"></i> Print</button>
                            <button class="btn btn-danger-light" onclick="pdf()"><i class="fa fa-file-pdf-o"></i> PDF</button> -->
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-1" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <thead>
                            <th class="border-bottom-0" width="1%">No</th>
                            <th class="border-bottom-0">Kode Barang</th>
                            <th class="border-bottom-0">Barang</th>
                            <th class="border-bottom-0">Stok Awal</th>
                            <th class="border-bottom-0">Jumlah Masuk</th>
                            <th class="border-bottom-0">Jumlah Keluar</th>
                            <th class="border-bottom-0">Total Stok</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END ROW -->

    <!-- MODAL Export -->
    <div class="modal fade" data-bs-backdrop="static" id="modalexport">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">

                <div class="modal-header">
                    <h6 class="modal-title">Export Data</h6><button onclick="reset()" aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <!-- <h5> Filter Data by</h5> -->
                    <form class="modal-dialog-scrollable" enctype='multipart/form-data' action="{{url('/admin/lapstokbarang/export')}}" method="POST">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-group">
                            <a class="btn btn-danger" onclick="print()"><i class="fa fa-file-pdf-o"></i> PDF</a>
                        </div>

                        <div class="form-group">
                            <input name="type" type="hidden" value="xlsx" >
                            <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Excel</button>
                            </form>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>

                    <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">Keluar <i class="fe fe-x"></i></a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        getData();
    });

    function getData() {
        //datatables
        table = $('#table-1').DataTable({

            "processing": true,
            "serverSide": true,
            "info": true,
            "order": [],
            "scrollX": true,
            "stateSave": true,
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'Semua']
            ],
            "pageLength": 10,

            lengthChange: true,

            "ajax": {
                "url": "{{ route('lap-sb.getlap-sb') }}",
                "data": function(d) {
                    d.tglawal = $('input[name="tglawal"]').val();
                    d.tglakhir = $('input[name="tglakhir"]').val();
                }
            },

            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'barang_kode',
                    name: 'barang_kode',
                },
                {
                    data: 'barang_nama',
                    name: 'barang_nama',
                },
                {
                    data: 'stokawal',
                    name: 'barang_stok',
                },
                {
                    data: 'jmlmasuk',
                    name: 'barang_kode',
                    orderable: false,
                },
                {
                    data: 'jmlkeluar',
                    name: 'barang_kode',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'totalstok',
                    name: 'barang_kode',
                    searchable: false,
                    orderable: false,
                },
            ],

        });
    }

    function filter() {
        var tglawal = $('input[name="tglawal"]').val();
        var tglakhir = $('input[name="tglakhir"]').val();
        if (tglawal != '' && tglakhir != '') {
            table.ajax.reload(null, false);
        } else {
            validasi("Isi dulu Form Filter Tanggal!", 'warning');
        }

    }

    function reset() {
        $('input[name="tglawal"]').val('');
        $('input[name="tglakhir"]').val('');
        table.ajax.reload(null, false);
    }

    function print() {
        var tglawal = $('input[name="tglawal"]').val();
        var tglakhir = $('input[name="tglakhir"]').val();
        window.open(
            "{{route('lap-sb.print')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
            '_blank'
        );

    }

    function pdf() {
        var tglawal = $('input[name="tglawal"]').val();
        var tglakhir = $('input[name="tglakhir"]').val();
        window.open(
            "{{route('lap-sb.pdf')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
            '_blank'
        );

    }

    function validasi(judul, status) {
        swal({
            title: judul,
            type: status,
            confirmButtonText: "Iya."
        });
    }
</script>
@endsection
