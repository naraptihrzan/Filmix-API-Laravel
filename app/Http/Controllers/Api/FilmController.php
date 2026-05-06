<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilmController extends Controller
{
    // [GET] Mengambil semua data film (Sekarang Mendukung Fitur Search)
    public function index(Request $request)
    {
        $query = Film::with('category');

        // Fitur Search: Cek apakah ada parameter 'search' di URL
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('judul', 'LIKE', "%$searchTerm%");
        }

        $films = $query->get();
        
        return response()->json([
            "status" => true,
            "message" => "Daftar film berhasil dimuat",
            "count" => $films->count(),
            "data" => $films
        ], 200);
    }

    // [POST] Menambahkan film baru (Ditambah video_url & tahun_rilis)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'judul'       => 'required|string',
            'thumbnail'   => 'required|string',
            'video_url'   => 'required|url', // Validasi URL Video (Penting untuk Streaming)
            'durasi'      => 'required|integer',
            'deskripsi'   => 'required',
            'tahun_rilis' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 422);
        }

        $film = Film::create($request->all());
        $film->load('category');

        return response()->json([
            "status" => true,
            "message" => "Film berhasil ditambahkan ke database",
            "data" => $film
        ], 201);
    }

    // [GET] Mengambil detail satu film berdasarkan ID
    public function show($id)
    {
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

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'video_url'   => 'sometimes|url',
            'durasi'      => 'sometimes|integer',
            'tahun_rilis' => 'sometimes|integer'
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
            "message" => "Film berhasil dihapus dari sistem"
        ], 200);
    }
}