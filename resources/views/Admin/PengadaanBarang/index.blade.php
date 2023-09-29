@extends('Master.Layouts.app', ['title' => $title])

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Pengadaan Barang</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-gray">Pengajuan</li>
                <li class="breadcrumb-item active" aria-current="page">Pengadaan Barang</li>
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

                            <!-- <a class="btn btn-primary-light" href="{{route('pb.add')}}">Tambah Data
                                <i class="fe fe-plus"></i></a> -->
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-1" width="100%"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <th class="border-bottom-0" width="1%">No</th>
                                <th class="border-bottom-0">No. Pesanan</th>
                                <th class="border-bottom-0">Supplier</th>
                                <th class="border-bottom-0">Mengetahui</th>
                                <th class="border-bottom-0">Tgl. Pengajuan</th>
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

    @include('Admin.PengadaanBarang.tambah')
    @include('Admin.PengadaanBarang.edit')
    @include('Admin.PengadaanBarang.hapus')
    @include('Admin.PengadaanBarang.barang')

    <script>
        function update(data) {
            $("input[name='idpbU']").val(data.pb_id);
            $("input[name='pbU']").val(data.pb_kode.replace(/_/g, ' '));
            $("textarea[name='ketU']").val(data.pb_keterangan.replace(/_/g, ' '));
        }

        function hapus(data) {
            $("input[name='idpb']").val(data.pb_id);
            $("#vpb").html("pb " + "<b>" + data.pb_kode.replace(/_/g, ' ') + "</b>");
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
                    "url": "{{ route('pb.getpb') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'pb_kode',
                        name: 'pb_kode',
                    },
                    {
                        data: 'supplier',
                        name: 'supplier',
                    },
                    {
                        data: 'pejabat',
                        name: 'pb_pejabat',
                    },
                    {
                        data: 'pb_tanggal',
                        name: 'pb_tanggal',
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
