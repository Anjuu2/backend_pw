<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deposit extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'deposits';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nomor_akun',
        'nominal_deposit',
        'tanggal_transaksi',
    ];
}
