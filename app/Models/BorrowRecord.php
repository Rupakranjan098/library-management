<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id', 'member_id', 'borrow_date', 
        'due_date', 'return_date', 'status'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public static function updateOverdueRecords()
    {
        return self::where('status', 'borrowed')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);
    }

    public function getFineAttribute()
    {
        $dueDate = \Carbon\Carbon::parse($this->due_date);
        
        if ($this->status === 'returned') {
            $endDate = $this->return_date ? \Carbon\Carbon::parse($this->return_date) : now();
        } else {
            $endDate = now();
        }

        if ($endDate->greaterThan($dueDate)) {
            $daysOverdue = $endDate->diffInDays($dueDate);
            return $daysOverdue * 5; // 5 RS per day
        }

        return 0;
    }
}
