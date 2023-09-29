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
        'barang_kode',
        'supplier_id',
        'pb_',
        'pb_tanggal',
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Models\Admin\SupplierModel');
    }
}
