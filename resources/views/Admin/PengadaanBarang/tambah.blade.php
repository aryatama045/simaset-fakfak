


<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <form class="modal-dialog-scrollable" action="{{url('admin/pb/proses_tambah')}}" method="POST">
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
                            <label for="pb_kode" class="form-label">Nomor Pesanan<span class="text-danger">*</span></label>
                            <input type="text" name="pb_kode" class="form-control" value="{{ $no_pesanan }}/{{date('m')}}/NP/BPKAD/{{date('Y')}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="pb_dibuat" class="form-label">Di Buat<span class="text-danger">*</span></label>
                            <input type="text" name="pb_dibuat" class="form-control" value="FakFak">
                        </div>
                        <div class="form-group">
                            <label for="pb_keterangan" class="form-label">Body Surat<span class="text-danger">*</span></label>
                            <textarea type="text" name="pb_keterangan" class="form-control" placeholder="" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="pb_footer" class="form-label">Footer Surat<span class="text-danger">*</span></label>
                            <textarea type="text" name="pb_footer" class="form-control" >Demikian Nota Pesanan dan atas perhatiannya kami sampaikan terimakasih.</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pb_tanggal" class="form-label">Tanggal Pengajuan<span class="text-danger">*</span></label>
                            <input type="text" name="pb_tanggal" class="form-control datepicker-date" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="spk_kode" class="form-label">Pilih Dokumen SPK <span class="text-danger">*</span></label>
                            <select name="spk_kode" class="select select-2 form-control" required>
                                <option value="">-- Pilih Dokumen SPK --</option>
                                @foreach ($spk as $s)
                                <option value="{{ $s->spk_kode }}">{{ $s->spk_kode }} - {{ $s->spk_jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="pb_supplier" class="form-label">Pilih Supplier <span class="text-danger">*</span></label>
                            <select name="pb_supplier" class="select select-2 form-control" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($supplier as $s)
                                <option value="{{ $s->supplier_id }}">{{ $s->supplier_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="pb_pejabat" class="form-label">Pilih Pejabat Pembuat Komitmen <span class="text-danger">*</span></label>
                            <select name="pb_pejabat" class="select select-2 form-control" required>
                                <option value="">-- Pilih Pejabat Mengetahui--</option>
                                @foreach ($pegawai as $s)
                                <option value="{{ $s->pegawai_id }}">{{ $s->nip }} - {{ $s->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div><br><br>

                <table class="table table-bordered responsive" id="user_table" width="100%" >
                    <thead>
                    <tr>
                        <th>No. </th>
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
                { 'width': 350, 'targets': 6 },
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


</script>
@endsection
