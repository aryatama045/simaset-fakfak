<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\WebModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;
use App\Export\LapBarangKeluar;
use DB;
use File;
use Redirect;
use Excel;

class LapBarangKeluarController extends Controller
{
    public function index()
    {
        $data["title"] = "Lap Barang Keluar";
        return view('Admin.Laporan.BarangKeluar.index', $data);
    }

    public function print(Request $request)
    {
        if ($request->tglawal) {
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                            ->leftJoin('tbl_jenisbarang as jb', 'jb.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                            ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                            ->orderBy('bk_id', 'DESC')->get();

            $data['data'] = $data['data']->groupBy([
                'bk_tanggal',
                function ($item) {
                    return $item['jenisbarang_nama'];
                },
            ], $preserveKeys = true);
        } else {
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                            ->leftJoin('tbl_jenisbarang as jb', 'jb.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                            ->orderBy('bk_id', 'DESC')->get();
            $data['data'] = $data['data']->groupBy([
                'bk_tanggal',
                function ($item) {
                    return $item['jenisbarang_nama'];
                },
            ], $preserveKeys = true);
        }

        $data["title"] = "Print Barang Masuk";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;


        return view('Admin.Laporan.BarangKeluar.print', $data);
    }

    public function pdf(Request $request)
    {
        // if ($request->tglawal) {
        //     $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('bk_id', 'DESC')->get();
        // } else {
        //     $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->orderBy('bk_id', 'DESC')->get();
        // }

        if ($request->tglawal) {
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                            ->leftJoin('tbl_jenisbarang as jb', 'jb.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                            ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                            ->orderBy('bk_id', 'DESC')->get();

            $data['data'] = $data['data']->groupBy([
                'bk_tanggal',
                function ($item) {
                    return $item['jenisbarang_nama'];
                },
            ], $preserveKeys = true);
        } else {
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                            ->leftJoin('tbl_jenisbarang as jb', 'jb.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                            ->orderBy('bk_id', 'DESC')->get();
            $data['data'] = $data['data']->groupBy([
                'bk_tanggal',
                function ($item) {
                    return $item['jenisbarang_nama'];
                },
            ], $preserveKeys = true);
        }

        $data["title"] = "PDF Barang Masuk";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        $pdf = PDF::loadView('Admin.Laporan.BarangKeluar.print', $data);

        if($request->tglawal){
            return $pdf->download('lap-bk-'.$request->tglawal.'-'.$request->tglakhir.'.pdf');
        }else{
            return $pdf->download('lap-bk-semua-tanggal.pdf');
        }

    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            if ($request->tglawal == '') {
                $data = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->orderBy('bk_id', 'DESC')->get();
            } else {
                $data = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('bk_id', 'DESC')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    $tgl = $row->bk_tanggal == '' ? '-' : Carbon::parse($row->bk_tanggal)->translatedFormat('d F Y');

                    return $tgl;
                })
                ->addColumn('tujuan', function ($row) {
                    $tujuan = $row->bk_tujuan == '' ? '-' : $row->bk_tujuan;

                    return $tujuan;
                })
                ->addColumn('barang', function ($row) {
                    $barang = $row->barang_id == '' ? '-' : $row->barang_nama;

                    return $barang;
                })
                ->rawColumns(['tgl', 'tujuan', 'barang'])->make(true);
        }
    }

    function export(Request $request)
    {

        $tgl_awal       = '';//$request->tgl_awal;
        $tgl_akhir      = '';//$request->tgl_akhir;
        $type           = 'xlsx';

        try{
            return Excel::download(new LapBarangKeluar($tgl_awal, $tgl_akhir), 'lap_barang_keluar_export-'.date('d-m-y').'.'.$type.'');
        }catch(\Exception $e) {
            return redirect()->back()->with('error_message', 'Operation Failed');
        }
    }

}
