<?php

namespace App\Export;

use App\Models\Admin\SpkModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SpkExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
            'Tanggal',
            'Header',
            'Body',
            'Footer',
        ];
    }

    public function collection()
    {

        $all_spk_data = [];

        $data_spk = SpkModel::get();

        foreach ($data_spk as $spk) {

            $all_spk_data[] = [
                $spk->spk_kode,
                $spk->spk_tanggal,
                $spk->spk_header,
                $spk->spk_body,
                $spk->spk_footer,
            ];
        }

        return collect($all_spk_data);
    }


}
