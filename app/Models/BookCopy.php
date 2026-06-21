<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class BookCopy extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode_id', 'book_id', 'status', 'barcode_printed_at', 'assigned_at'
    ];

    protected $casts = [
        'barcode_printed_at' => 'datetime',
        'assigned_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($copy) {
            if (empty($copy->barcode_id)) {
                $copy->barcode_id = self::generateUniqueBarcode();
            }
            if (empty($copy->book_id)) {
                $copy->status = 'Unassigned';
            }
        });
    }

    public static function generateUniqueBarcode()
    {
        return DB::transaction(function () {
            $maxBarcode = self::where('barcode_id', 'like', 'LIB-%')
                ->lockForUpdate()
                ->orderBy('barcode_id', 'desc')
                ->value('barcode_id');

            if ($maxBarcode) {
                $number = (int) substr($maxBarcode, 4);
                $nextNumber = $number + 1;
            } else {
                $nextNumber = 1;
            }

            do {
                $barcode = 'LIB-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                $nextNumber++;
            } while (self::where('barcode_id', $barcode)->exists());

            return $barcode;
        });
    }

    public static function findByBarcode($barcode)
    {
        return self::where('barcode_id', $barcode)->first();
    }

    public function getTitleAttribute()
    {
        return $this->book ? $this->book->title : 'Unassigned Barcode';
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'book_copy_id');
    }
}
