<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;
    protected $table = 'mitra';
    protected $fillable = [
        'sobatid',
        'email',
        'nama',
        'id_kec'
    ];

    protected $primaryKey = 'sobatid';
    public $incrementing = false;
    protected $keyType = 'string';
}
