<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'isbn', 'category_id', 'author_id', 
        'total_copies', 'available_copies'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
