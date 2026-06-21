<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'membership_expiry', 'member_type'];

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
