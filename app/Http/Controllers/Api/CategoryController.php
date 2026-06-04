<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     * GET /api/categories
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $categories = Category::all();
            
            return response()->json([
                "status" => true,
                "message" => "List Kategori",
                "data" => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal mengambil data kategori",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new category
     * POST /api/categories
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $category = Category::create([
                'name' => $request->name
            ]);

            return response()->json([
                "status" => true,
                "message" => "Kategori berhasil ditambahkan",
                "data" => $category
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "status" => false,
                "message" => "Validasi gagal",
                "errors" => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal menambahkan kategori",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
