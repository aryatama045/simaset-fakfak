<?php

// Aryatama write

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BeritaModel;
use App\Models\Admin\BeritadetailModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\PegawaiModel;
use App\Models\Admin\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Pdf\Berita;
use DB;

class BeritaController extends Controller
{
    public function index()
    {
        $data["title"] = "Berita Acara";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Berita Acara', 'tbl_akses.akses_type' => 'create'))->count();
        $data["pegawai"] = PegawaiModel::orderBy('pegawai_id', 'DESC')->get();
        return view('Admin.Berita.index', $data);
    }


    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('tbl_berita as h')
                ->leftJoin('tbl_pegawai as p1', 'h.berita_pihak_1', '=', 'p1.pegawai_id')
                ->leftJoin('tbl_pegawai as p2', 'h.berita_pihak_2', '=', 'p2.pegawai_id')
                ->Select('h.*',
                    'p1.nip as p1_nip', 'p1.nama_lengkap as p1_nama', 'p1.jabatan as p1_jabatan','p1.alamat as p1_alamat',
                    'p2.nip as p2_nip', 'p2.nama_lengkap as p2_nama', 'p2.jabatan as p2_jabatan','p2.alamat as p2_alamat')
            ->orderBy('berita_id', 'DESC')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('pihak_1', function ($row) {
                    $pihak_1 = $row->p1_nama == '' ? '-' : $row->p1_nama;

                    // $pihak_1 = substr(strip_tags($pihak_1), 0, 20);

                    return $pihak_1;
                })
                ->addColumn('pihak_2', function ($row) {
                    $pihak_2 = $row->p2_nama == '' ? '-' : $row->p2_nama;

                    // $pihak_2 = substr(strip_tags($pihak_2), 0, 20);

                    return $pihak_2;
                })
                ->addColumn('action', function ($row) {
                    $array = array(
                        "berita_id" => $row->berita_id,
                        "berita_kode" => $row->berita_kode,
                    );
                    $button = '';
                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Berita Acara', 'tbl_akses.akses_type' => 'update'))->count();
                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Berita Acara', 'tbl_akses.akses_type' => 'delete'))->count();
                    if ($hakEdit > 0 && $hakDelete > 0) {
                        $button .= '
                        <div class="g-2">
                        <a class="btn text-success btn-sm" target="_blank" href="berita/genInvoice/'.$row->berita_id.'" > <span class="fe fe-printer text-success fs-14"></span> PDF</a>
                        <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span></a>
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                        ';
                    } else if ($hakEdit > 0 && $hakDelete == 0) {
                        $button .= '
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span></a>
                        </div>
                        ';
                    } else if ($hakEdit == 0 && $hakDelete > 0) {
                        $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                        ';
                    } else {
                        $button .= '-';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'pihak_1'])->make(true);
        }
    }

    public function listbarang(Request $request)
    {
        if ($request->ajax()) {
            $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')->orderBy('barang_id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('img', function ($row) {
                    if ($row->barang_gambar == "image.png") {
                        $img = '<span class="avatar avatar-lg cover-image" style="background: url(&quot;' . url('/assets/default/barang') . '/' . $row->barang_gambar . '&quot;) center center;"></span>';
                    } else {
                        $img = '<span class="avatar avatar-lg cover-image" style="background: url(&quot;' . url('/uploads/image/' . $row->barang_gambar) . '&quot;) center center;"></span>';
                    }

                    return $img;
                })
                ->addColumn('jenisbarang', function ($row) {
                    $jenisbarang = $row->jenisbarang_id == '' ? '-' : $row->jenisbarang_nama;

                    return $jenisbarang;
                })
                ->addColumn('satuan', function ($row) {
                    $satuan = $row->satuan_id == '' ? '-' : $row->satuan_nama;

                    return $satuan;
                })
                ->addColumn('merk', function ($row) {
                    $merk = $row->merk_id == '' ? '-' : $row->merk_nama;

                    return $merk;
                })
                ->addColumn('currency', function ($row) {
                    $currency = $row->barang_harga == '' ? '-' : 'Rp ' . number_format($row->barang_harga, 0);

                    return $currency;
                })
                ->addColumn('totalstok', function ($row) use ($request) {
                    if ($request->tglawal == '') {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                    } else {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                    }


                    if ($request->tglawal) {
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)->sum('tbl_barangkeluar.bk_jumlah');
                    } else {
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)->sum('tbl_barangkeluar.bk_jumlah');
                    }

                    $totalstok = $row->barang_stok + ($jmlmasuk - $jmlkeluar);
                    if($totalstok == 0){
                        $result = '<span class="">'.$totalstok.'</span>';
                    }else if($totalstok > 0){
                        $result = '<span class="text-success">'.$totalstok.'</span>';
                    }else{
                        $result = '<span class="text-danger">'.$totalstok.'</span>';
                    }
                    

                    return $result;
                })
                ->addColumn('action', function ($row) use ($request) {

                    if ($request->tglawal == '') {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                    } else {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                    }


                    if ($request->tglawal) {
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)->sum('tbl_barangkeluar.bk_jumlah');
                    } else {
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)->sum('tbl_barangkeluar.bk_jumlah');
                    }

                    $totalstok = $row->barang_stok + ($jmlmasuk - $jmlkeluar); 
                    
                    $jumlah_harga = $totalstok * $row->barang_harga;

                    $array = array(
                        "barang_kode" => $row->barang_kode,
                        "barang_nama" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->barang_nama)),
                        "stok" => $totalstok,
                        "harga_satuan" => number_format($row->barang_harga, 0),
                        "jumlah_harga" => number_format($jumlah_harga, 0),
                        "barang_id" => $row->barang_id,
                    );
                    $button = '';
                    if ($request->get('param') == 'tambah') {
                        $button .= '
                        <div class="g-2">
                            <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick=pilihBarang(' . json_encode($array) . ')>Pilih</a>
                        </div>
                        ';
                    } else {
                        $button .= '
                    <div class="g-2">
                        <a class="btn btn-success btn-sm" href="javascript:void(0)" onclick=pilihBarangU(' . json_encode($array) . ')>Pilih</a>
                    </div>
                    ';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'img', 'jenisbarang', 'satuan', 'merk', 'currency', 'totalstok'])->make(true);
        }
    }

    public function proses_tambah(Request $request)
    {
        // dd($request);
        
        $header = array(
            'berita_kode'   => $request->berita_kode,
            'berita_pihak_1'   => $request->berita_pihak_1,
            'berita_pihak_2'   => $request->berita_pihak_2,
            'berita_pic'       => '1',
            'berita_tanggal'    => $request->berita_tanggal,

            'berita_header'    => $request->berita_header,
            'berita_body'    => $request->berita_body,
            'berita_footer'    => $request->berita_footer,

        );

        //insert data
        BeritaModel::create($header);

        $berita_data = BeritaModel::orderBy('berita_id', 'DESC')->get();


        $count_d = count($request->harga_satuan);

        $log_detail = array();
        for($x=0; $x < $count_d; $x++) {
            $detail_barang = array(
                'berita_id'     => $berita_data->berita_id,
                'barang_id'     => $request->barang_id[$x],
                'stok'          => $request->stok[$x],
                'harga_satuan'  => $request->harga_satuan[$x],
            );
            array_push($log_detail, $detail_barang);
            BeritadetailModel::create($detail_barang);
        }

        // dd($header, $log_detail);

        return redirect('admin/berita')->with('create_message', 'Berita Acara Nomor : '. $request->berita_kode);
    }

    public function proses_ubah(Request $request, BeritaModel $pb)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->pb)));

        //update data
        $pb->update([
            'pb_nama' => $request->pb,
            'pb_slug' => $slug,
            'pb_keterangan'  => $request->ket,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_hapus(Request $request, BeritaModel $berita, $id)
    {

        BeritaModel::where('berita_id',$id)->delete();
        BeritadetailModel::where('berita_id',$id)->delete();

        return response()->json(['success' => 'Berhasil']);
    }



    public function genInvoice($id)
    {

        // if (!empty($id)){


            $data_header = DB::table('tbl_berita as h')
                ->leftJoin('tbl_pegawai as p1', 'h.berita_pihak_1', '=', 'p1.pegawai_id')
                ->leftJoin('tbl_pegawai as p2', 'h.berita_pihak_2', '=', 'p2.pegawai_id')
                ->where('h.berita_id', $id)
                ->Select('h.*',
                    'p1.nip as p1_nip', 'p1.nama_lengkap as p1_nama', 'p1.jabatan as p1_jabatan','p1.alamat as p1_alamat',
                    'p2.nip as p2_nip', 'p2.nama_lengkap as p2_nama', 'p2.jabatan as p2_jabatan','p2.alamat as p2_alamat')
            ->orderBy('berita_id', 'DESC')->get();


            // if($data_header) {

                $data_detail = DB::table('tbl_beritadetail as bd')
                ->leftjoin('tbl_barang as brg', 'bd.barang_id', '=', 'brg.barang_id')
                ->where('bd.berita_id', $id)
                ->select('bd.stok','brg.barang_nama','brg.barang_harga')
                ->get();

                // $data_detail = '';
                // $data_header= '';

                $general_setting = DB::table('tbl_web')->latest()->first();

                // if($data_detail){
                    $output['general_setting']		= $general_setting;
                    $output['header']		= $data_header;
                    $output['detail']		= $data_detail;
                // }
            // }

            // dd($data_header, $data_detail);



            $myPdf = new Berita($output);

            $myPdf->Output('I', "BeritaAcara.pdf", true);

            exit;
        // }

    }
}
