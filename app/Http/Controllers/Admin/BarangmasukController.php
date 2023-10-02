<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\CustomerModel;
use App\Models\Admin\SupplierModel;
use App\Models\Admin\PbModel;
use App\Models\Admin\PbdetailModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class BarangmasukController extends Controller
{
    public function index()
    {
        $data["title"] = "Barang Masuk";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang Masuk', 'tbl_akses.akses_type' => 'create'))->count();
        $data["customer"] = CustomerModel::orderBy('customer_id', 'DESC')->get();
        $data["supplier"] = SupplierModel::orderBy('supplier_id', 'DESC')->get();
        $data["pengadaan"] = PbModel::orderBy('pb_id', 'DESC')->get();

        return view('Admin.BarangMasuk.index', $data);
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
            ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
            ->orderBy('bm_id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    $tgl = $row->bm_tanggal == '' ? '-' : Carbon::parse($row->bm_tanggal)->translatedFormat('d F Y');

                    return $tgl;
                })
                ->addColumn('supplier', function ($row) {
                    $supplier = $row->supplier_id == '' ? '-' : $row->supplier_nama;

                    return $supplier;
                })
                ->addColumn('barang', function ($row) {
                    $barang = $row->barang_id == '' ? '-' : $row->barang_nama;

                    return $barang;
                })
                ->addColumn('action', function ($row) {
                    $array = array(
                        "bm_id" => $row->bm_id,
                        "bm_kode" => $row->bm_kode,
                        "barang_kode" => $row->barang_kode,
                        "supplier_id" => $row->supplier_id,
                        "bm_tanggal" => $row->bm_tanggal,
                        "bm_jumlah" => $row->bm_jumlah
                    );
                    $button = '';
                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang Masuk', 'tbl_akses.akses_type' => 'update'))->count();
                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang Masuk', 'tbl_akses.akses_type' => 'delete'))->count();
                    if ($hakEdit > 0 && $hakDelete > 0) {
                        $button .= '
                        <div class="g-2">
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
                ->rawColumns(['action', 'tgl', 'supplier', 'barang'])->make(true);
        }
    }

    public function listpengadaan(Request $request)
    {
        if ($request->ajax()) {
            $data = PbModel::leftJoin('tbl_supplier', 'tbl_pb.supplier_id', '=', 'tbl_supplier.supplier_id')
            ->leftJoin('tbl_pegawai', 'tbl_pb.pb_pejabat', '=', 'tbl_pegawai.pegawai_id')
            ->leftJoin('tbl_user', 'tbl_pb.pb_pic', '=', 'tbl_user.user_id')
            ->orderBy('pb_id', 'DESC')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('ket', function ($row) {
                    $ket = $row->pb_keterangan == '' ? '-' : $row->pb_keterangan;

                    $ket = substr(strip_tags($ket), 0, 10);

                    return $ket;
                })
                ->addColumn('footer', function ($row) {
                    $footer = $row->pb_footer == '' ? '-' : $row->pb_footer;
                    $footer = substr(strip_tags($footer), 0, 10);

                    return $footer;
                })
                ->addColumn('supplier', function ($row) {
                    $supplier = $row->supplier_nama == '' ? '-' : $row->supplier_nama;

                    return $supplier;
                })
                ->addColumn('pejabat', function ($row) {
                    $pejabat = $row->nama_lengkap == '' ? '-' : $row->nama_lengkap;

                    return $pejabat;
                })
                ->addColumn('pic', function ($row) {
                    $pic = $row->user_nmlengkap == '' ? '-' : $row->user_nmlengkap;

                    return $pic;
                })
                ->addColumn('action', function ($row) {
                    $array = array(
                        "pb_id" => $row->pb_id,
                        "pb_kode" => $row->pb_kode,
                        // "pb_supplier" => $row->supplier_nama,
                        // "pb_pic" => $row->pb_pic,
                        // "pb_tanggal" => $row->pb_tanggal,
                        "pb_keterangan" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->pb_keterangan))
                    );
                    $button = '';

                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Pengadaan Barang', 'tbl_akses.akses_type' => 'update'))->count();
                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Pengadaan Barang', 'tbl_akses.akses_type' => 'delete'))->count();

                    $button .= '
                    <div class="g-2">
                        <a class="btn btn-primary btn-sm" href="'.url('admin/barang-masuk/copydocument/' .$row->pb_id).'" > Pilih</a>
                        <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick=copyDocument(' .$row->pb_id. ')>Pilih2</a>
                    </div>
                    ';

                    return $button;
                })
                ->rawColumns(['action', 'ket'])->make(true);
        }
    }


    public function copydocument(Request $request, $id){


        $cek_pb = PbModel::where('pb_id' , $id)->get();


        // dd($cek_pb);

        if(isset($cek_pb[0])){


            $data["title"] = "Barang Masuk";
            $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang Masuk', 'tbl_akses.akses_type' => 'create'))->count();
            $data["customer"] = CustomerModel::orderBy('customer_id', 'DESC')->get();
            $data["supplier"] = SupplierModel::orderBy('supplier_id', 'DESC')->get();
            $data["pengadaan"] = PbModel::orderBy('pb_id', 'DESC')->get();

            $data["header"] = PbModel::where('pb_id' , $id)->get();
            $data["detail"] = PbdetailModel::where('pb_id' , $id)->get();

            $random = Str::random(13);
            $data["bmkode"] = 'BRG-'.$random;

            dd( $data["bmkode"], $data["header"],$data["detail"]);

            return view('Admin.BarangMasuk.copy', $data);

        }else{

            return redirect('admin/barang-masuk')->with('error_message', ' Dokumen Tidak Ada Silahkan Cek Kembali');

        }






        // if($no_doc_trans){
		// 	$output['header'] = array();
		// 	$header = $this->model_bkb_opening_store->getHeaderBKB($no_doc_trans);
		// 	if($header){
		// 		$header[0]['tgl_doc_trans'] = date("d/m/Y",strtotime($header[0]['tgl_doc_trans']));
		// 		$header[0]['tgl_reff'] 		= date("d/m/Y",strtotime($header[0]['tgl_reff']));
		// 		$header[0]['tgl_kirim'] 	= date("d/m/Y",strtotime($header[0]['tgl_kirim']));
		// 		$output['header'] = $header[0];
		// 	}
		// 	$output['detail'] = array();
		// 	$detail = $this->model_bkb_opening_store->getDetailBKB($no_doc_trans);
		// 	if($detail){
		// 		$i = 1;
		// 		foreach ($detail as $key => $value) {
		// 			$output['detail'][$key] = array(
		// 				$value['urut'],
		// 				$value['kd_brg'],
		// 				$value['nm_barang'],
		// 				$value['qty'],
		// 			);
		// 			$i++;
		// 		}
		// 	}
		// }else{
		// 	$output['data'] = [];
		// }
		// echo json_encode($output);
	}

    public function proses_copy(Request $request, $id)
    {

        # Data barang

        $log_detail = array();
        $c_barang = count($request->barang);
        for($x=0; $x < $c_barang; $x++){
            $data_barang = array(
                'bm_tanggal'    => $request->tglmasuk,
                'bm_kode'       => $request->bmkode,
                'pb_id'         => $request->pb_id,
                'supplier_id'   => $request->supplier,
                'barang_kode'   => $request->barang[$x],
                'bm_jumlah'     => $request->jml,
            );
            array_push($log_detail, $data_barang);
            BarangmasukModel::create($data_barang);
        }


    }


    public function proses_tambah(Request $request)
    {

        //insert data
        BarangmasukModel::create([
            'bm_tanggal' => $request->tglmasuk,
            'bm_kode' => $request->bmkode,
            'barang_kode' => $request->barang,
            'supplier_id'   => $request->supplier,
            'bm_jumlah'   => $request->jml,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }


    public function proses_ubah(Request $request, BarangmasukModel $barangmasuk)
    {
        //update data
        $barangmasuk->update([
            'bm_tanggal' => $request->tglmasuk,
            'barang_kode' => $request->barang,
            'supplier_id'   => $request->supplier,
            'bm_jumlah'   => $request->jml,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_hapus(Request $request, BarangmasukModel $barangmasuk)
    {
        //delete
        $barangmasuk->delete();

        return response()->json(['success' => 'Berhasil']);
    }

}
