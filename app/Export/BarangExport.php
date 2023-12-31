<?php

namespace App\Export;

use App\Models\Admin\BarangModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BarangExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{

    protected $jenis;
    protected $satuan;

    function __construct($jenis,$satuan) {
        $this->jenis = $jenis;
        $this->satuan = $satuan;
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
            'No.',
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Jenis',
            'Merk',
            'Satuan',
            'Stok',
            'Harga',
            'Crated At',
            'Create By',
            'Updated At',
            'Update By'
        ];
    }

    public function collection()
    {

        $all_barang_data = [];

        if($this->jenis != NULL){
            $data_barang = BarangModel::leftJoin('tbl_kategori as tk', 'tk.kategori_id','=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_jenisbarang as tjb', 'tjb.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_merk as tm', 'tm.merk_id', '=', 'tbl_barang.merk_id')
                ->leftJoin('tbl_satuan as ts', 'ts.satuan_id', '=', 'tbl_barang.satuan_id')
                ->where('tjb.jenisbarang_id', $this->jenis)
                ->select('barang_kode','barang_nama', 'barang_stok', 'barang_harga', 'barang_id', 'tk.kategori_nama',
                        'tjb.jenisbarang_nama', 'tm.merk_nama', 'ts.satuan_nama')
                ->get();
        } else {
            $data_barang = BarangModel::leftJoin('tbl_kategori as tk', 'tk.kategori_id','=', 'tbl_barang.kategori_id')
                ->leftJoin('tbl_jenisbarang as tjb', 'tjb.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                ->leftJoin('tbl_merk as tm', 'tm.merk_id', '=', 'tbl_barang.merk_id')
                ->leftJoin('tbl_satuan as ts', 'ts.satuan_id', '=', 'tbl_barang.satuan_id')
                ->select('barang_kode','barang_nama', 'barang_stok', 'barang_harga', 'barang_id', 'tk.kategori_nama',
                        'tjb.jenisbarang_nama', 'tm.merk_nama', 'ts.satuan_nama')
                ->get();
        }


        

        $no=1;
        foreach ($data_barang as $barang) {

            $all_barang_data[] = [
                $no++,
                $barang->barang_kode,
                $barang->barang_nama,
                $barang->kategori_nama,
                $barang->jenisbarang_nama,
                $barang->merk_nama,
                $barang->satuan_nama,
                $barang->barang_stok,
                $barang->barang_harga,
                '',
                '',
                '',
                ''
            ];
        }

        return collect($all_barang_data);
    }
}
