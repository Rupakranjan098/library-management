<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 10 Members
        $members = [];
        for ($i = 1; $i <= 10; $i++) {
            $members[] = \App\Models\Member::create([
                'name' => 'Member ' . $i,
                'email' => 'member' . $i . '@example.com',
                'phone' => '123456789' . $i,
                'membership_expiry' => now()->addYears(1),
            ]);
        }

        // 2. Ensure we have books
        $books = \App\Models\Book::all();
        if ($books->isEmpty()) {
            return; // no books to borrow
        }

        // 3. Create Borrow Records for the last 15 days to form a nice curve
        $curveData = [25, 48, 64, 53, 38, 28, 42, 60, 78, 68, 50, 38, 22, 33, 48];
        
        foreach ($curveData as $daysAgo => $count) {
            $date = \Carbon\Carbon::now()->subDays(14 - $daysAgo);
            
            for ($j = 0; $j < $count; $j++) {
                $status = 'returned';
                if ($daysAgo > 12) $status = 'borrowed';
                if ($daysAgo == 10) $status = 'overdue';

                \App\Models\BorrowRecord::create([
                    'member_id' => $members[array_rand($members)]->id,
                    'book_id' => $books->random()->id,
                    'borrow_date' => $date->format('Y-m-d'),
                    'due_date' => $date->copy()->addDays(14)->format('Y-m-d'),
                    'return_date' => $status === 'returned' ? $date->copy()->addDays(rand(1, 7))->format('Y-m-d') : null,
                    'status' => $status,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
