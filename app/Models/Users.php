<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\Type\FalseType;
use Illuminate\Foundation\Auth\User;

class Users extends User

{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'nip',
        'email',
        'nama',
        'jabatan',
        'password',
        'admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'admin' => "0",
    ];

    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';
}
