@extends('Master.Layouts.app', ['title' => $title])


@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">{{$title}} History</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-gray">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}} History</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

@if(session()->has('create_message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <h5 class="alert-heading">Success !!</h5>
    {{ session()->get('create_message') }}
    <!-- <a class="alert-link" href="#!">Example alert link!</a> -->
    <button class="btn-close close" type="button" data-bs-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session()->has('error_message'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">Error !!</h5>
        {{ session()->get('error_message') }}
        <!-- <a class="alert-link" href="#!">Example alert link!</a> -->
        <button class="btn-close close" type="button" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif



<!-- ROW -->
<div class="row row-sm">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header justify-content-between">
                <ul class="nav " role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " href="{{url('admin/barang')}}" >Data Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{url('admin/datahistory')}}" >History</a>
                    </li>
                </ul>


                <!-- <h3 class="card-title">Data</h3> -->
                @if($hakTambah > 0)
                <div>
                    <a class="btn btn-default-light" href="barang/export_barang/xlsx" target="_blank" >Export <i class="fe fe-download"></i></a>
                    <a class="modal-effect btn btn-success-light" onclick="generateID()" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modalimport">Import <i class="fe fe-upload"></i></a>
                    <a class="modal-effect btn btn-primary-light" onclick="generateID()" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modaldemo8">Tambah Data <i class="fe fe-plus"></i></a>
                </div>
                @endif
            </div>
            <div class="card-body">
                @if($hakDelete > 0)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" id="dropdownNoAnimation" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Bulk Action</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownNoAnimation">
                        <a class="dropdown-item" href="#!" name="bulk_delete" id="bulk_delete">Bulk Delete</a>
                        <!-- <a class="dropdown-item" href="#!">Something else here</a> -->
                    </div>
                </div>
                @endif
                <br>

                <table id="table-history" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                    <thead>
                        <th class="border-bottom-0">Kode Barang</th>
                        <th class="border-bottom-0">Nama Barang</th>
                        <th class="border-bottom-0">Ket. Hapus</th>
                        <th class="border-bottom-0">User By</th>
                        <th class="border-bottom-0">Tanggal</th>
                    </thead>
                    <tbody></tbody>
                </table>





            </div>
        </div>
    </div>
</div>
<!-- END ROW -->

@include('Admin.Barang.tambah', ['jenisbarang' => $jenisbarang, 'satuan' => $satuan, 'merk' => $merk])
@include('Admin.Barang.edit', ['jenisbarang' => $jenisbarang, 'satuan' => $satuan, 'merk' => $merk])
@include('Admin.Barang.hapus')
@include('Admin.Barang.gambar')
@include('Admin.Barang.import')


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var table;
    $(document).ready(function() {
        //datatables
        table = $('#table-history').DataTable({
            "processing": true,
            "serverSide": true,
            "info": true,
            "order": [],
            "stateSave":true,
            "scrollX": true,
            "lengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "pageLength": 10,
            lengthChange: true,
            "ajax": {
                "url": "{{route('barang.getbarang')}}",
            },
            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                    'selectRow': true
                    }
                }
            ],
            'select': {
                'style': 'multi'
            },
            "columns": [
                {
                    data: 'barang_kode',
                    name: 'barang_kode',
                },
                {
                    data: 'barang_nama',
                    name: 'barang_nama',
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                },
                {
                    data: 'user_nmlengkap',
                    name: 'user_nmlengkap',
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                },
            ],
        });
    });


</script>
@endsection
