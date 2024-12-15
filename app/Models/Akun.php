<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Akun extends Authenticatable
{
    use HasApiTokens, HasFactory;
    
    public $timestamps = false;
    protected $table = 'akuns';
    protected $primaryKey = 'id';

    protected $fillable = [
        'npm',
        'nomor_rekening',
        'nama_akun',
        'saldo',
        'pin',
        'password',
        'isAdmin',
    ];
}
