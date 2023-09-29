<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritadetailModel extends Model
{
    use HasFactory;
    protected $table = "tbl_beritadetail";
    protected $primaryKey = 'berita_d_id';
    protected $fillable = [
        'berita_id',
        'jenis_pekerjaan',
        'detail_pekerjaan',
    ];

}
