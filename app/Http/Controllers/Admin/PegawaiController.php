<?php

// Aryatama write

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PegawaiController extends Controller
{
    public function index()
    {
        $data["title"] = "Pegawai";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Pegawai', 'tbl_akses.akses_type' => 'create'))->count();
        return view('Admin.Pegawai.index', $data);
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = PegawaiModel::orderBy('pegawai_id', 'DESC')->get();
            return Datatables::of($data)
                ->addColumn('jbtn', function ($row) {
                    $jabatan = $row->jabatan == '' ? '-' : $row->jabatan;

                    $jabatan = substr(strip_tags($jabatan), 0, 20);

                    return $jabatan;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $array = array(
                        "pegawai_id" => $row->pegawai_id,
                        "nip" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->nip)),
                        "nama_lengkap" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->nama_lengkap)),
                        "jabatan" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->jabatan)),
                        "no_telp" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->no_telp)),
                        "alamat" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->alamat)),
                        "keterangan" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->keterangan))
                    );
                    $button = '';
                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Pegawai', 'tbl_akses.akses_type' => 'update'))->count();
                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Pegawai', 'tbl_akses.akses_type' => 'delete'))->count();
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
                ->rawColumns(['action', 'ket'])->make(true);
        }
    }

    public function proses_tambah(Request $request)
    {

        //insert data
        PegawaiModel::create([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'keterangan'   => $request->ket,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_ubah(Request $request, PegawaiModel $pegawai)
    {
        //update data
        $pegawai->update([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama,
            'jabatan' => $request->jabatan,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'keterangan'   => $request->ket,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_hapus(Request $request, PegawaiModel $pegawai)
    {
        //delete
        $pegawai->delete();

        return response()->json(['success' => 'Berhasil']);
    }
}
