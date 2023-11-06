<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangHistoryModel extends Model
{
    use HasFactory;
    protected $table = "tbl_barang_log";
    protected $primaryKey = 'id_barang_log';
    protected $fillable = [
        'barang_id',
        'keterangan',
        'kategori_id',
        'user_id'
    ];
}
