<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    protected $table = 'kegiatan';
    protected $fillable = [
        'tahun',
        'bulan',
        'kegiatan',
        'subject_meter',
    ];

    protected $primaryKey = 'id_keg';
}
