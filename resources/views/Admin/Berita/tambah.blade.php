


<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <form class="modal-dialog-scrollable" action="{{url('admin/spk/proses_tambah')}}" method="POST">
        @csrf
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content ">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah SPK</h5><button aria-label="Close" onclick="reset()" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="overflow: auto;">


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="spk_kode" class="form-label">No. SPK<span class="text-danger">*</span></label>
                                <input type="text" name="spk_kode" class="form-control" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="spk_jenis" class="form-label">Jenis SPK<span class="text-danger">*</span></label>
                                <input type="text" name="spk_jenis" class="form-control" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="spk_rekening" class="form-label">No. Rekening<span class="text-danger">*</span></label>
                                <input type="text" name="spk_rekening" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="spk_tanggal" class="form-label">Tanggal Pengajuan<span class="text-danger">*</span></label>
                                <input type="text" name="spk_tanggal" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="spk_pihak_1" class="form-label">Pihak <b>PERTAMA</b> <span class="text-danger">*</span></label>
                                <select name="spk_pihak_1" class="select select-2 form-control">
                                    <option value="">-- Pihak <b>PERTAMA</b> --</option>
                                    @foreach ($pegawai as $s)
                                    <option value="{{ $s->pegawai_id }}">{{ $s->nip }} - {{ $s->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="spk_pihak_2" class="form-label">Pihak <b>KEDUA</b><span class="text-danger">*</span></label>
                                <select name="spk_pihak_2" class="select select-2 form-control">
                                    <option value="">-- Pihak <b>KEDUA</b>--</option>
                                    @foreach ($supplier as $s)
                                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="spk_mengetahui" class="form-label">Pihak <b>Mengetahui</b><span class="text-danger">*</span></label>
                                <select name="spk_mengetahui" class="select select-2 form-control">
                                    <option value="">-- Pihak <b>KEDUA</b>--</option>
                                    @foreach ($pegawai as $s)
                                    <option value="{{ $s->pegawai_id }}">{{ $s->nip }} - {{ $s->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6> <b>Detail Pekerjaan</b></h6>

                    <table class="table table-bordered responsive" id="user_table" width="100%" >
                        <thead>
                            <tr>
                                <th width="35%">Jenis Pekerjaan</th>
                                <th>Detail Pekerjaan</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="jenis_pekerjaan[]" placeholder="Input Jenis Pekerjaan" id="jenis_pekerjaan" class="form-control" required /></td>
                                <td><textarea type="text" name="detail_pekerjaan[]" id="detail_pekerjaan" class="form-control" placeholder="Input Detail Pekerjaan" required></textarea></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><button type="button" name="add" class="btn btn-success btn-sm add"><i class="fa fa-plus"></i> Tambah </button> </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                <div class="modal-footer pull-right">
                    <button type="submit" class="btn btn-primary">Simpan <i class="fe fe-check"></i></button>
                    <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
                </div>


            </div>
        </div>
    </form>
</div>


@section('formTambahJS')
<script>

    function checkForm() {
        const status = $("#status").val();
        const pb_kode = $("input[name='pb_kode']").val();
        const pb_tanggal = $("input[name='pb_tanggal']").val();
        const pb_keterangan = $("input[name='pb_keterangan']").val();

        setLoading(true);
        resetValid();

        if (pb_tanggal == "") {
            validasi('Tanggal Keluar wajib di isi!', 'warning');
            $("input[name='pb_tanggal']").addClass('is-invalid');
            setLoading(false);
            return false;
        }else {
            submitForm();
        }

    }

    function submitForm() {
        var pb_kode       = $("input[name='pb_kode']").val();
        var pb_tanggal    = $("input[name='pb_tanggal']").val();
        var pb_keterangan    = $("input[name='pb_keterangan']").val();
        var pb_supplier   = $("input[name='pb_supplier']").val();

        var barang_kode = $('input[name="barang_kode[]"').map(function(){
                    return this.value;
                }).get();

        var jml = $("input[name='jml']").val();
        var harga = $("input[name='harga']").val();
        var barang_id       = $("input[name='barang_id']").val();


        $.ajax({
            type: 'POST',
            url: "{{ route('pb.store') }}",
            enctype: 'multipart/form-data',
            data: {
                pb_kode: pb_kode,
                pb_keterangan : pb_keterangan,
                barang_id: barang_id,
                pb_tanggal: pb_tanggal,
                pb_supplier: pb_supplier,
                barang_kode: barang_kode,
                jml: jml,
                harga: harga,
            },
            success: function(data) {
                $('#modaldemo8').modal('toggle');
                swal({
                    title: "Berhasil ditambah!",
                    type: "success"
                });
                table.ajax.reload(null, false);
                reset();

            }
        });
    }

    function resetValid() {
        $("input[name='tglkeluar']").removeClass('is-invalid');
        $("input[name='kdbarang']").removeClass('is-invalid');
        $("input[name='tujuan']").removeClass('is-invalid');
        $("input[name='jml']").removeClass('is-invalid');
        $("input[name='harga']").removeClass('is-invalid');
    };

    function reset() {
        resetValid();
        $("input[name='pb_kode']").val('');
        $("input[name='tglkeluar']").val('');
        $("input[name='kdbarang']").val('');
        $("input[name='tujuan']").val('');
        $("input[name='jml']").val('0');
        $("input[name='harga']").val('');
        $("#nmbarang").val('');
        $("#satuan").val('');
        $("#jenis").val('');
        $("#harga").val('');
        $("#status").val('false');
        setLoading(false);
    }

    function setLoading(bool) {
        if (bool == true) {
            $('#btnLoader').removeClass('d-none');
            $('#btnSimpan').addClass('d-none');
        } else {
            $('#btnSimpan').removeClass('d-none');
            $('#btnLoader').addClass('d-none');
        }
    }


    var count = 1;

    function add_input_field(count) {

        var html = '';

        if (count > 1) {
            html += '<tr>';

            html += '<td> <input type="text" name="jenis_pekerjaan[]" id="jenis_pekerjaan" class="form-control" required/></td>';

            html += '<td><textarea type="text" name="detail_pekerjaan[]" id="detail_pekerjaan" class="form-control" required></textarea></td>';

        }

        var remove_button = '';

        if (count > 1) {

            remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fa fa-trash"></i> Batal</button>';

        } else {

            remove_button = '';
        }

        html += '<td>' + remove_button + '</td></tr>';

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