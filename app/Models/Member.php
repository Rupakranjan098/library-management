<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'membership_expiry'];

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
