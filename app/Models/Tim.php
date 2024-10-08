<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    use HasFactory;
    protected $table = 'tim';
    protected $fillable = [
        'id_tim',
        'tim',
        'nip_ketim'
    ];

    protected $primaryKey = 'id_tim';
    public $incrementing = false;
    protected $keyType = 'string';
}
