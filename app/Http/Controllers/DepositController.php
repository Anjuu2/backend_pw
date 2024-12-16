<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    // public function Admincreate(Request $request)
    // {
    //     $request->validate([
    //         'nomor_akun' => 'required|exists:akuns,nomor_akun', // Foreign key validation
    //         'nominal_deposit' => 'required|numeric|min:0',
    //         'tanggal_transaksi' => 'required|date',
    //     ]);

    //     $deposit = Deposit::create([
    //         'nomor_akun' => $request->nomor_akun,
    //         'nominal_deposit' => $request->nominal_deposit,
    //         'tanggal_transaksi' => $request->tanggal_transaksi,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Deposit created successfully.',
    //         'data' => $deposit,
    //     ], 201);
    // }

    public function Adminindex()
    {
        $deposits = Deposit::with('akun')->get();

        return response()->json([
            'status' => true,
            'message' => 'Deposits retrieved successfully.',
            'data' => $deposits,
        ], 200);
    }

    public function Adminshow($id)
    {
        $deposit = Deposit::with('akun')->find($id);

        if (!$deposit) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Deposit retrieved successfully.',
            'data' => $deposit,
        ], 200);
    }

    public function Adminupdate(Request $request, $id)
    {
        $request->validate([
            'nominal_deposit' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        $deposit = Deposit::find($id);

        if (!$deposit) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit not found.',
            ], 404);
        }

        $deposit->update([
            'nominal_deposit' => $request->nominal_deposit,
            'tanggal_transaksi' => $request->tanggal_transaksi,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Deposit updated successfully.',
            'data' => $deposit,
        ], 200);
    }

    public function Admindelete($id)
    {
        $deposit = Deposit::find($id);

        if (!$deposit) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit not found.',
            ], 404);
        }

        $deposit->delete();

        return response()->json([
            'status' => true,
            'message' => 'Deposit deleted successfully.',
        ], 200);
    }

    ///////////////////////////////////////////// USER /////////////////////////////////////////
    public function create(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'nominal_deposit' => 'required|numeric|min:0',
        'tanggal_transaksi' => 'required|date',
    ]);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated',
        ], 401);
    }

    $deposit = Deposit::create([
        'nomor_akun' => $user->id,
        'nominal_deposit' => $request->nominal_deposit,
        'tanggal_transaksi' => $request->tanggal_transaksi,
    ]);

    // Mengambil akun yang terkait dengan nomor_akun
    // $akun = Akun::where('nomor_akun', $user->id)->first();

    // if (!$akun) {
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Akun tidak ditemukan.',
    //     ], 404);
    // }

    $user->saldo += $request->nominal_deposit;
    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'Deposit created successfully. Saldo akun berhasil diperbarui.',
        'data' => $deposit,
        'user' => $user,
        'updated_balance' => $user->saldo,
    ], 201);
}


    public function index()
    {
        $user = Auth::user();

        $deposits = Deposit::with('akun')->where('nomor_akun', $user->id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Deposits retrieved successfully.',
            'data' => $deposits,
            'user' => $user,
        ], 200);
    }

    public function show($id)
    {
        $user = Auth::user();

        $deposit = Deposit::with('akun')->where('id', $id)->where('nomor_akun', $user->id)->first();

        if (!$deposit) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Deposit retrieved successfully.',
            'data' => $deposit,
            'user' => $user,
        ], 200);
    }
}
