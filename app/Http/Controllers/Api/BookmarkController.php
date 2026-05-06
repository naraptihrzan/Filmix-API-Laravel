<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Film;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Mengambil bookmark user yang sedang login beserta data film & kategorinya
        $myBookmarks = Bookmark::where('user_id', $user->id)
                        ->with('film.category') 
                        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar Bookmark Berhasil Diambil',
            'data' => $myBookmarks
        ]);
    }
    public function toggleBookmark(Request $request) 
{
    $request->validate(['film_id' => 'required|exists:films,id']);
    
    $user = auth()->user();
    $filmId = $request->film_id;

    $bookmark = \App\Models\Bookmark::where('user_id', $user->id)
                ->where('film_id', $filmId)
                ->first();

    if ($bookmark) {
        $bookmark->delete();
        return response()->json(['message' => 'Film dihapus dari bookmark']);
    }

    \App\Models\Bookmark::create([
        'user_id' => $user->id,
        'film_id' => $filmId
    ]);

    return response()->json(['message' => 'Film berhasil ditambahkan ke bookmark']);
}
}
