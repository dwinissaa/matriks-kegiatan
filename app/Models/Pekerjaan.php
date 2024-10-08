<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;
    protected $table = 'pekerjaan';
    protected $fillable = [
        // 'id_alokasi',
        'id_keg',
        'id_anggota',
        'uraian_pekerjaan',
        'target',
        'satuan',
        'harga_satuan'
    ];
    protected $primaryKey = 'id_pekerjaan';
    
}
