<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';
    protected $fillable = [
        'id_kec',
        'kecamatan',
    ];

    protected $primaryKey = 'id_kec';
    public $incrementing = false;
    protected $keyType = 'string';
}
