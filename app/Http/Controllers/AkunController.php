<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class AkunController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'npm' => 'required',
            'nomor_rekening' => 'required',
            'nama_akun' => 'required',
            'pin' => 'required',
            'password' => 'required',
        ]);

        try{
            $akun = Akun::create([
                'npm' => $request->npm,
                'nomor_rekening' => $request->nomor_rekening,
                'nama_akun' => $request->nama_akun,
                'saldo' => 0,
                'pin' => $request->pin,
                'password' => Hash::make($request->password),
                'isAdmin' => 0,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json([
                "status" => true,
                "message" => "Register successfull",
                "data" => $akun,
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Something went wrong",
                "data" => $e->getMessage(),
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'npm' => 'required',
            'password' => 'required',
            'pin' => 'required',
        ]);

        $akun = Akun::where('npm', $request->npm)->first();

        if (!$akun) {
            return response()->json([
                "status" => false,
                "message" => "Invalid credentials",
            ], 401);
        }

        if ($akun->pin !== $request->pin) {
            return response()->json([
                "status" => false,
                "message" => "Invalid credentials",
            ], 401);
        }

        if (!Hash::check($request->password, $akun->password)) {
            return response()->json([
                "status" => false,
                "message" => "Invalid credentials",
            ], 401);
        }

        try {
            $token = $akun->createToken('Personal Access Token')->plainTextToken;

            return response()->json([
                "status" => true,
                "detail" => $akun,
                "token" => $token,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Error creating token: " . $e->getMessage(),
            ], 500);
        }
    }
}
