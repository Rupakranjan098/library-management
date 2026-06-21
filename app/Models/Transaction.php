<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id', 'member_id', 'book_copy_id', 'issue_date', 'due_date', 
        'return_date', 'status', 'fine_amount'
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
        'fine_amount' => 'decimal:2',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class, 'book_copy_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getDaysOverdueAttribute()
    {
        $endDate = $this->return_date ?? now();
        if ($endDate->greaterThan($this->due_date)) {
            return (int) $endDate->diffInDays($this->due_date);
        }
        return 0;
    }

    public function getBorrowDateAttribute()
    {
        return $this->issue_date;
    }

    public function getFineAttribute()
    {
        return $this->fine_amount;
    }
}
