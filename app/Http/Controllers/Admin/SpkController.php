<?php

// Aryatama write

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\SpkModel;
use App\Models\Admin\SpkdetailModel;
use App\Models\Admin\SupplierModel;
use App\Models\Admin\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Pdf\Spk;
use App\Export\SpkExport;
use DB;
use File;
use Redirect;
use Excel;

class SpkController extends Controller
{
    public function index()
    {
        $data["title"] = "Surat Perintah Kerja";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Surat Perintah Kerja', 'tbl_akses.akses_type' => 'create'))->count();
        $data["supplier"] = SupplierModel::orderBy('supplier_id', 'DESC')->get();
        $data["pegawai"] = PegawaiModel::orderBy('pegawai_id', 'DESC')->get();

        $bulan = date('m'); $tahun=date('Y');
        $no_doc = '/'.$bulan.'/SPK/BPKAD/'.$tahun;
        $count_pesanan = SpkModel::where('spk_kode', $no_doc)->orwhere('spk_kode', 'like', '%'.$no_doc.'%')->count();
        $jum_no = $count_pesanan + 0001;
        $data["no_spk"] = str_pad($jum_no, 4, '0', STR_PAD_LEFT);

        return view('Admin.Spk.index', $data);
    }


    public function show(Request $request)
    {
        if ($request->ajax()) {
            $user = Session::get('user')->user_nmlengkap;

            if($user == 'Super Administrator'){
                $data = DB::table('tbl_spk as h')
                    ->leftjoin('tbl_pegawai as p1', 'h.spk_pihak_1', '=', 'p1.pegawai_id')
                    ->leftjoin('tbl_supplier as p2', 'h.spk_pihak_2', '=', 'p2.supplier_id')
                    ->leftjoin('tbl_pegawai as m', 'h.spk_mengetahui', '=', 'm.pegawai_id')
                    ->select('h.*',
                        'p1.nip as p1_nip', 'p1.nama_lengkap as p1_nama', 'p1.jabatan as p1_jabatan','p1.alamat as p1_alamat',
                        'm.nip as m_nip', 'm.nama_lengkap as m_nama', 'm.jabatan as m_jabatan', 'm.alamat as m_alamat',
                        'p2.supplier_nama as sp_nama', 'p2.nama_lengkap as sp_nama','p2.jabatan as sp_jabatan', 'p2.alamat as sp_alamat')
                ->orderby('spk_tanggal', 'DESC')->get();
            }else{
                $data = DB::table('tbl_spk as h')
                    ->leftjoin('tbl_pegawai as p1', 'h.spk_pihak_1', '=', 'p1.pegawai_id')
                    ->leftjoin('tbl_supplier as p2', 'h.spk_pihak_2', '=', 'p2.supplier_id')
                    ->leftjoin('tbl_pegawai as m', 'h.spk_mengetahui', '=', 'm.pegawai_id')
                    ->where('create_by', $user)
                    ->select('h.*',
                        'p1.nip as p1_nip', 'p1.nama_lengkap as p1_nama', 'p1.jabatan as p1_jabatan','p1.alamat as p1_alamat',
                        'm.nip as m_nip', 'm.nama_lengkap as m_nama', 'm.jabatan as m_jabatan', 'm.alamat as m_alamat',
                        'p2.supplier_nama as sp_nama', 'p2.nama_lengkap as sp_nama','p2.jabatan as sp_jabatan', 'p2.alamat as sp_alamat')
                ->orderby('spk_tanggal', 'DESC')->get();
            }


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
                ->addColumn('create_by', function ($row) {
                    $create_by = $row->create_by == '' ? '-' : $row->create_by;

                    return $create_by;
                })
                ->addColumn('action', function ($row) {
                    $array = array(
                        "spk_id" => $row->spk_id,
                        "spk_kode" => $row->spk_kode,
                    );
                    $button = '';
                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Surat Perintah Kerja', 'tbl_akses.akses_type' => 'update'))->count();
                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Surat Perintah Kerja', 'tbl_akses.akses_type' => 'delete'))->count();
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
                ->rawColumns(['action', 'ket','create_by'])->make(true);
        }
    }

    public function proses_tambah(Request $request)
    {

        $header = array(
            'spk_kode'          => $request->spk_kode,
            'spk_jenis'         => $request->spk_jenis,
            'spk_rekening'      => $request->spk_rekening,
            'spk_tanggal'       => $request->spk_tanggal,
            'spk_pihak_1'       => $request->spk_pihak_1,
            'spk_pihak_2'       => $request->spk_pihak_2,
            'spk_mengetahui'    => $request->spk_mengetahui,
            'spk_pic'           => '-',
            'create_by'       => Session::get('user')->user_nmlengkap,
        );
        SpkModel::create($header);

        $spk_data = SpkModel::latest()->first();

        $Jp = count($request->jenis_pekerjaan);
        $log_detail = array();
        for($x=0; $x < $Jp; $x++) {
            $detail_jenis = array(
                'spk_id'            => $spk_data->spk_id,
                'jenis_pekerjaan'   => $request->jenis_pekerjaan[$x],
                'detail_pekerjaan'  => $request->detail_pekerjaan[$x],
            );
            array_push($log_detail, $detail_jenis);
            SpkdetailModel::create($detail_jenis);
        }

        return redirect('admin/spk')->with('create_message', 'Pengajuan SPK Nomor : '. $request->spk_kode);
    }

    public function proses_ubah(Request $request, SpkModel $pb)
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

    public function proses_hapus(Request $request, SpkModel $spk, $id)
    {

        SpkModel::where('spk_id',$id)->delete();
        SpkdetailModel::where('spk_id',$id)->delete();

        return response()->json(['success' => 'Berhasil']);
    }


    public function genInvoice($id)
    {

        if (!empty($id)){


            $data_header = DB::table('tbl_spk as h')
            ->leftJoin('tbl_pegawai as p1', 'h.spk_pihak_1', '=', 'p1.pegawai_id')
            ->leftJoin('tbl_supplier as p2', 'h.spk_pihak_2', '=', 'p2.supplier_id')
            ->leftJoin('tbl_pegawai as m', 'h.spk_mengetahui', '=', 'm.pegawai_id')
            ->leftJoin('tbl_user', 'h.spk_pic', '=', 'tbl_user.user_id')
            ->where('h.spk_id', $id)
            ->Select('h.*',
                'p1.nip as p1_nip', 'p1.nama_lengkap as p1_nama', 'p1.jabatan as p1_jabatan','p1.alamat as p1_alamat',
                'm.nip as m_nip', 'm.nama_lengkap as m_nama', 'm.jabatan as m_jabatan', 'm.alamat as m_alamat',
                'p2.supplier_nama as sp_perusahaan', 'p2.nama_lengkap as sp_nama','p2.jabatan as sp_jabatan', 'p2.alamat as sp_alamat')
            ->orderBy('spk_id', 'DESC')->get();

            if($data_header) {

                $data_detail = DB::table('tbl_spkdetail as spkd')
                ->where('spkd.spk_id', $id)
                ->select('spkd.*')
                ->get();

                $data_lampiran = DB::table('tbl_pbdetail as pbd')
                ->leftJoin('tbl_pb as pb', 'pbd.pb_id', '=', 'pb.pb_id')
                ->leftJoin('tbl_spk as spk', 'pb.spk_kode', '=', 'spk.spk_kode')
                ->leftjoin('tbl_barang as brg', 'pbd.barang_id', '=', 'brg.barang_id')
                ->where('spk.spk_id', $id)
                ->select('pbd.*','brg.barang_nama','brg.barang_harga')
                ->get();


                $general_setting = DB::table('tbl_web')->latest()->first();

                if($data_detail){
                    $output['general_setting']		= $general_setting;
                    $output['header']		= $data_header;
                    $output['detail']		= $data_detail;
                    $output['lampiran']		= $data_lampiran;
                }
            }

            // dd($data_header, $data_detail, $data_lampiran);


            ob_end_clean(); //    the buffer and never prints or returns anything.


            $spk_kode = $output['header'][0]->spk_kode;

            $myPdf = new Spk($output);

            $myPdf->Output('I', "Spk(".$spk_kode.").pdf", true);


            exit;
        }

    }

    function spk_export(Request $request)
    {

        $tgl_awal       = '';//$request->tgl_awal;
        $tgl_akhir      = '';//$request->tgl_akhir;
        $type           = 'xlsx';

        try{
            return Excel::download(new SpkExport($tgl_awal, $tgl_akhir), 'spk_export-'.date('d-m-y').'.'.$type.'');
        }catch(\Exception $e) {
            return redirect()->back()->with('error_message', 'Operation Failed');
        }
    }


}
