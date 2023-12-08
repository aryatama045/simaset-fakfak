<?php

namespace App\Export;

use App\Models\Admin\BarangmasukModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LapBarangMasuk implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
            'Kode',
            'Pembelian Kode',
            'Barang Kode',
            'Nama Barang',
            'Supplier',
            'Jumlah',
            'Tanggal',
        ];
    }

    public function collection()
    {

        $all_bm_data = [];

        $data_bm = BarangmasukModel::
        leftJoin('tbl_barang','tbl_barangmasuk.barang_kode','=','tbl_barang.barang_kode')
        ->leftJoin('tbl_supplier','tbl_barangmasuk.supplier_id','=','tbl_supplier.supplier_id')
        ->get();

        foreach ($data_bm as $bm) {

            $all_bm_data[] = [
                $bm->bm_kode,
                $bm->pb_kode,
                $bm->barang_kode,
                $bm->barang_nama,
                $bm->supplier_nama,
                $bm->bm_jumlah,
                $bm->bm_tanggal,
            ];
        }

        return collect($all_bm_data);
    }


}
