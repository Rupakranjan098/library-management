<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create an Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Seed Categories
        $cat1 = \App\Models\Category::create(['name' => 'Science Fiction', 'description' => 'Sci-Fi books']);
        $cat2 = \App\Models\Category::create(['name' => 'Fantasy', 'description' => 'Fantasy books']);
        $cat3 = \App\Models\Category::create(['name' => 'Non-Fiction', 'description' => 'Real world books']);

        // Seed Authors
        $auth1 = \App\Models\Author::create(['name' => 'Isaac Asimov', 'bio' => 'Sci-Fi master']);
        $auth2 = \App\Models\Author::create(['name' => 'J.R.R. Tolkien', 'bio' => 'Fantasy legend']);
        $auth3 = \App\Models\Author::create(['name' => 'Carl Sagan', 'bio' => 'Astronomer']);

        // Seed Books
        \App\Models\Book::create([
            'title' => 'Foundation',
            'isbn' => '9780553293357',
            'category_id' => $cat1->id,
            'author_id' => $auth1->id,
            'total_copies' => 10,
            'available_copies' => 10,
        ]);

        \App\Models\Book::create([
            'title' => 'The Hobbit',
            'isbn' => '9780547928227',
            'category_id' => $cat2->id,
            'author_id' => $auth2->id,
            'total_copies' => 5,
            'available_copies' => 5,
        ]);

        \App\Models\Book::create([
            'title' => 'Cosmos',
            'isbn' => '9780345331359',
            'category_id' => $cat3->id,
            'author_id' => $auth3->id,
            'total_copies' => 3,
            'available_copies' => 3,
        ]);

        // Seed default Settings
        \App\Models\Setting::set('library_name', 'Laravel Library');
        \App\Models\Setting::set('default_language', 'English');
        \App\Models\Setting::set('date_format', 'May 24, 2024 (MMM DD, YYYY)');
        \App\Models\Setting::set('timezone', '(UTC+05:30) Kolkata, India');
        \App\Models\Setting::set('currency', 'INR (₹)');
        \App\Models\Setting::set('maintenance_mode', '0');
        \App\Models\Setting::set('max_books_per_member', '5');
        \App\Models\Setting::set('borrow_duration', '15');
        \App\Models\Setting::set('renewal_period', '7');
        \App\Models\Setting::set('allow_renewals', '1');
        \App\Models\Setting::set('fine_rate', '5.00');
        \App\Models\Setting::set('max_fine', '100.00');
        \App\Models\Setting::set('grace_period', '0');
        \App\Models\Setting::set('notify_member_registration', '1');
        \App\Models\Setting::set('notify_due_reminder', '1');
        \App\Models\Setting::set('notify_overdue_notices', '1');

        $this->call(DashboardSeeder::class);
    }
}
