<?php

// Aryatama write

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\PbModel;
use App\Models\Admin\PbdetailModel;

use App\Models\Admin\SupplierModel;
use App\Models\Admin\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Pdf\Nota;
use DB;

class PbController extends Controller
{
    public function index()
    {
        $data["title"] = "Tambah";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Pengadaan Barang', 'tbl_akses.akses_type' => 'create'))->count();
        $data["supplier"] = SupplierModel::orderBy('supplier_id', 'DESC')->get();
        $data["pegawai"] = PegawaiModel::orderBy('pegawai_id', 'DESC')->get();
        return view('Admin.PengadaanBarang.index', $data);
    }

    public function add()
    {
        $data["title"] = "Pengadaan Barang";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Pengadaan Barang', 'tbl_akses.akses_type' => 'create'))->count();
        $data["supplier"] = SupplierModel::orderBy('supplier_id', 'DESC')->get();
        return view('Admin.PengadaanBarang.index', $data);
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = PbModel::leftJoin('tbl_supplier', 'tbl_pb.supplier_id', '=', 'tbl_supplier.supplier_id')
            ->leftJoin('tbl_user', 'tbl_pb.pb_pic', '=', 'tbl_user.user_id')
            ->orderBy('pb_id', 'DESC')->get();

            // $data = PbModel::with('supplier')->orderBy('pb_id', 'DESC')->get();

            // dd($data);
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
                    if ($hakEdit > 0 && $hakDelete > 0) {
                        $button .= '
                        <div class="g-2">
                        <a class="btn text-success btn-sm" target="_blank" href="pb/genInvoice/'.$row->pb_id.'" > <span class="fe fe-printer text-success fs-14"></span> PDF</a>
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
                ->rawColumns(['action', 'ket'])->make(true);
        }
    }

    public function proses_tambah(Request $request)
    {
        $header = array(
            'pb_kode'       => $request->pb_kode,
            'supplier_id'   => $request->pb_supplier,
            'pb_pejabat'    => $request->pb_pejabat,
            'pb_tanggal'    => $request->pb_tanggal,
            'pb_keterangan' => $request->pb_keterangan,
            'pb_footer'     => $request->pb_footer,
        );


        //insert data
        PbModel::create($header);

        $pb_data = PbModel::latest()->first();

        $c_barang = count($request->barang_kode);
        $log_detail = array();
        for($x=0; $x < $c_barang; $x++) {
            $detail_barang = array(
                'pb_id'         => $pb_data->pb_id,
                'barang_id'     => $request->barang_id[$x],
                'barang_kode'   => $request->barang_kode[$x],
                'satuan'        => $request->satuan[$x],
                'spek'          => $request->spek[$x],
                'pb_jumlah'     => $request->jml[$x],
                'pb_harga'      => $request->harga[$x],
            );
            array_push($log_detail, $detail_barang);
            PbdetailModel::create( $detail_barang);
        }

        return redirect('admin/pb')->with('create_message', 'Pengajuan Nomor : '. $request->pb_kode);
    }

    public function proses_ubah(Request $request, PbModel $pb)
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

    public function proses_hapus(Request $request, PbModel $pb, $id)
    {
        //delete
        PbModel::where('pb_id',$id)->delete();
        PbdetailModel::where('pb_id',$id)->delete();

        return response()->json(['success' => 'Berhasil']);
    }




    public function genInvoice($id)
    {

        // if (!empty($id)){


            $data_header = DB::table('tbl_pb as h')
            ->leftjoin('tbl_supplier as s', 'h.supplier_id', '=', 's.supplier_id')
            ->leftjoin('tbl_user as u', 'h.pb_pic', '=', 'u.user_id')
            ->where('h.pb_id', $id)
            ->select('h.pb_kode as no_dok','h.pb_keterangan','h.pb_footer','h.created_at',
                    's.supplier_nama as supplier','s.supplier_keterangan',
                    'u.user_nmlengkap as nama_user' )
            ->get();


            // if($data_header) {

                $data_detail = DB::table('tbl_pbdetail as pbd')
                ->leftjoin('tbl_barang as brg', 'pbd.barang_id', '=', 'brg.barang_id')
                ->leftjoin('tbl_satuan as s', 'brg.satuan_id','=', 's.satuan_id')
                ->where('pbd.pb_id', $id)
                ->select('pbd.pb_jumlah as jumlah','pbd.pb_harga',
                'brg.barang_kode','brg.barang_nama',
                's.satuan_nama')
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

            $myPdf = new Nota($output);

            $myPdf->Output('I', "Notapesan.pdf", true);

            exit;
        // }

    }
}
