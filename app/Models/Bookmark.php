<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    // Tambahkan ini agar kolom user_id dan film_id bisa diisi secara otomatis
    protected $fillable = [
        'user_id',
        'film_id',
    ];

    // Relasi ke User
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Film
    public function film() 
    {
        return $this->belongsTo(Film::class);
    }
}