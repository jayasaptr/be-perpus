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

    public function categoryId()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    //transactin
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    //ambil name category dari relasi category_id
    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    //image
    public function getImageAttribute($value)
    {
        return url('storage/images/'.$value);
    }
}
