<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilmController extends Controller
{
    // [GET] Mengambil semua data film beserta kategorinya
    public function index()
    {
        // Menggunakan with('category') agar data kategori ikut terbawa (Eager Loading)
        $films = Film::with('category')->get();
        
        return response()->json([
            "status" => true,
            "message" => "Daftar semua film",
            "data" => $films
        ], 200);
    }

    // [POST] Menambahkan film baru
    public function store(Request $request)
    {
        // Cek apakah user yang login adalah admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Hanya Admin yang boleh tambah film'], 403);
        }
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id', // Validasi id kategori harus ada di tabel categories
            'judul'       => 'required',
            'thumbnail'   => 'required',
            'durasi'      => 'required|integer',
            'deskripsi'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 422);
        }

        $film = Film::create($request->all());
        
        // Memuat ulang relasi kategori setelah film dibuat agar responnya lengkap
        $film->load('category');

        return response()->json([
            "status" => true,
            "message" => "Film berhasil ditambahkan",
            "data" => $film
        ], 201);
    }

    // [GET] Mengambil detail satu film berdasarkan ID
    public function show($id)
    {
        // Mencari film beserta kategorinya
        $film = Film::with('category')->find($id);
        
        if (!$film) {
            return response()->json(["status" => false, "message" => "Film tidak ditemukan"], 404);
        }

        return response()->json([
            "status" => true,
            "message" => "Detail film ditemukan",
            "data" => $film
        ], 200);
    }

    // [PUT] Memperbarui data film
    public function update(Request $request, $id)
    {
        $film = Film::find($id);
        if (!$film) {
            return response()->json(["status" => false, "message" => "Film tidak ditemukan"], 404);
        }

        // Tambahkan validasi category_id jika data tersebut ikut diubah
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'durasi'      => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 422);
        }

        $film->update($request->all());
        $film->load('category');

        return response()->json([
            "status" => true,
            "message" => "Data film berhasil diperbarui",
            "data" => $film
        ], 200);
    }

    // [DELETE] Menghapus film
    public function destroy($id)
    {
        $film = Film::find($id);
        if (!$film) {
            return response()->json(["status" => false, "message" => "Film tidak ditemukan"], 404);
        }

        $film->delete();
        return response()->json([
            "status" => true,
            "message" => "Film berhasil dihapus"
        ], 200);
    }
}