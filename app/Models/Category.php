<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name'];

    // Relasi: Satu Kategori memiliki banyak Film
    public function films(): HasMany
    {
        return $this->hasMany(Film::class);
    }
}