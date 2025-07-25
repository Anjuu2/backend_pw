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
    public function register(Request $request)
    {
        $request->validate([
            'npm' => 'required|unique:akuns,npm',
            'nomor_rekening' => 'required',
            'nama_akun' => 'required',
            'pin' => 'required|numeric|digits:6',
            'password' => 'required|min:8',
        ]);

        try {
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
                "message" => "Register successful",
                "data" => $akun,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Something went wrong",
                "error" => $e->getMessage(),
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

    public function logout (Request $request)
    {
        if(Auth::check()){
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not logged in'], 401);
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->isAdmin == 0 && $user->id != $id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $akun = Akun::find($id);

        if (!$akun) {
            return response()->json([
                'status' => false,
                'message' => 'Akun not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Akun retrieved successfully',
            'data' => $akun,
        ], 200);
    }

    public function showNamaAkun($id)
    {
        $akun = Akun::select('nama_akun')->where('id', $id)->first();

        if (!$akun) {
            return response()->json([
                'status' => false,
                'message' => 'Akun not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Nama akun retrieved successfully',
            'data' => $akun->nama_akun,
        ], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->isAdmin == 0 && $user->id != $id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $akun = Akun::find($id);

        if (!$akun) {
            return response()->json([
                'status' => false,
                'message' => 'Akun not found',
            ], 404);
        }

        $akun->delete();

        return response()->json([
            'status' => true,
            'message' => 'Akun deleted successfully',
        ], 200);
    }
}
