<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $admin = Admin::where('username', $username)->first();

        $encryptionKey = 'xxxASDFLKPQO1;;23v'; 
        $encryptedPassword = openssl_encrypt($password, 'AES-128-ECB', $encryptionKey);

        if ($admin && $encryptedPassword == $admin->password) {
            // Generate token using Sanctum
            $token = $admin->createToken('admin-token')->plainTextToken;


            return response()->json([
                'status' => 1,
                'message' => 'Login berhasil',
                'token' => $token, // Kirim token ke frontend
            ]);
        } else {
            throw ValidationException::withMessages([
                'message' => ['Login gagal. Username atau password salah.'],
            ]);
        }

        return 0;
    }

    public function delete(Request $request)
    {
        $username = $request->input('username');

        DB::table('admin')->where('username', $username)->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Admin berhasil dihapus'
        ]);
    }

    public function fetch()
    {
        $admins = DB::table('admin')->select('id', 'username')->get();

        return response()->json([
            'status' => 1,
            'admins' => $admins
        ]);
    }

    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|unique:admin|max:255',
            'password' => 'required|string|max:255',
        ]);

        $encryptionKey = 'xxxASDFLKPQO1;;23v'; 

        $encryptedPassword = openssl_encrypt($validatedData['password'], 'AES-128-ECB', $encryptionKey);

        try {
            DB::table('admin')->insert([
                'username' => $validatedData['username'],
                'password' => $encryptedPassword,
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Admin added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to add admin: ' . $e->getMessage()
            ]);
        }
    }

    public function decryptPassword($encryptedPassword)
    {
        $encryptionKey = 'xxxASDFLKPQO1;;23v';

        $decryptedPassword = openssl_decrypt($encryptedPassword, 'AES-128-ECB', $encryptionKey);

        return $decryptedPassword;
    }
}
