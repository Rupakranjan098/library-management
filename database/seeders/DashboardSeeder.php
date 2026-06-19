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
            $daysSinceBorrow = 14 - $daysAgo; // 14 to 0
            $date = \Carbon\Carbon::now()->subDays($daysSinceBorrow);
            
            for ($j = 0; $j < $count; $j++) {
                $status = 'returned';
                
                // If it is borrowed recently (within last 5 days), it's a standard 'borrowed' record
                if ($daysSinceBorrow <= 4) {
                    // 70% chance of 'borrowed', 30% chance of 'returned' early
                    $status = rand(1, 10) <= 7 ? 'borrowed' : 'returned';
                } else {
                    // For older records:
                    // 15% chance of being overdue, 85% chance of being returned
                    $status = rand(1, 100) <= 15 ? 'overdue' : 'returned';
                }

                $borrowDateStr = $date->format('Y-m-d');
                $dueDateStr = $date->copy()->addDays(5)->format('Y-m-d'); // 5 days borrowing limit
                
                $returnDateStr = null;
                if ($status === 'returned') {
                    // Random days kept: between 1 and 10 days
                    $daysKept = rand(1, 10);
                    $returnDate = $date->copy()->addDays($daysKept);
                    
                    // Don't let return date be in the future
                    if ($returnDate->isAfter(now())) {
                        $returnDate = now();
                    }
                    $returnDateStr = $returnDate->format('Y-m-d');
                }

                \App\Models\BorrowRecord::create([
                    'member_id' => $members[array_rand($members)]->id,
                    'book_id' => $books->random()->id,
                    'borrow_date' => $borrowDateStr,
                    'due_date' => $dueDateStr,
                    'return_date' => $returnDateStr,
                    'status' => $status,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
