@extends('Master.Layouts.app', ['title' => $title])


@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">{{$title}}</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-gray">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
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
                <!-- <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Data Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">History</a>
                    </li>
                </ul> -->

                <nav class="nav">
                    <a class="nav-link active disabled" href="#">Data Barang</a>
                    <a class="nav-link" href="#">History</a>
                </nav>


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


                <div class="table-responsive">
                    <table id="table-1" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <thead>
                            @if($hakEdit > 0)
                            <th class="border-bottom-0" width="1%">
                                <div class="form-check">
                                    <input type="checkbox" name="select_all" value="1" class="form-check-input" id="example-select-all">
                                </div>
                            </th>
                            <th class="border-bottom-0" width="1%">Action</th>
                            @endif

                            <th class="border-bottom-0">Gambar</th>
                            <th class="border-bottom-0">Kode Barang</th>
                            <th class="border-bottom-0">Nama Barang</th>
                            <th class="border-bottom-0">Jenis</th>
                            <th class="border-bottom-0">Kategori</th>
                            <th class="border-bottom-0">Satuan</th>
                            <th class="border-bottom-0">Merk</th>
                            <th class="border-bottom-0">Stok</th>
                            <th class="border-bottom-0">Harga</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>


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

<div id="studentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="student_form">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Data</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Enter First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Enter Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="student_id" id="student_id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function generateID(){
        id = new Date().getTime();
        $("input[name='kode']").val("BRG-"+id);
    }
    function update(data){
        $("input[name='idbarangU']").val(data.barang_id);
        $("input[name='kodeU']").val(data.barang_kode);
        $("input[name='namaU']").val(data.barang_nama.replace(/_/g, ' '));
        $("select[name='jenisbarangU']").val(data.jenisbarang_id);
        $("select[name='satuanU']").val(data.satuan_id);
        $("select[name='merkU']").val(data.merk_id);
        $("input[name='stokU']").val(data.barang_stok);
        $("input[name='hargaU']").val(data.barang_harga.replace(/_/g, ' '));
        if(data.barang_gambar != 'image.png'){
            $("#outputImgU").attr("src", "{{url('/uploads/image')}}"+"/"+data.barang_gambar);
        }
    }
    function hapus(data) {
        $("input[name='idbarang']").val(data.barang_id);
        $("#vbarang").html("barang " + "<b>" + data.barang_nama.replace(/_/g, ' ') + "</b>");
    }
    function gambar(data) {
        if(data.barang_gambar != 'image.png'){
            $("#outputImgG").attr("src", "{{url('/uploads/image')}}"+"/"+data.barang_gambar);
        }else{
            $("#outputImgG").attr("src", "{{url('/assets/default/barang/image.png')}}");
        }
    }
    function validasi(judul, status) {
        swal({
            title: judul,
            type: status,
            confirmButtonText: "Iya"
        });
    }
</script>
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
        table = $('#table-1').DataTable({
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
                // {
                //     data: 'DT_RowIndex',
                //     name: 'DT_RowIndex',
                //     searchable: false
                // },
                {
                    data: 'checkbox',
                    orderable: false,
                    searchable:false,
                    name:'id'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'img',
                    name: 'barang_gambar',
                    searchable: false,
                    orderable: false
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
                    data: 'jenisbarang',
                    name: 'jenisbarang_nama',
                },
                {
                    data: 'kategori',
                    name: 'kategori_nama',
                },
                {
                    data: 'satuan',
                    name: 'satuan_nama',
                },
                {
                    data: 'merk',
                    name: 'merk_nama',
                },
                {
                    data: 'totalstok',
                    name: 'barang_stok',
                },
                {
                    data: 'currency',
                    name: 'barang_harga'
                },
            ],
            select: {
                style: 'multi'
            },
        });
    });


    $(document).on('click', '#bulk_delete', function(){
        var id = [];
        if(confirm("Are you sure you want to Delete this data?"))
        {
            $('.barang_checkbox:checked').each(function(){
                id.push($(this).val());
            });
            if(id.length > 0)
            {
                $.ajax({
                    url:"{{ route('barang.bulk_delete')}}",
                    method:"post",
                    data:{id:id},
                    success:function(data)
                    {
                        // alert(data);
                        swal({
                            title: "Berhasil dihapus!" + data,
                            type: "success"
                        });
                        $('#table-1').DataTable().ajax.reload();
                        $('input[type="checkbox"]', rows).prop('checked', this.checked);
                    }
                });
            }
            else
            {
                alert("Please select atleast one checkbox");
            }
        }
    });

    $('#example-select-all').on('click', function(){
        // Get all rows with search applied
        var rows = table.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    // Handle click on checkbox to set state of "Select all" control
    $('#table-1 tbody').on('change', 'input[type="checkbox"]', function(){
        // If checkbox is not checked
        if(!this.checked){
            var el = $('#example-select-all').get(0);
            // If "Select all" control is checked and has 'indeterminate' property
            if(el && el.checked && ('indeterminate' in el)){
                // Set visual state of "Select all" control
                // as 'indeterminate'
                el.indeterminate = true;
            }
        }
    });

</script>
@endsection
