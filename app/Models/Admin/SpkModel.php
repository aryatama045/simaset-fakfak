<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkModel extends Model
{
    use HasFactory;
    protected $table = "tbl_spk";
    protected $primaryKey = 'spk_id';
    protected $fillable = [
        'spk_kode',
        'spk_jenis',
        'spk_rekening',
        'spk_pic',
        'spk_tanggal',
        'spk_pihak_1',
        'spk_pihak_2',
        'spk_mengetahui',
        'create_by',
        'edited_by'
    ];


}
