<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use App\Models\Deposit;
use App\Models\Peminjaman;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function create()
    {
        $user = Auth::user(); // Mendapatkan data pengguna yang sedang login

        // Ambil data Deposit berdasarkan nomor_akun user login
        $deposits = Deposit::where('nomor_akun', $user->id)->get();

        // Ambil data Peminjaman berdasarkan nomor_akun user login
        $peminjamans = Peminjaman::where('nomor_akun', $user->id)->get();

        // Ambil data Pembayaran berdasarkan nomor_akun user login
        $pembayarans = Pembayaran::where('nomor_akun', $user->id)->get();

        // Proses untuk menyimpan data dari deposit
        foreach ($deposits as $deposit) {
            Riwayat::firstOrCreate(
                [
                    'nomor_akun' => $user->id,
                    'jenis_transaksi' => 'Deposit',
                    'tanggal_transaksi' => $deposit->tanggal_transaksi,
                ],
                [
                    'nominal_transaksi' => $deposit->nominal_deposit,
                ]
            );
        }

        // Proses untuk menyimpan data dari peminjaman
        foreach ($peminjamans as $peminjaman) {
            Riwayat::firstOrCreate(
                [
                    'nomor_akun' => $user->id,
                    'jenis_transaksi' => 'Peminjaman',
                    'tanggal_transaksi' => $peminjaman->tanggal_peminjaman,
                ],
                [
                    'nominal_transaksi' => $peminjaman->nominal_peminjaman,
                ]
            );
        }

        // Proses untuk menyimpan data dari pembayaran
        foreach ($pembayarans as $pembayaran) {
            Riwayat::firstOrCreate(
                [
                    'nomor_akun' => $user->id,
                    'jenis_transaksi' => 'Pembayaran',
                    'tanggal_transaksi' => $pembayaran->tanggal_pembayaran,
                ],
                [
                    'nominal_transaksi' => $pembayaran->nominal_angsuran,
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Riwayat transaksi berhasil dibuat',
        ], 201);
    }

    // READ: Menampilkan semua riwayat transaksi berdasarkan user yang login
    public function index()
    {
        $user = Auth::user(); // Mendapatkan data pengguna yang sedang login

        // Ambil riwayat transaksi berdasarkan nomor_akun user login
        $riwayats = Riwayat::where('nomor_akun', $user->id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Riwayat transaksi berhasil diambil.',
            'data' => $riwayats,
        ], 200);
    }
}
