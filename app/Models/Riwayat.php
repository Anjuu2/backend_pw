<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Riwayat extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'riwayats';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jenis_transaksi',
        'nominal_transaksi',
        'tanggal_transaksi',
        'nomor_akun',
    ];
}
