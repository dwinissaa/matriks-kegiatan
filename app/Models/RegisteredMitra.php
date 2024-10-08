<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredMitra extends Model
{
    use HasFactory;
    protected $table = 'registeredmitra';
    protected $fillable = [
        'sobatid',
        'tahun',
    ];

    protected $primaryKey = ['sobatid','tahun'];
    public $incrementing = false;
    protected $keyType = 'string';
}
