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

    public function getDaysOverdueAttribute()
    {
        $dueDate = \Carbon\Carbon::parse($this->due_date)->startOfDay();
        
        if ($this->status === 'returned') {
            $endDate = $this->return_date ? \Carbon\Carbon::parse($this->return_date)->startOfDay() : now()->startOfDay();
        } else {
            $endDate = now()->startOfDay();
        }

        if ($endDate->greaterThan($dueDate)) {
            return (int) abs($endDate->diffInDays($dueDate));
        }

        return 0;
    }

    public function getFineAttribute()
    {
        $fineRate = (float) \App\Models\Setting::get('fine_rate', 5);
        $maxFine = (float) \App\Models\Setting::get('max_fine', 100);
        $gracePeriod = (int) \App\Models\Setting::get('grace_period', 0);

        if ($this->days_overdue > $gracePeriod) {
            $billableDays = $this->days_overdue - $gracePeriod;
            $fine = $billableDays * $fineRate;
            return min($fine, $maxFine);
        }

        return 0;
    }
}
