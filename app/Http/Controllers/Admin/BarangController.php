<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\BaranghistoryModel;
use App\Models\Admin\JenisBarangModel;
use App\Models\Admin\KategoriModel;
use App\Models\Admin\MerkModel;
use App\Models\Admin\SatuanModel;
use App\Models\Admin\WebModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Import\BarangImport;
use App\Export\BarangExport;
use Illuminate\Support\Facades\Input;
use File;
use Redirect;
use Excel;
use DB;
use PDF;




class BarangController extends Controller
{
    public function index()
    {
        $data["title"] = "Barang";
        $data["hakEdit"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang', 'tbl_akses.akses_type' => 'update'))->count();
        $data["hakDelete"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang', 'tbl_akses.akses_type' => 'delete'))->count();
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang', 'tbl_akses.akses_type' => 'create'))->count();
        $data["jenisbarang"] =  JenisBarangModel::orderBy('jenisbarang_id', 'DESC')->get();
        $data["satuan"] =  SatuanModel::orderBy('satuan_id', 'DESC')->get();
        $data["merk"] =  MerkModel::orderBy('merk_id', 'DESC')->get();
        return view('Admin.Barang.index', $data);
    }

    public function datahistory()
    {
        $data["title"] = "Barang";
        $data["hakEdit"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang', 'tbl_akses.akses_type' => 'update'))->count();
        $data["hakDelete"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang', 'tbl_akses.akses_type' => 'delete'))->count();
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang', 'tbl_akses.akses_type' => 'create'))->count();
        $data["jenisbarang"] =  JenisBarangModel::orderBy('jenisbarang_id', 'DESC')->get();
        $data["satuan"] =  SatuanModel::orderBy('satuan_id', 'DESC')->get();
        $data["merk"] =  MerkModel::orderBy('merk_id', 'DESC')->get();
        return view('Admin.Barang.history', $data);
    }

    public function getbarang($id)
    {
        $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')->where('tbl_barang.barang_kode', '=', $id)->get();
        return json_encode($data);
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $user = Session::get('user')->user_nmlengkap;

            if($user == 'Super Administrator'){

                if($request->tglawal != ''){

                    $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                    ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                    ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                    ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                    ->whereBetween('tbl_barang.created_at', [$request->tglawal, $request->tglakhir])
                    ->orderBy('barang_id', 'DESC')->get();
                }else{
                    $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                    ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                    ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                    ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                    ->orderBy('barang_id', 'DESC')->get();

                }
            }else{
                if($request->tglawal != ''){

                    $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                    ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                    ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                    ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                    ->whereBetween('tbl_barang.created_at', [$request->tglawal, $request->tglakhir])
                    ->where('make_by', $user)
                    ->orderBy('barang_id', 'DESC')->get();
                }else{
                    $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                    ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                    ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                    ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                    ->where('make_by', $user)
                    ->orderBy('barang_id', 'DESC')->get();
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('checkbox', function($row) {
                    $checkbox = '<div class="form-check">
                    <input type="checkbox" name="barang_checkbox[]" class="barang_checkbox form-check-input" value='. $row->barang_id .'>
                    </div>';
                    return $checkbox;
                })
                ->addColumn('img', function ($row) {
                    $array = array(
                        "barang_gambar" => $row->barang_gambar,
                    );
                    if ($row->barang_gambar == "image.png") {
                        $img = '<a data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Gmodaldemo8" onclick=gambar(' . json_encode($array) . ')><span class="avatar avatar-lg cover-image" style="background: url(&quot;' . url('/assets/default/barang') . '/' . $row->barang_gambar . '&quot;) center center;"></span></a>';
                    } else {
                        $img = '<a data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Gmodaldemo8" onclick=gambar(' . json_encode($array) . ')><span class="avatar avatar-lg cover-image" style="background: url(&quot;' . url('/uploads/image/' . $row->barang_gambar) . '&quot;) center center;"></span></a>';
                    }

                    return $img;
                })
                ->addColumn('jenisbarang', function ($row) {
                    $jenisbarang = $row->jenisbarang_id == '' ? '-' : $row->jenisbarang_nama;

                    return $jenisbarang;
                })
                ->addColumn('kategori', function ($row) {
                    $kategori = $row->kategori_id == '' ? '-' : $row->kategori_nama;

                    return $kategori;
                })
                ->addColumn('satuan', function ($row) {
                    $satuan = $row->satuan_id == '' ? '-' : $row->satuan_nama;

                    return $satuan;
                })
                ->addColumn('merk', function ($row) {
                    $merk = $row->merk_id == '' ? '-' : $row->merk_nama;

                    return $merk;
                })
                ->addColumn('make_by', function ($row) {
                    $make_by = $row->make_by == '' ? '-' : $row->make_by;

                    return $make_by;
                })
                ->addColumn('currency', function ($row) {
                    $currency = $row->barang_harga == '' ? '-' : 'Rp ' . number_format($row->barang_harga, 0);

                    return $currency;
                })
                ->addColumn('totalstok', function ($row) use ($request) {
                    if ($request->tglawal == '') {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                    } else {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
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
                ->addColumn('action', function ($row) {
                    $array = array(
                        "barang_id" => $row->barang_id,
                        "jenisbarang_id" => $row->jenisbarang_id,
                        "satuan_id" => $row->satuan_id,
                        "merk_id" => $row->merk_id,
                        "barang_id" => $row->barang_id,
                        "barang_kode" => $row->barang_kode,
                        "barang_nama" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->barang_nama)),
                        "barang_harga" => $row->barang_harga,
                        "barang_stok" => $row->barang_stok,
                        "barang_gambar" => $row->barang_gambar,
                    );
                    $button = '';
                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang', 'tbl_akses.akses_type' => 'update'))->count();
                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang', 'tbl_akses.akses_type' => 'delete'))->count();
                    if ($hakEdit > 0 && $hakDelete > 0) {
                        $button .= '
                            <a class="dropdown-item btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span> Edit</a>
                        ';
                        // <div class="dropdown">
                        //     <button class="btn btn-light dropdown-toggle" id="dropdownNoAnimation" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
                        //     <div class="dropdown-menu" aria-labelledby="dropdownNoAnimation">
                        //         <a class="dropdown-item btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span> Edit</a>
                        // <a class="dropdown-item btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span> Remove</a>
                        //     </div>
                        // </div>

                    } else if ($hakEdit > 0 && $hakDelete == 0) {
                        $button .= ' <a class="dropdown-item btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span> Edit</a>';
                        // <div class="dropdown">
                        //     <button class="btn btn-light dropdown-toggle" id="dropdownNoAnimation" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
                        //     <div class="dropdown-menu" aria-labelledby="dropdownNoAnimation">
                        //         <a class="dropdown-item btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span> Edit</a>
                        //     </div>
                        // </div>

                    } else if ($hakEdit == 0 && $hakDelete > 0) {
                        // $button .= '
                        // <div class="dropdown">
                        //     <button class="btn btn-light dropdown-toggle" id="dropdownNoAnimation" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
                        //     <div class="dropdown-menu" aria-labelledby="dropdownNoAnimation">
                        //         <a class="dropdown-item btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span> Remove</a>
                        //     </div>
                        // </div>
                        // ';
                    } else {
                        $button .= '-';
                    }

                    return $button;
                })
                ->rawColumns(['checkbox','action', 'img', 'jenisbarang', 'satuan','kategori', 'merk','currency', 'totalstok', 'make_by'])->make(true);
        }
    }

    public function gethistory(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('tbl_barang_log')
            ->leftJoin('tbl_barang', 'tbl_barang_log.barang_id' , '=','tbl_barang.barang_id' )
            ->leftJoin('tbl_user', 'tbl_barang_log.user_id' , '=',  'tbl_user.user_id')
            ->select('tbl_barang_log.keterangan','tbl_barang_log.created_at', 'tbl_user.user_nmlengkap', 'tbl_barang.barang_kode','tbl_barang.barang_nama')
            ->orderBy('tanggal', 'DESC')->get();

            dd($data);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('barang_kode', function ($row) {
                    $barang_kode = $row->barang_kode == '' ? '-' : $row->barang_kode;

                    return $barang_kode;
                })
                ->addColumn('barang_nama', function ($row) {
                    $barang_nama = $row->barang_nama == '' ? '-' : $row->barang_nama;

                    return $barang_nama;
                })
                ->addColumn('keterangan', function ($row) {
                    $keterangan = $row->keterangan == '' ? '-' : $row->keterangan;

                    return $keterangan;
                })
                ->addColumn('user_nmlengkap', function ($row) {
                    $user_nmlengkap = $row->user_nmlengkap == '' ? '-' : $row->user_nmlengkap;

                    return $user_nmlengkap;
                })
                ->addColumn('created_at', function ($row) {
                    $created_at = $row->created_at == '' ? '-' : date('d/m/y h:i A', strtotime($row->created_at));

                    return $created_at;
                })
                ->rawColumns(['barang_kode', 'barang_nama', 'user_nmlengkap', 'keterangan','created_at' ])->make(true);
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
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                    } else {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
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

                    $array = array(
                        "barang_kode" => $row->barang_kode,
                        "barang_nama" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->barang_nama)),
                        "satuan_nama" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->satuan_nama)),
                        "jenisbarang_nama" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->jenisbarang_nama)),
                        "barang_harga" => number_format($row->barang_harga, 0),
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
        $img = "";
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->nama)));

        //upload image
        if ($request->file('foto') == null) {
            $img = "image.png";
        } else {

            $imageName = time().'.'.request()->foto->getClientOriginalExtension();
            $img = $imageName;
            request()->foto->move(public_path('uploads/image'), $imageName);

        }


        //create
        BarangModel::create([
            'barang_gambar' => $img,
            'jenisbarang_id' => $request->jenisbarang,
            'satuan_id' => $request->satuan,
            'merk_id' => $request->merk,
            'kategori_id' => $request->kategori,
            'barang_kode' => $request->kode,
            'barang_nama' => $request->nama,
            'barang_slug' => $slug,
            'barang_harga' => $request->harga,
            'barang_stok' => 0,
            'make_by'       => Session::get('user')->user_nmlengkap,

        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_ubah(Request $request, BarangModel $barang)
    {

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->nama)));

        //check if image is uploaded
        if ($request->hasFile('foto')) {

            //upload new image
            $imageName = time().'.'.request()->foto->getClientOriginalExtension();
            $img = $imageName;
            request()->foto->move(public_path('uploads/image'), $imageName);

            //delete old image
            if(\File::exists(public_path('uploads/image/'. $barang->barang_gambar))){
                \File::delete(public_path('uploads/image/'. $barang->barang_gambar));
            }

            //update data with new image
            $barang->update([
                'barang_gambar' => $img,
                'jenisbarang_id'=> $request->jenisbarang,
                'satuan_id'     => $request->satuan,
                'merk_id'       => $request->merk,
                'kategori_id'   => $request->kategori,
                'barang_kode'   => $request->kode,
                'barang_nama'   => $request->nama,
                'barang_slug'   => $slug,
                'barang_harga'  => $request->harga,
                'barang_stok'   => $request->stok,
                'edited_by'       => Session::get('user')->user_nmlengkap,
            ]);
        } else {
            //update data without image
            $barang->update([
                'jenisbarang_id'=> $request->jenisbarang,
                'satuan_id'     => $request->satuan,
                'merk_id'       => $request->merk,
                'kategori_id'   => $request->kategori,
                'barang_kode'   => $request->kode,
                'barang_nama'   => $request->nama,
                'barang_slug'   => $slug,
                'barang_harga'  => $request->harga,
                'barang_stok'   => $request->stok,
                'edited_by'       => Session::get('user')->user_nmlengkap,
            ]);
        }

        return response()->json(['success' => 'Berhasil']);
    }


    public function proses_hapus(Request $request, BarangModel $barang)
    {
        //delete image
        if(\File::exists(public_path('uploads/image/'. $barang->barang_gambar))){
            \File::delete(public_path('uploads/image/'. $barang->barang_gambar));
        }

        //delete
        $barang->delete();

        return response()->json(['success' => 'Berhasil']);
    }


    public function import_barang2(Request $request)
    {
        //get file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('message', 'Please upload a CSV file');

        $filePath=$upload->getRealPath();

        //open and read
        $file=fopen($filePath, 'r');
        $header= fgetcsv($file);
        $escapedHeader=[];

        //validate
        foreach ($header as $key => $value) {
            $lheader=strtolower($value);
            $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }


        //looping through other columns
        while($columns=fgetcsv($file))
        {
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }

            $data= array_combine($escapedHeader, $columns);

            // dd($data);
            if($data['jenis'])
                $slug_jenis = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['jenis'])));
                $jenis_data = JenisBarangModel::firstOrCreate(['jenisbarang_nama' => $data['jenis'], 'jenisbarang_slug' => $slug_jenis, 'jenisbarang_ket' => '']);

            if($data['kategori'])
                $slug_kategori = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['kategori'])));
                $kategori       = KategoriModel::firstOrCreate(['kategori_nama' => $data['kategori'], 'kategori_slug' => $slug_kategori, 'kategori_ket' => '']);

            if($data['merk'])
                $slug_merk = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['merk'])));
                $merk       = MerkModel::firstOrCreate(['merk_nama' => $data['merk'], 'merk_slug' => $slug_merk, 'merk_keterangan' => '']);

            if($data['satuan'])
                $slug_satuan = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['satuan'])));
                $satuan_data    = SatuanModel::firstOrCreate(['satuan_nama' => $data['satuan'], 'satuan_slug' => $slug_satuan, 'satuan_keterangan' => '']);

            $product        = BarangModel::firstOrNew([ 'barang_nama'=>$data['name'] ]);

            if($data['image'])
                $product->barang_gambar = $data['image'];
            else
                $product->barang_gambar = 'image.png';


            $random = Str::random(13);

            $codeProduct = 'BRG-'.$random;
            $slug_barang = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));

            $product->barang_kode       = $codeProduct;
            $product->barang_nama       = $data['name'];
            $product->barang_slug       = $slug_barang;
            $product->jenisbarang_id    = $jenis_data->jenisbarang_id;
            $product->kategori_id       = $kategori->kategori_id;
            $product->merk_id           = $merk->merk_id;
            $product->satuan_id         = $satuan_data->satuan_id;
            $product->barang_spek       = $data['spek'];
            $product->barang_stok       = 0;
            $product->barang_harga      = $data['harga'];

            if($data['name'])
                $product->save();


        }
        return redirect('admin/barang')->with('create_message', 'Product imported successfully');
    }


    public function import_barang(Request $request)
    {

        $gudang = request('to_warehouse_id');
        $file   = $request->file('file');

        $array= Excel::toArray(new BarangImport, $file);

        $data = [];
        foreach($array as $key => $val){

            foreach ($val as $key2 => $val2){

                if(isset($val2['jenis'])){
                    $slug_jenis = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $val2['jenis'])));
                    $jenis_data = JenisBarangModel::firstOrCreate(['jenisbarang_nama' => $val2['jenis'], 'jenisbarang_slug' => $slug_jenis, 'jenisbarang_ket' => '']);
                    $jenis_id   = $jenis_data->jenisbarang_id;
                }else{
                    $jenis_id = null;
                }


                if(isset($val2['kategori'])){
                    $slug_kategori  = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $val2['kategori'])));

                    $kategori       = KategoriModel::firstOrCreate(['kategori_nama' => $val2['kategori'], 'kategori_slug' => $slug_kategori, 'kategori_ket' => '']);
                    $kategori_id    = $kategori->kategori_id;
                }else{
                    $kategori_id    = null;
                }



                if(isset($val2['merk'])){
                    $slug_merk = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $val2['merk'])));
                    $merk       = MerkModel::firstOrCreate(['merk_nama' => $val2['merk'], 'merk_slug' => $slug_merk, 'merk_keterangan' => '']);
                    $merk_id    = $merk->merk_id;
                }else{
                    $merk_id    = null;
                }


                if(isset($val2['satuan'])){
                    $slug_satuan = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $val2['satuan'])));
                    $satuan_data    = SatuanModel::firstOrCreate(['satuan_nama' => $val2['satuan'], 'satuan_slug' => $slug_satuan, 'satuan_keterangan' => '']);
                    $satuan_id      = $satuan_data->satuan_id;
                }else{
                    $satuan_id      = null;
                }



                $product        = BarangModel::firstOrNew([ 'barang_nama'=>$val2['name'] ]);

                if($val2['image'])
                    $product->barang_gambar = $val2['image'];
                else
                    $product->barang_gambar = 'image.png';


                $random = Str::random(13);

                $codeProduct = 'BRG-'.$random;
                $slug_barang = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $val2['name'])));

                $product->barang_kode       = $codeProduct;
                $product->barang_nama       = $val2['name'];
                $product->barang_slug       = $slug_barang;
                $product->jenisbarang_id    = $jenis_id;
                $product->kategori_id       = $kategori_id;
                $product->merk_id           = $merk_id;
                $product->satuan_id         = $satuan_id;
                $product->barang_spek       = $val2['spek'];
                $product->barang_stok       = 0;
                $product->barang_harga      = $val2['harga'];

                $product->save();

            }
        }


        // Excel::import(new AdjustmentStokExcelImport, $request->file('file'));

        return redirect('admin/barang')->with('create_message', 'Product imported successfully');
    }

    function bulk_delete(Request $request)
    {
        $id_array = $request->input('id');
        // dd($id_array);
        $barang = BarangModel::whereIn('barang_id', $id_array);
        if($barang->delete())
        {
            echo 'Data Deleted';
        }
    }

    function export_barang(Request $request)
    {

        $jenis      = $request->jenisbarang;
        $satuan     = $request->satuan;
        $type       = $request->type;

        try{
            return Excel::download(new BarangExport($jenis,$satuan), 'barang_export-'.date('d-m-y').'.'.$type.'');
        }catch(\Exception $e) {
            return redirect()->back()->with('error_message', 'Operation Failed');
        }
    }

    function print(Request $request)
    {
        $user = Session::get('user')->user_nmlengkap;

        if($user == 'Super Administrator'){

            if($request->tglawal != ''){
                $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                ->whereBetween('tbl_barang.created_at', [$request->tglawal, $request->tglakhir])
                ->orderBy('barang_id', 'DESC')->get();
            }else{
                $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                ->orderBy('barang_id', 'DESC')->get();
            }

        }else{
            if($request->tglawal != ''){
                $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                ->whereBetween('tbl_barang.created_at', [$request->tglawal, $request->tglakhir])
                ->where('make_by', $user)
                ->orderBy('barang_id', 'DESC')->get();
            }else{
                $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                ->where('make_by', $user)
                ->orderBy('barang_id', 'DESC')->get();
            }
        }

        // dd('test');
        $data["title"] = "Print Data Barang";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        return view('Admin.Barang.print', $data);
    }

    function pdf(Request $request)
    {
        $user = Session::get('user')->user_nmlengkap;

        if($user == 'Super Administrator'){

            if($request->tglawal != ''){
                $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                ->whereBetween('created_at', [$request->tglawal, $request->tglakhir])
                ->orderBy('barang_id', 'DESC')->get();
            }else{
                $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                ->orderBy('barang_id', 'DESC')->get();
            }

        }else{
            if($request->tglawal != ''){
                $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                ->whereBetween('created_at', [$request->tglawal, $request->tglakhir])
                ->where('make_by', $user)
                ->orderBy('barang_id', 'DESC')->get();
            }else{
                $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                ->leftJoin('tbl_kategori', 'tbl_kategori.kategori_id', '=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                ->where('make_by', $user)
                ->orderBy('barang_id', 'DESC')->get();
            }
        }

        $data["title"] = "PDF Data Barang";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        $pdf = PDF::loadView('Admin.Barang.pdf', $data)->setPaper('a4', 'landscape')->setOption('margin', 0);

        return $pdf->download('lap-data-barang.pdf');


    }


}
