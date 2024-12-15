<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Peminjaman extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'peminjamans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nominal_peminjaman',
        'tanggal_peminjaman',
        'masa_peminjaman',
        'ktm',
        'deskripsi_peminjaman',
        'nomor_akun'
    ];
}
