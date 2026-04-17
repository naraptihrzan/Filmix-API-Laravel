<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilmController extends Controller
{
    // [GET] Mengambil semua data film
    public function index()
    {
        $films = Film::all();
        return response()->json([
            "status" => true,
            "message" => "Daftar semua film",
            "data" => $films
        ], 200);
    }

    // [POST] Menambahkan film baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'thumbnail' => 'required',
            'durasi' => 'required|integer',
            'deskripsi' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 422);
        }

        $film = Film::create($request->all());
        return response()->json([
            "status" => true,
            "message" => "Film berhasil ditambahkan",
            "data" => $film
        ], 201);
    }

    // [GET] Mengambil detail satu film berdasarkan ID
    public function show($id)
    {
        $film = Film::find($id);
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

        $film->update($request->all());
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
