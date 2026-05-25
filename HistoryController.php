<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    // Ambil history user
    public function index()
    {
        $user = auth()->user();

        $histories = History::where('user_id', $user->id)
            ->with('film')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $histories
        ]);
    }

    // Simpan history
    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id'
        ]);

        $history = History::create([
            'user_id' => auth()->id(),
            'film_id' => $request->film_id,
            'watched_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'History berhasil disimpan',
            'data' => $history
        ]);
    }

    // Menghapus SATU riwayat tontonan berdasarkan ID history
    public function destroy($id)
    {
        $history = History::where('user_id', auth()->id())
                      ->where('id', $id)
                      ->first();
        
        if (!$history) {
            return response()->json([
                'success' => false, 
                'message' => 'History tidak ditemukan atau bukan milik Anda'
            ], 404);
        }

        $history->delete();
        return response()->json([
            'success' => true, 
            'message' => 'History berhasil dihapus'
        ]);
    }

    // Menghapus SEMUA riwayat tontonan user yang sedang login
    public function clearAll()
    {
        History::where('user_id', auth()->id())->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Semua history berhasil dikosongkan'
        ]);
    }
}