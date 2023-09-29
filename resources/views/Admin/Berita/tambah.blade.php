


<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <form class="modal-dialog-scrollable" action="{{url('admin/berita/proses_tambah')}}" method="POST">
    @csrf
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content ">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengadaan Barang</h5>
            </div>
            <div class="modal-body" style="overflow: auto;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="berita_kode" class="form-label">No. Berita Acara<span class="text-danger">*</span></label>
                            <input type="text" name="berita_kode" class="form-control" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label for="berita_pihak_1" class="form-label">Pihak <b>PERTAMA</b> <span class="text-danger">*</span></label>
                            <select name="berita_pihak_1" class="select select-2 form-control">
                                <option value="">-- Pihak <b>PERTAMA</b> --</option>
                                @foreach ($pegawai as $s)
                                <option value="{{ $s->pegawai_id }}">{{ $s->nip }} - {{ $s->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="berita_tanggal" class="form-label">Tanggal Pengajuan<span class="text-danger">*</span></label>
                            <input type="text" name="berita_tanggal" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="berita_pihak_2" class="form-label">Pihak <b>KEDUA</b><span class="text-danger">*</span></label>
                            <select name="berita_pihak_2" class="select select-2 form-control">
                                <option value="">-- Pihak <b>KEDUA</b>--</option>
                                @foreach ($pegawai as $s)
                                <option value="{{ $s->pegawai_id }}">{{ $s->nip }} - {{ $s->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <br><br>

                <table class="table table-bordered responsive" id="user_table" width="100%" >
                    <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th width="20%">Nama Barang</th>
                        <th>Satuan</th>
                        <th>Spek</th>
                        <th>Jumlah</th>
                        <th width="20%">Harga</th>
                        <th><button type="button" class="btn btn-default" onclick="modalBarang()"><i class="fa fa-plus"></i></button></th>
                    </tr>
                    </thead>


                </table>
                <br>
                <button type="button" name="add" class="btn btn-success btn-sm add"><i class="fa fa-plus"></i> Tambah Manual</button>
            </div>


            <div class="modal-footer pull-right">
                <button type="submit" class="btn btn-primary">Simpan <i class="fe fe-check"></i></button>
                <a href="{{url('admin/pb')}}" class="btn btn-light" >Batal <i class="fe fe-x"></i></a>
            </div>



        </div>
    </div>
    </form>
</div>


@section('formTambahJS')
<script>
    $(document).ready(function() {
        tableListPO = $('#user_table').DataTable({
            'ordering'    : false,
            'bPaginate'   : false,
            'bFilter'     : false,
            'bInfo'       : false,
            'fixedColumns': true,
            'responsive': true,
            'columnDefs'  : [
                { 'width': 10, 'targets': 0 },
                { 'width': 125, 'targets': 1 },
                { 'width': 250, 'targets': 2 },
                { 'width': 150, 'targets': 3 },
                { 'width': 150, 'targets': 4 },
                { 'width': 150, 'targets': 5 },
                { 'width': 50, 'targets': 6 },
                ],
        });
    });


    function deleteRow(barang_kode){
        tableListPO.row('#'+barang_kode).remove().draw();
        var data = tableListPO.rows().data();
        var new_no_urut =1 ;
        for (let index = 0; index < data.length; index++) {
            var append_1 = new_no_urut;
            tableListPO.cell({row:index, column:0}).data(append_1);
            new_no_urut++;
        }
    }

    $('input[name="kdbarang"]').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            getbarangbyid($('input[name="kdbarang"]').val());
        }
    });

    function modalBarang() {
        $('#modalBarang').modal('show');
        $('#modaldemo8').addClass('d-none');
        $('input[name="param"]').val('tambah');
        resetValid();
        table2.ajax.reload();
    }

    function searchBarang() {
        getbarangbyid($('input[name="kdbarang"]').val());
        resetValid();
    }

    function getbarangbyid(id) {
        $("#loaderkd").removeClass('d-none');
        $.ajax({
            type: 'GET',
            url: "{{ url('admin/barang/getbarang') }}/" + id,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                if (data.length > 0) {
                    $("#loaderkd").addClass('d-none');
                    $("#status").val("true");
                    $("#nmbarang").val(data[0].barang_nama);
                    $("#satuan").val(data[0].satuan_nama);
                    $("#jenis").val(data[0].jenisbarang_nama);
                    $("#harga").val(data[0].barang_harga);
                } else {
                    $("#loaderkd").addClass('d-none');
                    $("#status").val("false");
                    $("#nmbarang").val('');
                    $("#satuan").val('');
                    $("#jenis").val('');
                    $("#harga").val('');
                }
            }
        });
    }


    var count = 1;

    function add_input_field(count) {

        var html = '';

        if (count > 1) {
            html += '<tr>';

            html += '<td><input type="text" name="jenis_pekerjaan[]" id="jenis_pekerjaan" class="form-control" required/></td>';

            html += '<td><input type="text" name="jenis_pekerjaan[]" id="jenis_pekerjaan" class="form-control" required/></td>';

            html += '<td><input type="text" name="jenis_pekerjaan[]" id="jenis_pekerjaan" class="form-control" required/></td>';

            html += '<td><input type="text" name="jenis_pekerjaan[]" id="jenis_pekerjaan" class="form-control" required/></td>';

            html += '<td><input type="text" name="jenis_pekerjaan[]" id="jenis_pekerjaan" class="form-control" required/></td>';

            html += '<td><textarea type="text" name="detail_pekerjaan[]" id="detail_pekerjaan" class="form-control" required></textarea></td>';

            html += '<td><button type="button" name="remove" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>';

            html += '</tr>';
        }


        return html;

    }

    $('#user_table').append(add_input_field(1));


    $(document).on('click', '.add', function() {

        count++;
        $('#user_table').append(add_input_field(count));


    });

    $(document).on('click', '.remove', function() {

        $(this).closest('tr').remove();

    });


</script>
@endsection
