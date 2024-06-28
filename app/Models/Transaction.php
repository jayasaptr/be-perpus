<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'returned_at',
        'status',
    ];

    public function userId()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookId()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
