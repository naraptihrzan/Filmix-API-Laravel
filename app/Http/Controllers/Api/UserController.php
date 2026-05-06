<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // [GET] Mengambil data user yang sedang login
    public function me(Request $request)
    {
        return response()->json([
            "status" => true,
            "data" => $request->user()
        ]);
    }

    // [PUT/POST] Update data profile
    public function update(Request $request)
    {
        $user = $request->user(); // Mengambil user yang sedang login via token

        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8|confirmed', // 'confirmed' butuh input password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 422);
        }

        // Update Nama & Email
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;

        // Update Password jika user mengisi kolom password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Profil berhasil diperbarui",
            "data" => $user
        ], 200);
    }
}