<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PbModel extends Model
{
    use HasFactory;
    protected $table = "tbl_pb";
    protected $primaryKey = 'pb_id';
    protected $fillable = [
        'pb_kode',
        'spk_kode',
        'supplier_id',
        'pb_pejabat',
        'pb_pic',
        'pb_keterangan',
        'pb_footer',
        'pb_tanggal',
    ];


}
