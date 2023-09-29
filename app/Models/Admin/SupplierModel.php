<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;
    protected $table = "tbl_supplier";
    protected $primaryKey = 'supplier_id';
    protected $fillable = [
        'supplier_nama',
        'nama_lengkap',
        'jabatan',
        'no_telp',
        'alamat',
        'supplier_slug',
        'supplier_keterangan'
    ];

}
