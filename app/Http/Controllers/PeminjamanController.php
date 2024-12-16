<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PeminjamanController extends Controller
{
    // public function create(Request $request)
    // {
    //     $request->validate([
    //         'nomor_akun' => 'required|integer',
    //         'nominal_peminjaman' => 'required|numeric|min:0',
    //         'tanggal_peminjaman' => 'required|date',
    //         'masa_peminjaman' => 'required|integer|min:1',
    //         'ktm' => 'required|string',
    //         'deskripsi_peminjaman' => 'required|string',
    //     ]);

    //     $peminjaman = Peminjaman::create($request->all());

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Peminjaman created successfully.',
    //         'data' => $peminjaman,
    //     ], 201);
    // }

    public function Adminindex()
    {
        $peminjaman = Peminjaman::where('nominal_peminjaman', '>', 0)->get();

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman retrieved successfully.',
            'data' => $peminjaman,
        ], 200);
    }

    public function Adminshow($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'message' => 'Peminjaman not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman retrieved successfully.',
            'data' => $peminjaman,
        ], 200);
    }

    public function Adminupdate(Request $request, $id)
    {
        $request->validate([
            'nominal_peminjaman' => 'required|numeric|min:0',
            'tanggal_peminjaman' => 'required|date',
            'masa_peminjaman' => 'required|integer|min:1',
            'ktm' => 'required|string',
            'deskripsi_peminjaman' => 'required|string',
        ]);

        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'message' => 'Peminjaman not found.',
            ], 404);
        }

        $peminjaman->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman updated successfully.',
            'data' => $peminjaman,
        ], 200);
    }

    public function Admindelete($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'message' => 'Peminjaman not found.',
            ], 404);
        }

        $peminjaman->delete();

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman deleted successfully.',
        ], 200);
    }

    ///////////////////////////////////////////// USER /////////////////////////////////////////

    public function create(Request $request)
    {
        // Ambil ID user yang sedang login
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'nominal_peminjaman' => 'required|numeric|min:0',
            'tanggal_peminjaman' => 'required|date',
            'masa_peminjaman' => 'required|integer|min:1',
            'ktm' => 'required|string',
            'deskripsi_peminjaman' => 'required|string',
        ]);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }

        // Buat peminjaman baru
        $peminjaman = Peminjaman::create([
            'nomor_akun' => $user->id,
            'nominal_peminjaman' => $request->nominal_peminjaman,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'masa_peminjaman' => $request->masa_peminjaman,
            'ktm' => $request->ktm,
            'deskripsi_peminjaman' => $request->deskripsi_peminjaman,
            'nominal_fix' => $request->nominal_peminjaman,
        ]);

        // Kembalikan respons JSON
        return response()->json([
            'status' => true,
            'message' => 'Peminjaman created successfully.',
            'data' => $peminjaman,
        ], 201);
    }


    public function index()
    {
        $user = Auth::user();

        $peminjaman = Peminjaman::where('nomor_akun', $user->id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Peminjamans retrieved successfully.',
            'data' => $peminjaman,
        ], 200);
    }

    public function show($id)
    {
        $user = Auth::user();

        $peminjaman = Peminjaman::with('akun')->where('id', $id)->where('nomor_akun', $user->id)->first();

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'message' => 'Peminjaman not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman retrieved successfully.',
            'data' => $peminjaman,
            'user' => $user,
        ], 200);
    }
}
