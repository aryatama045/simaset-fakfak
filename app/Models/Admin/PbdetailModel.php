<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PbdetailModel extends Model
{
    use HasFactory;
    protected $table = "tbl_pbdetail";
    protected $primaryKey = 'pb_d_id';
    protected $fillable = [
        'pb_id',
        'pb_kode',
        'barang_id',
        'barang_kode',
        'pb_harga',
        'pb_jumlah',
        'satuan',
        'spek',
    ];


}
