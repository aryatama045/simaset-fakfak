<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Supplier</h6><button aria-label="Close" class="btn-close"
                    data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="supplier" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="supplier" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="jabatan" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="no_telp" class="form-label">Kontak (No. Telp) <span class="text-danger">*</span></label>
                    <input type="text" name="no_telp" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <input type="text" name="alamat" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="ket" class="form-label">Keterangan</label>
                    <textarea name="ket" class="form-control" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <a href="javascript:void(0)" onclick="checkForm()" id="btnSimpan" class="btn btn-primary">Simpan <i
                        class="fe fe-check"></i></a>
                <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">Batal <i
                        class="fe fe-x"></i></a>
            </div>
        </div>
    </div>
</div>


@section('formTambahJS')
    <script>
        function checkForm() {
            const supplier = $("input[name='supplier']").val();
            setLoading(true);
            resetValid();

            if (supplier == "") {
                validasi('Nama Supplier wajib di isi!', 'warning');
                $("input[name='supplier']").addClass('is-invalid');
                setLoading(false);
                return false;
            } else {
                submitForm();
            }

        }

        function submitForm() {
            const supplier = $("input[name='supplier']").val();
            const ket = $("textarea[name='ket']").val();
            const nama_lengkap = $("input[name='nama_lengkap']").val();
            const jabatan = $("input[name='jabatan']").val();
            const no_telp = $("input[name='no_telp']").val();
            const alamat = $("input[name='alamat']").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('supplier.store') }}",
                enctype: 'multipart/form-data',
                data: {
                    supplier: supplier,
                    nama_lengkap: nama_lengkap,
                    jabatan: jabatan,
                    no_telp: no_telp,
                    alamat: alamat,
                    ket: ket
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
            $("input[name='supplier']").removeClass('is-invalid');
        };

        function reset() {
            resetValid();
            $("input[name='supplier']").val('');
            $("input[name='nama_lengkap']").val('');
            $("textarea[name='ket']").val('');
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
    </script>
@endsection
