<?php

namespace App\Export;

use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\BeritaModel;
use App\Models\Admin\BeritadetailModel;
use App\Models\Admin\PbModel;
use App\Models\Admin\PbdetailModel;
use App\Models\Admin\SpkModel;
use App\Models\Admin\SpkdetailModel;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LapStok implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $tgl_awal;
    protected $tgl_akhir;

    function __construct($tgl_awal,$tgl_akhir) {
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },

        ];

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];

        $worksheet->getStyle('')->applyFromArray($styleArray);
    }

    public function headings():array{
        return[
            'Barang Kode',
            'Nama Barang',
            'Stok Awal',
            'Jumlah Masuk',
            'Jumlah Keluar',
            'Total Stok',
            'Harga',
            'Bertambah',
            'Berkurang',
            'Sisa'
        ];
    }

    public function collection()
    {

        $all_data = [];

        $data_habis = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
        ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
        ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
        ->orderBy('barang_id', 'DESC')->get();

        // dd('test');

        foreach ($data_habis as $dh) {

            $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
            ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
            ->where('tbl_barangmasuk.barang_kode', '=', $dh->barang_kode)
            ->sum('tbl_barangmasuk.bm_jumlah');

            $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
            ->where('tbl_barangkeluar.barang_kode', '=', $dh->barang_kode)
            ->sum('tbl_barangkeluar.bk_jumlah');

            $totalstok = $dh->barang_stok + ($jmlmasuk - $jmlkeluar);
            if($totalstok == 0){
                $result = $totalstok;
            }else if($totalstok > 0){
                $result = $totalstok;
            }else{
                $result = '-';
            }

            $bertambah = $jmlmasuk * $dh->barang_harga;
            $bertambah = number_format($bertambah,0,"",'.');
            $berkurang = $jmlkeluar * $dh->barang_harga;
            $berkurang = number_format($berkurang,0,"",'.');

            $harga =  number_format($dh->barang_harga,0,"",'.');
            $sisa = $bertambah - $berkurang;
            $sisa = number_format($sisa,0,"",'.');

            if($totalstok == 0){
                $ket = 'Habis';
            }


            $all_data[] = [
                $dh->barang_kode,
                $dh->barang_nama,
                $dh->barang_stok,
                $jmlmasuk,
                $jmlkeluar,
                $result,
                $harga,
                $bertambah,
                $berkurang,
                $sisa,
                $ket,
            ];
        }

        return collect($all_data);
    }


}
