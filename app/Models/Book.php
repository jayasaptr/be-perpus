<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'image',
        'stock',
        'publication_year',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //transactin
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
