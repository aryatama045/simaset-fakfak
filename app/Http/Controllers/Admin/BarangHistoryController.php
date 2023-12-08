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




class BarangHistoryController extends Controller
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
        return view('Admin.Barang.history', $data);
    }


    public function gethistory(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('tbl_barang_log')
            ->leftJoin('tbl_barang', 'tbl_barang_log.barang_id' , '=','tbl_barang.barang_id' )
            ->leftJoin('tbl_user', 'tbl_barang_log.user_id' , '=',  'tbl_user.user_id')
            ->select('tbl_barang_log.keterangan','tbl_barang_log.created_at', 'tbl_user.user_nmlengkap', 'tbl_barang.barang_kode','tbl_barang.barang_nama')
            ->orderBy('created_at', 'DESC')->get();

            // dd($data);

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


}
