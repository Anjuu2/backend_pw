<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Pembayaran extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'pembayarans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nominal_angsuran',
        'tanggal_pembayaran',
        'tahapan_angsuran',
        'id_peminjaman',
        'nomor_akun',
    ];
}
