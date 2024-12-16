<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    // public function create(Request $request)
    // {
    //     $request->validate([
    //         'nomor_akun' => 'required|integer',
    //         'nominal_angsuran' => 'required|numeric|min:0',
    //         'tahapan_angsuran' => 'required|integer|min:1',
    //     ]);

    //     $pembayaran = Pembayaran::create([
    //         'nomor_akun' => $request->nomor_akun,
    //         'nominal_angsuran' => $request->nominal_angsuran,
    //         'tahapan_angsuran' => $request->tahapan_angsuran,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Pembayaran created successfully.',
    //         'data' => $pembayaran,
    //     ], 201);
    // }

    public function Adminindex()
    {
        $pembayarans = Pembayaran::all();

        return response()->json([
            'status' => true,
            'message' => 'Pembayarans retrieved successfully.',
            'data' => $pembayarans,
        ], 200);
    }

    public function Adminshow($id)
    {
        $pembayaran = Pembayaran::find($id);

        if (!$pembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Pembayaran not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran retrieved successfully.',
            'data' => $pembayaran,
        ], 200);
    }

    public function Adminupdate(Request $request, $id)
    {
        $request->validate([
            'nominal_angsuran' => 'required|numeric|min:0',
            'tahapan_angsuran' => 'required|integer|min:1',
        ]);

        $pembayaran = Pembayaran::find($id);

        if (!$pembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Pembayaran not found.',
            ], 404);
        }

        $pembayaran->update([
            'nominal_angsuran' => $request->nominal_angsuran,
            'tahapan_angsuran' => $request->tahapan_angsuran,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran updated successfully.',
            'data' => $pembayaran,
        ], 200);
    }

    public function Admindelete($id)
    {
        $pembayaran = Pembayaran::find($id);

        if (!$pembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Pembayaran not found.',
            ], 404);
        }

        $pembayaran->delete();

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran deleted successfully.',
        ], 200);
    }
    
    ///////////////////////////////////////////// USER /////////////////////////////////////////

    public function create(Request $request)
    {
        $user = Auth::user();

        $validateData = $request->validate([
            'id_peminjaman' => 'required',
            'tanggal_pembayaran' => 'required|date',
        ]);

        $IdPeminjaman = $validateData['id_peminjaman'];
        $peminjaman = Peminjaman::find($IdPeminjaman);

        if (!$peminjaman || $peminjaman->id != $IdPeminjaman) {
            return response()->json([
                'message' => "Peminjaman not found",
            ], 403);
        }

        // Menentukan tahapan_angsuran berikutnya
        $lastPayment = Pembayaran::where('id_peminjaman', $IdPeminjaman)
                                ->orderBy('tahapan_angsuran', 'desc')
                                ->first();

        // Jika belum ada pembayaran sebelumnya, mulai dari tahapan 1
        $nextTahapan = $lastPayment ? $lastPayment->tahapan_angsuran + 1 : 1;

        $nominal_angsuran = $peminjaman->nominal_fix / $peminjaman->masa_peminjaman;

        $pembayaran = Pembayaran::create([
            'nomor_akun' => $user->id,
            'id_peminjaman' => $validateData['id_peminjaman'],
            'nominal_angsuran' => $nominal_angsuran,
            'tahapan_angsuran' => $nextTahapan,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
        ]);

        // Mengurangi nominal peminjaman sesuai dengan nominal angsuran
        $peminjaman->nominal_peminjaman -= $nominal_angsuran;
        $peminjaman->save();

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran created successfully.',
            'data' => $pembayaran,
            'peminjaman' => $peminjaman,
        ], 201);
    }


    public function index()
    {
        $user = Auth::user();

        $pembayarans = Pembayaran::where('nomor_akun', $user->id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Pembayarans retrieved successfully.',
            'data' => $pembayarans,
        ], 200);
    }

    public function show($id)
    {
        $user = Auth::user();

        $pembayaran = Pembayaran::with('akun')->where('id', $id)->where('nomor_akun', $user->id)->first();

        if (!$pembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Pembayaran not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran retrieved successfully.',
            'data' => $pembayaran,
            'user' => $user,
        ], 200);
    }
}
