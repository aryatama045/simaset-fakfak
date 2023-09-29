@extends('Master.Layouts.app', ['title' => $title])

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Supplier </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-gray">Data Pengguna </li>
                <li class="breadcrumb-item active" aria-current="page">Supplier </li>
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
                    @if ($hakTambah > 0)
                        <div>
                            <a class="modal-effect btn btn-primary-light" data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal" href="#modaldemo8">Tambah Data
                                <i class="fe fe-plus"></i></a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-1" width="100%"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <th class="border-bottom-0" width="1%">No</th>
                                <th class="border-bottom-0">Perusahaan</th>
                                <th class="border-bottom-0">Nama Lengkap</th>
                                <th class="border-bottom-0">Keterangan</th>
                                <th class="border-bottom-0" width="1%">Action</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->

    @include('Admin.Supplier.tambah')
    @include('Admin.Supplier.edit')
    @include('Admin.Supplier.hapus')

    <script>
        function update(data) {
            $("input[name='idsupplierU']").val(data.supplier_id);
            $("input[name='supplierU']").val(data.supplier_nama.replace(/_/g, ' '));
            $("input[name='namaU']").val(data.nama_lengkap.replace(/_/g, ' '));
            $("input[name='jabatanU']").val(data.jabatan.replace(/_/g, ' '));
            $("input[name='no_telpU']").val(data.no_telp.replace(/_/g, ' '));
            $("input[name='alamatU']").val(data.alamat.replace(/_/g, ' '));
            $("textarea[name='ketU']").val(data.supplier_keterangan.replace(/_/g, ' '));
        }

        function hapus(data) {
            $("input[name='idsupplier']").val(data.supplier_id);
            $("#vsupplier").html("supplier " + "<b>" + data.supplier_nama.replace(/_/g, ' ') + "</b>");
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
                "stateSave": true,
                "lengthMenu": [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                "pageLength": 10,

                lengthChange: true,

                "ajax": {
                    "url": "{{ route('supplier.getsupplier') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'supplier_nama',
                        name: 'supplier_nama',
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap',
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

            });
        });
    </script>
@endsection
