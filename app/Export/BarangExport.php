<?php

namespace App\Export;

use App\Models\Admin\BarangModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;


class BarangExport implements FromCollection,WithHeadings
{
    public function headings():array{
        return[
            'Nama Barang',
            'Stok',
            'Harga',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        $all_barang_data = [];
        $data_barang = BarangModel::select('barang_nama', 'barang_stok', 'barang_harga', 'barang_id')
                    ->get();

        foreach ($data_barang as $barang) {
            // $data= [];
            // foreach ($barang->studentRecords as $record) {
            //     if (moduleStatusCheck('University')) {
            //         $data[] = $record->unFaculty->name.'('. $record->unDepartment->name .'),' ;
            //     } else {
            //         $data[] = $record->class->class_name." (". $record->section->section_name . ")";
            //     }
            // }
            // $classSection = implode(', ', $data);
            $all_barang_data[] = [
                $barang->barang_nama,
                $barang->barang_stok,
                $barang->barang_harga,

            ];
        }

        return collect($all_barang_data);
    }
}
