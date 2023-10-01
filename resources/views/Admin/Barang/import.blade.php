<!-- MODAL Import -->
<div class="modal fade" data-bs-backdrop="static" id="modalimport">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <form class="modal-dialog-scrollable" enctype='multipart/form-data' action="{{url('admin/barang/import_barang')}}" method="POST">
        @csrf
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Import Barang</h6><button onclick="reset()" aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Import Data *</label>
                        <input name="file" type="file" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> Template File</label>
                        <a href="{{url('/assets/sample_products.csv')}}" target="_blank" class="btn btn-info btn-block btn-md"><i class="fe fe-download"></i>  File Download</a>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <button type="submit" class="btn btn-primary">Simpan <i class="fe fe-check"></i></button>
                
                <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
            </div>


        </div>
        </form>
    </div>
</div>


@section('formImportJS')
<script>
    function setLoading(bool) {
        if (bool == true) {
            $('#btnLoader').removeClass('d-none');
            $('#btnSimpan').addClass('d-none');
        } else {
            $('#btnSimpan').removeClass('d-none');
            $('#btnLoader').addClass('d-none');
        }
    }
    function fileIsValid(fileName) {
        var ext = fileName.match(/\.([^\.]+)$/)[1];
        ext = ext.toLowerCase();
        var isValid = true;
        switch (ext) {
            case 'csv':
            case 'xlsx':
            case 'xls':
                break;
            default:
                this.value = '';
                isValid = false;
        }
        return isValid;
    }
    function VerifyFileNameAndFileSize() {
        var file = document.getElementById('GetFile').files[0];
        if (file != null) {
            var fileName = file.name;
            if (fileIsValid(fileName) == false) {
                validasi('Format bukan excel', 'warning');
                document.getElementById('GetFile').value = null;
                return false;
            }
            var content;
            var size = file.size;
            if ((size != null) && ((size / (1024 * 1024)) > 3)) {
                validasi('Ukuran Maximum 1 MB', 'warning');
                document.getElementById('GetFile').value = null;
                return false;
            }
            var ext = fileName.match(/\.([^\.]+)$/)[1];
            ext = ext.toLowerCase();
            // $(".custom-file-label").addClass("selected").html(file.name);
            document.getElementById('outputImg').src = window.URL.createObjectURL(file);
            return true;
        } else
            return false;
    }
</script>
@endsection