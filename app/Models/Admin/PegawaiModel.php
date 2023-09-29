<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiModel extends Model
{
    use HasFactory;
    protected $table = "tbl_pegawai";
    protected $primaryKey = 'pegawai_id';
    protected $fillable = [
        'nip',
        'nama_lengkap',
        'jabatan',
        'no_telp',
        'alamat',
        'keterangan'
    ];

}
