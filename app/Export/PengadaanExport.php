<?php

namespace App\Export;

use App\Models\Admin\PbModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PengadaanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
    //     return view('admin.pengadaan.excel', [
    //         'data' => PengadaanModel::get()
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
            'Tanggal',
            'Header',
            'Body',
            'Footer',
        ];
    }

    public function collection()
    {

        $all_pengadaan_data = [];

        $data_pengadaan = PbModel::get();

        foreach ($data_pengadaan as $pengadaan) {

            $all_pengadaan_data[] = [
                $pengadaan->pengadaan_kode,
                $pengadaan->pengadaan_tanggal,
                $pengadaan->pengadaan_header,
                $pengadaan->pengadaan_body,
                $pengadaan->pengadaan_footer,
            ];
        }

        return collect($all_pengadaan_data);
    }


}
