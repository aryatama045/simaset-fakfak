<!-- MODAL Export -->
<div class="modal fade" data-bs-backdrop="static" id="modalexport">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <form class="modal-dialog-scrollable" enctype='multipart/form-data' action="{{url('admin/barang/export_barang')}}" method="POST">
        @csrf
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Export Barang</h6><button onclick="reset()" aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <h5> Filter Data by</h5>
                <input name="type" type="hidden" value="xlsx" >

                <div class="form-group">
                    <label for="jenisbarang" class="form-label">Jenis Barang</label>
                    <select name="jenisbarang" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach ($jenisbarang as $jb)
                            <option value="{{$jb->jenisbarang_id}}">{{$jb->jenisbarang_nama}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="satuan" class="form-label">Satuan Barang</label>
                    <select name="satuan" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach ($satuan as $s)
                        <option value="{{$s->satuan_id}}">{{$s->satuan_nama}}</option>
                        @endforeach
                    </select>
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