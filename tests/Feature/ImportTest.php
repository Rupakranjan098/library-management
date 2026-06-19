<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImportTest extends TestCase
{
    // Do not use RefreshDatabase if it resets XAMPP's actual database during developer run, 
    // but in standard testing it's fine. To be safe, we will just use transactions or clean up manually.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed some initial settings or user
        $this->user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        
        // Seed one category and one author
        $this->category = Category::create(['name' => 'Science Fiction']);
        $this->author = Author::create(['name' => 'Isaac Asimov']);
    }

    public function test_books_can_be_imported_via_csv(): void
    {
        $csvContent = "title,isbn,author,category,total_copies\n"
            . "Dune,9780441172719,Frank Herbert,Science Fiction,10\n"
            . "Brave New World,9780060850524,Aldous Huxley,Dystopian,8\n"
            . "Foundation,9780553293357,Isaac Asimov,Science Fiction,12\n" // Existing book/ISBN in seed? No, let's pre-create one to trigger skip.
            . ",9781234567890,Unknown Author,General,3\n"; // Missing title

        // Create an existing book with ISBN 9780553293357
        Book::create([
            'title' => 'Existing Foundation',
            'isbn' => '9780553293357',
            'author_id' => $this->author->id,
            'category_id' => $this->category->id,
            'total_copies' => 1,
            'available_copies' => 1,
        ]);

        $file = UploadedFile::fake()->createWithContent('books.csv', $csvContent);

        $response = $this->actingAs($this->user)
            ->post(route('books.import'), [
                'csv_file' => $file,
            ]);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success');
        
        // Check Dune (new book, new author, existing category Science Fiction)
        $this->assertDatabaseHas('books', [
            'title' => 'Dune',
            'isbn' => '9780441172719',
        ]);
        $this->assertDatabaseHas('authors', [
            'name' => 'Frank Herbert',
        ]);

        // Check Brave New World (new book, new author, new category Dystopian)
        $this->assertDatabaseHas('books', [
            'title' => 'Brave New World',
            'isbn' => '9780060850524',
        ]);
        $this->assertDatabaseHas('categories', [
            'name' => 'Dystopian',
        ]);
        $this->assertDatabaseHas('authors', [
            'name' => 'Aldous Huxley',
        ]);

        // Check skips: 2 successfully imported (Dune, Brave New World), 2 skipped (Foundation duplicate, and empty title)
        $sessionSuccess = session('success');
        $this->assertStringContainsString('Imported 2 books successfully', $sessionSuccess);
        $this->assertStringContainsString('Skipped 2 rows', $sessionSuccess);

        $sessionErrors = session('errors');
        $this->assertNotNull($sessionErrors);
        $this->assertStringContainsString("ISBN '9780553293357' already exists", $sessionErrors->first());
    }

    public function test_authors_can_be_imported_via_csv(): void
    {
        $csvContent = "name,bio\n"
            . "Arthur C. Clarke,Science fiction author who co-wrote 2001.\n"
            . "J.R.R. Tolkien,English writer and philologist.\n"
            . "Isaac Asimov,Famous science fiction writer.\n" // Existing author, should skip
            . ",Biography of missing name.\n"; // Missing name

        $file = UploadedFile::fake()->createWithContent('authors.csv', $csvContent);

        $response = $this->actingAs($this->user)
            ->post(route('authors.import'), [
                'csv_file' => $file,
            ]);

        $response->assertRedirect(route('authors.index'));
        $response->assertSessionHas('success');

        // Check new authors are in database
        $this->assertDatabaseHas('authors', [
            'name' => 'Arthur C. Clarke',
            'bio' => 'Science fiction author who co-wrote 2001.',
        ]);
        $this->assertDatabaseHas('authors', [
            'name' => 'J.R.R. Tolkien',
            'bio' => 'English writer and philologist.',
        ]);

        // Existing author Isaac Asimov bio should NOT be overwritten (since skipped)
        $asimov = Author::where('name', 'Isaac Asimov')->first();
        $this->assertNotEquals('Famous science fiction writer.', $asimov->bio);

        // Check skips: 2 successfully imported, 2 skipped
        $sessionSuccess = session('success');
        $this->assertStringContainsString('Imported 2 authors successfully', $sessionSuccess);
        $this->assertStringContainsString('Skipped 2 rows', $sessionSuccess);

        $sessionErrors = session('errors');
        $this->assertNotNull($sessionErrors);
        $this->assertStringContainsString("Author 'Isaac Asimov' already exists", $sessionErrors->first());
    }

    public function test_categories_can_be_imported_via_csv(): void
    {
        $csvContent = "name,description\n"
            . "Biography,Books about people lives.\n"
            . "History,Books about historical events.\n"
            . "Science Fiction,Futuristic sci-fi concepts.\n" // Existing category, should skip
            . ",Description of missing category name.\n"; // Missing name

        $file = UploadedFile::fake()->createWithContent('categories.csv', $csvContent);

        $response = $this->actingAs($this->user)
            ->post(route('categories.import'), [
                'csv_file' => $file,
            ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');

        // Check new categories are in database
        $this->assertDatabaseHas('categories', [
            'name' => 'Biography',
            'description' => 'Books about people lives.',
        ]);
        $this->assertDatabaseHas('categories', [
            'name' => 'History',
            'description' => 'Books about historical events.',
        ]);

        // Existing category Science Fiction description should NOT be overwritten (since skipped)
        $scifi = Category::where('name', 'Science Fiction')->first();
        $this->assertNotEquals('Futuristic sci-fi concepts.', $scifi->description);

        // Check skips: 2 successfully imported, 2 skipped
        $sessionSuccess = session('success');
        $this->assertStringContainsString('Imported 2 categories successfully', $sessionSuccess);
        $this->assertStringContainsString('Skipped 2 rows', $sessionSuccess);

        $sessionErrors = session('errors');
        $this->assertNotNull($sessionErrors);
        $this->assertStringContainsString("Category 'Science Fiction' already exists", $sessionErrors->first());
    }
}
