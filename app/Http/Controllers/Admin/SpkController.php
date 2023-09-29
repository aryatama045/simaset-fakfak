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
use DB;

class SpkController extends Controller
{
    public function index()
    {
        $data["title"] = "Surat Perintah Kerja";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Surat Perintah Kerja', 'tbl_akses.akses_type' => 'create'))->count();
        $data["supplier"] = SupplierModel::orderBy('supplier_id', 'DESC')->get();
        $data["pegawai"] = PegawaiModel::orderBy('pegawai_id', 'DESC')->get();
        return view('Admin.Spk.index', $data);
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

            dd($data);

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
                ->rawColumns(['action', 'ket'])->make(true);
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
            'spk_pic'    => '-',
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


                $general_setting = DB::table('tbl_web')->latest()->first();

                if($data_detail){
                    $output['general_setting']		= $general_setting;
                    $output['header']		= $data_header;
                    $output['detail']		= $data_detail;
                }
            }

            // dd($data_header, $data_detail);



            $myPdf = new Spk($output);

            $myPdf->Output('I', "Spk.pdf", true);

            exit;
        }

    }
}
