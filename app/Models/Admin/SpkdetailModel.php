<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkdetailModel extends Model
{
    use HasFactory;
    protected $table = "tbl_spkdetail";
    protected $primaryKey = 'spk_d_id';
    protected $fillable = [
        'spk_id',
        'jenis_pekerjaan',
        'detail_pekerjaan',
    ];

}
