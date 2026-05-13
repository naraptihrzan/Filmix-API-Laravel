<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk manajemen file

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

    // [PUT/POST] Update data profile (Sekarang Mendukung Foto)
    public function update(Request $request)
    {
        $user = $request->user(); // Mengambil user yang sedang login via token

        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8|confirmed',
            'foto'     => 'sometimes|image|mimes:jpg,png,jpeg|max:2048', // Validasi foto: max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 422);
        }

        // --- Logika Update Foto Profil ---
        if ($request->hasFile('foto')) {
            // 1. Hapus foto lama jika ada (opsional, agar storage tidak penuh)
            if ($user->foto) {
                Storage::delete('public/profiles/' . $user->foto);
            }

            // 2. Simpan foto baru
            $file = $request->file('foto');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->storeAs('public/profiles', $nama_file);
            
            // 3. Simpan nama file ke database
            $user->foto = $nama_file;
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