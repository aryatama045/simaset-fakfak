<!-- MODAL BARANG -->
<div class="modal fade" data-bs-backdrop="static" style="overflow-y:scroll;" id="modalBarang">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Pilih Faktur Pengadaan</h6><button onclick="resetB('tambah')" aria-label="Close" class="btn-close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body p-4 pb-5">
                <input type="hidden" value="tambah" name="param">
                <input type="hidden" id="randkey">
                <div class="table-responsive">
                    <table id="table-2" width="100%" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
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

@section('formOtherJS')
<script>
    document.getElementById('randkey').value = makeid(10);

    function resetB() {
        param = $('input[name="param"]').val();
        if (param == 'tambah') {
            $('#modalBarang').modal('hide');
            $('#modaldemo8').removeClass('d-none');
        } else {
            $('#modalBarang').modal('hide');
            $('#Umodaldemo8').removeClass('d-none');
        }

    }

    function pilihBarang(data) {
        const key = $("#randkey").val();
        $("#status").val("true");
        $("input[name='kdbarang']").val(data.barang_kode);
        $("#nmbarang").val(data.barang_nama.replace(/_/g, ' '));
        $("#satuan").val(data.satuan_nama.replace(/_/g, ' '));
        $("#jenis").val(data.jenisbarang_nama.replace(/_/g, ' '));
        $('#modaldemo8').removeClass('d-none');
        $('#modalBarang').modal('hide');
    }

    function pilihBarangU(data) {
        const key = $("#randkey").val();
        $("#statusU").val("true");
        $("input[name='kdbarangU']").val(data.barang_kode);
        $("#nmbarangU").val(data.barang_nama.replace(/_/g, ' '));
        $("#satuanU").val(data.satuan_nama.replace(/_/g, ' '));
        $("#jenisU").val(data.jenisbarang_nama.replace(/_/g, ' '));
        $('#Umodaldemo8').removeClass('d-none');
        $('#modalBarang').modal('hide');
    }

    var table2, barang_table;
    $(document).ready(function() {
        //datatables
        table2 = $('#table-2').DataTable({

            "processing": true,
            "serverSide": true,
            "info": false,
            "order": [],
            "ordering": false,
            "scrollX": false,
            // "lengthMenu": [
            //     [5, 10, 25, 50, 100],
            //     [5, 10, 25, 50, 100]
            // ],
            "pageLength": 10,

            "lengthChange": true,

            "ajax": {
                "url": "{{url('admin/barang-masuk/listpengadaan')}}/param",
                "data": function(d) {
                    d.param = $('input[name="param"]').val();
                }
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

        /* Event On tr Table Browse BKB */
        // $('#table-2 tbody').on( 'click', 'tr', function () {
        //     var rowData = table-2.row(this).data();
        //     copyDocument(rowData[1]);
        // });
        /* End Event On tr Table Browse BKB */
    });


    /* Get Detail BKB untuk copy document Menggunakan Ajax */
    function copyDocument(no_doc_trans){
        $.ajax({
        url: "{{url('admin/barang-masuk/copydocument')}}",
        type: 'post',
        data : {
            no_doc_trans: no_doc_trans,
        },
        dataType: 'json',
        success:function(response) {
            
            if(response.success){


                /* Set Data Detail */
                    barang_table.clear().draw();
                    $.each(response.detail, function(index, value) {
                        var result = Object.keys(value).map(function(key) {
                        return [ value[key]];
                        });
                        barang_table.row.add(result).draw( false );
                    });
                /* End Set Data Detail */



                $('#modalBarang').modal('hide');
                $("input[type=text]").attr("autocomplete", "off");
            }else{
                dialog_warning('Notification',response.messages);
            }

        }
        });
    }
    /* End Get Detail BKB untuk copy document Menggunakan Ajax */


    
    function makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
</script>
@endsection