<?php

// Aryatama write

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\SpkModel;
use App\Models\Admin\SpkdetailModel;
use App\Models\Admin\PegawaiModel;
use App\Models\Admin\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Pdf\Nota;
use DB;

class BeritaController extends Controller
{
    public function index()
    {
        $data["title"] = "Tambah";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Berita Acara', 'tbl_akses.akses_type' => 'create'))->count();
        $data["supplier"] = SupplierModel::orderBy('supplier_id', 'DESC')->get();
        $data["pegawai"] = PegawaiModel::orderBy('pegawai_id', 'DESC')->get();
        return view('Admin.Berita.index', $data);
    }


    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('tbl_spk as h')
                ->leftJoin('tbl_pegawai as p1', 'h.spk_pihak_1', '=', 'p1.pegawai_id')
                ->leftJoin('tbl_supplier as p2', 'h.spk_pihak_2', '=', 'p2.supplier_id')
                ->leftJoin('tbl_pegawai as m', 'h.spk_mengetahui', '=', 'm.pegawai_id')
                ->leftJoin('tbl_user', 'h.spk_pic', '=', 'tbl_user.user_id')
                ->Select('h.*',                
                    'p1.nip as p1_nip', 'p1.nama_lengkap as p1_nama', 'p1.jabatan as p1_jabatan','p1.alamat as p1_alamat', 
                    'm.nip as m_nip', 'm.nama_lengkap as m_nama', 'm.jabatan as m_jabatan', 'm.alamat as m_alamat',
                    'p2.supplier_nama as sp_nama', 'p2.nama_lengkap as sp_nama','p2.jabatan as sp_jabatan', 'p2.alamat as sp_alamat')
            ->orderBy('spk_id', 'DESC')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('pihak_1', function ($row) {
                    $pihak_1 = $row->p1_nama == '' ? '-' : $row->p1_nama;

                    $pihak_1 = substr(strip_tags($pihak_1), 0, 20);

                    return $pihak_1;
                })
                ->addColumn('pihak_2', function ($row) {
                    $pihak_2 = $row->sp_nama == '' ? '-' : $row->sp_nama;

                    $pihak_2 = substr(strip_tags($pihak_2), 0, 20);

                    return $pihak_2;
                })
                ->addColumn('jenis', function ($row) {
                    $jenis = $row->spk_jenis == '' ? '-' : $row->spk_jenis;

                    $jenis = substr(strip_tags($jenis), 0, 20);

                    return $jenis;
                })
                ->addColumn('action', function ($row) {
                    $array = array(
                        "spk_id" => $row->spk_id,
                    );
                    $button = '';
                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Berita Acara', 'tbl_akses.akses_type' => 'update'))->count();
                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Berita Acara', 'tbl_akses.akses_type' => 'delete'))->count();
                    if ($hakEdit > 0 && $hakDelete > 0) {
                        $button .= '
                        <div class="g-2">
                        <a class="btn text-success btn-sm" target="_blank" href="spk/genInvoice/'.$row->spk_id.'" > <span class="fe fe-printer text-success fs-14"></span> PDF</a>
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
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->pb)));

        $header = array(
            'pb_kode'   => $request->pb_kode,
            'supplier_id' => $request->pb_supplier,
            'pb_tanggal'    => $request->pb_tanggal,
            'pb_keterangan' => $request->keterangan,
        );
        // dd($header, $request);


        //insert data
        // PbModel::create($header);


        $c_barang = count($request->barang_kode);
        $log_detail = array();
        for($x=0; $x < $c_barang; $x++) {
            $detail_barang = array(
                'barang_kode' => $request->barang_kode[$x],
                'satuan'       => $request->satuan[$x],
                'spek'       => $request->spek[$x],
                'pb_jumlah'       => $request->jml[$x],
                'pb_harga'       => $request->harga[$x],
            );
            array_push($log_detail, $detail_barang);
        }

        // dd($log_detail);


        // $data = response()->json(['success' => 'Berhasil']);

        // return view('Admin.Berita.index', $data);

        // \Session::flash('create_message', 'Sent successfully');

        return redirect('admin/pb')->with('create_message', 'Pengajuan berhasil No : '. $request->pb_kode);
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

    public function proses_hapus(Request $request, PbModel $pb)
    {
        //delete
        $pb->delete();

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
                'brg.barang_kode','brg.barang_nama as barang',
                's.satuan_nama as satuan')
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
