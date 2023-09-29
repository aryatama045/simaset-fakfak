<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaModel extends Model
{
    use HasFactory;
    protected $table = "tbl_berita";
    protected $primaryKey = 'berita_id';
    protected $fillable = [
        'berita_kode',
        'berita_pihak_1',
        'berita_pihak_2',
        'berita_pic',
        'berita_tanggal',
    ];


}
