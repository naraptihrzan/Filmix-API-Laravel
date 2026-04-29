<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Film extends Model
{
    protected $fillable = ['category_id', 'judul', 'thumbnail', 'durasi', 'deskripsi'];

    // Relasi: Film ini dimiliki oleh sebuah Kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
