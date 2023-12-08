<?php

namespace App\Export;

use App\Models\Admin\BarangkeluarModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LapBarangKeluar implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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


    // public function view(): View
    // {
    //     return view('admin.spk.excel', [
    //         'data' => SpkModel::get()
    //     ]);
    // }

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
            'Barang Kode',
            'Nama Barang',
            'Tujuan',
            'Jumlah',
            'Tanggal',
        ];
    }

    public function collection()
    {

        $all_bk_data = [];

        $data_bk = BarangkeluarModel::
        leftJoin('tbl_barang','tbl_barangkeluar.barang_kode','=','tbl_barang.barang_kode')
        ->get();

        foreach ($data_bk as $bk) {

            $all_bk_data[] = [
                $bk->bk_kode,
                $bk->barang_kode,
                $bk->barang_nama,
                $bk->bk_tujuan,
                $bk->bk_jumlah,
                $bk->bk_tanggal,
            ];
        }

        return collect($all_bk_data);
    }


}
