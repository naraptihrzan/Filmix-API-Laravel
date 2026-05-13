<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilmController extends Controller
{
    // [GET] Mengambil data film dengan Fitur Search & Filter Canggih
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $query = Film::with('category');

        // 1. Filter berdasarkan Category ID (Jika user pilih kategori tertentu di UI)
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // 2. Fitur Search: Mencari di Judul, Deskripsi, atau Nama Kategori
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', "%$search%")
                  ->orWhere('deskripsi', 'LIKE', "%$search%")
                  // Mencari berdasarkan nama kategori (Relasi)
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('name', 'LIKE', "%$search%");
                  });
            });
        }

        // Urutkan dari yang terbaru (Opsional, biar lebih rapi)
        $films = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            "status" => true,
            "message" => "Daftar film berhasil dimuat",
            "count" => $films->count(),
            "data" => $films
        ], 200);
    }

    // [POST] Menambahkan film baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'judul'       => 'required|string',
            'thumbnail'   => 'required|string',
            'video_url'   => 'required|url',
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
            "message" => "Film berhasil ditambahkan",
            "data" => $film
        ], 201);
    }

    // [GET] Detail satu film
    public function show($id)
    {
        $film = Film::with('category')->find($id);
        
        if (!$film) {
            return response()->json(["status" => false, "message" => "Film tidak ditemukan"], 404);
        }

        return response()->json([
            "status" => true,
            "message" => "Detail ditemukan",
            "data" => $film
        ], 200);
    }

    // [PUT] Update data film
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
            "message" => "Film diperbarui",
            "data" => $film
        ], 200);
    }

    // [DELETE] Hapus film
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