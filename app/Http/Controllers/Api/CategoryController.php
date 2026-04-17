<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/categories
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            "status" => true,
            "message" => "List Kategori",
            "data" => $categories
        ], 200);
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            "status" => true,
            "message" => "Kategori berhasil ditambahkan",
            "data" => $category
        ], 201); // 201 artinya Created
    }
}
