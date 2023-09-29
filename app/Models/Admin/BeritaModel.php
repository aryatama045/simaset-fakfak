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
        'supplier_id',
        'berita_pic',
        'berita_tanggal',
    ];


}
