<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlokasiTim extends Model
{
    use HasFactory;
    protected $table = 'alokasitim';
    protected $fillable = [
        'nip'
    ];

    protected $primaryKey = ['id_tim','nip'];
    public $incrementing = false;
    protected $keyType = 'string';
}
