<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlokasiKegiatan extends Model
{
    use HasFactory;

    protected $table = 'alokasikegiatan';
    protected $fillable = [
        'id_keg',
        'id_anggota'
    ];

    protected $primaryKey = ['id_keg','id_anggota'];
}