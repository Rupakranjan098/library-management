<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CirculationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $book;
    protected $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();

        $author = \App\Models\Author::create(['name' => 'Test Author', 'bio' => 'Bio']);
        $category = \App\Models\Category::create(['name' => 'Test Category', 'description' => 'Desc']);

        $this->book = Book::create([
            'title' => 'Test Book Title',
            'isbn' => '1234567890',
            'author_id' => $author->id,
            'category_id' => $category->id,
        ]);

        // Create 2 copies
        for ($i = 0; $i < 2; $i++) {
            BookCopy::create([
                'book_id' => $this->book->id,
                'status' => 'Available',
                'assigned_at' => now(),
            ]);
        }

        $this->member = Member::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'membership_date' => now()->toDateString(),
            'membership_expiry' => now()->addYear()->toDateString(),
            'member_type' => 'student',
        ]);
    }

    public function test_authenticated_user_can_view_circulation_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('circulation.index'));

        $response->assertStatus(200);
        $response->assertViewIs('circulation.index');
    }

    public function test_can_issue_book_successfully()
    {
        $availableCount = $this->book->copies()->where('status', 'Available')->count();
        $this->assertEquals(2, $availableCount);

        $copy = $this->book->copies()->first();

        $response = $this->actingAs($this->admin)
            ->post(route('circulation.issue.post'), [
                'member_id' => $this->member->id,
                'barcode_id' => $copy->barcode_id,
            ]);

        $response->assertRedirect(route('circulation.index'));
        
        $this->assertEquals(1, $this->book->copies()->where('status', 'Available')->count());

        $this->assertDatabaseHas('transactions', [
            'book_id' => $this->book->id,
            'book_copy_id' => $copy->id,
            'member_id' => $this->member->id,
            'status' => 'Issued',
        ]);
    }

    public function test_cannot_issue_book_if_out_of_stock()
    {
        // Mark all copies as Issued
        $this->book->copies()->update(['status' => 'Issued']);

        $copy = $this->book->copies()->first();

        $response = $this->actingAs($this->admin)
            ->from(route('circulation.issue'))
            ->post(route('circulation.issue.post'), [
                'member_id' => $this->member->id,
                'barcode_id' => $copy->barcode_id,
            ]);

        $response->assertRedirect(route('circulation.issue'));
        $response->assertSessionHasErrors('barcode_id');
        
        $this->assertDatabaseMissing('transactions', [
            'member_id' => $this->member->id,
            'book_id' => $this->book->id,
        ]);
    }

    public function test_cannot_issue_same_book_twice_to_same_member_without_returning()
    {
        $copies = $this->book->copies;

        // Issue first copy
        $this->actingAs($this->admin)
            ->post(route('circulation.issue.post'), [
                'member_id' => $this->member->id,
                'barcode_id' => $copies[0]->barcode_id,
            ]);

        // Try to issue second copy of same book
        $response = $this->actingAs($this->admin)
            ->from(route('circulation.issue'))
            ->post(route('circulation.issue.post'), [
                'member_id' => $this->member->id,
                'barcode_id' => $copies[1]->barcode_id,
            ]);

        $response->assertRedirect(route('circulation.issue'));
        $response->assertSessionHasErrors('barcode_id');
    }

    public function test_can_return_book_and_calculate_fine_if_overdue()
    {
        $copy = $this->book->copies()->first();
        $copy->update(['status' => 'Issued']);

        // Issue book manually with past due date
        $transaction = Transaction::create([
            'book_id' => $this->book->id,
            'book_copy_id' => $copy->id,
            'member_id' => $this->member->id,
            'issue_date' => now()->subDays(20),
            'due_date' => now()->subDays(6), // 6 days overdue
            'status' => 'Issued',
            'fine_amount' => 0.00,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('circulation.return.post'), [
                'barcode_id' => $copy->barcode_id,
            ]);

        $response->assertRedirect(route('circulation.index'));

        $copy->refresh();
        $this->assertEquals('Available', $copy->status);

        $transaction->refresh();
        $this->assertEquals('Returned', $transaction->status);
        $this->assertNotNull($transaction->return_date);
        
        // Fine calculation: 6 days overdue * ₹2.00 fine rate = ₹12.00
        $this->assertEquals(12.00, floatval($transaction->fine_amount));
    }

    public function test_can_access_generate_barcodes_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('books.generate-barcodes'));

        $response->assertStatus(200);
        $response->assertViewIs('books.generate-barcodes');
    }

    public function test_can_generate_unassigned_barcodes_successfully()
    {
        $initialCount = BookCopy::count();

        $response = $this->actingAs($this->admin)
            ->post(route('books.generate-barcodes.post'), [
                'quantity' => 15,
            ]);

        // Should redirect to print barcodes route with the generated IDs
        $response->assertRedirect();
        $this->assertTrue(session()->has('success'));

        $finalCount = BookCopy::count();
        $this->assertEquals($initialCount + 15, $finalCount);

        // Verify the created copies are unassigned and have unique barcodes
        $unassignedCopies = BookCopy::whereNull('book_id')->where('status', 'Unassigned')->get();
        $this->assertGreaterThanOrEqual(15, $unassignedCopies->count());

        foreach ($unassignedCopies->take(15) as $copy) {
            $this->assertStringStartsWith('LIB-', $copy->barcode_id);
            $this->assertNull($copy->barcode_printed_at);
        }
    }

    public function test_can_create_new_book_with_unassigned_barcode()
    {
        $unassignedCopy = BookCopy::create([
            'barcode_id' => 'LIB-999001',
            'book_id' => null,
            'status' => 'Unassigned',
        ]);

        $category = \App\Models\Category::first() ?: \App\Models\Category::create(['name' => 'Fiction', 'description' => 'Fiction']);
        $author = \App\Models\Author::first() ?: \App\Models\Author::create(['name' => 'John Author', 'bio' => 'Bio']);

        $response = $this->actingAs($this->admin)
            ->post(route('books.store'), [
                'book_mode' => 'new',
                'barcode_id' => 'LIB-999001',
                'title' => 'Brand New Novel',
                'isbn' => '999888777123',
                'category_id' => $category->id,
                'author_id' => $author->id,
            ]);

        $newBook = Book::where('title', 'Brand New Novel')->first();
        $this->assertNotNull($newBook);

        $response->assertRedirect(route('books.show', $newBook->id));

        $unassignedCopy->refresh();
        $this->assertEquals($newBook->id, $unassignedCopy->book_id);
        $this->assertEquals('Available', $unassignedCopy->status);
        $this->assertNotNull($unassignedCopy->assigned_at);
    }

    public function test_cannot_create_book_with_already_assigned_barcode()
    {
        // Copy already assigned to $this->book (created in setUp)
        $assignedCopy = $this->book->copies()->first();

        $category = \App\Models\Category::first();
        $author = \App\Models\Author::first();

        $response = $this->actingAs($this->admin)
            ->from(route('books.create'))
            ->post(route('books.store'), [
                'book_mode' => 'new',
                'barcode_id' => $assignedCopy->barcode_id,
                'title' => 'Failing Book Title',
                'isbn' => '123123123',
                'category_id' => $category->id,
                'author_id' => $author->id,
            ]);

        $response->assertRedirect(route('books.create'));
        $response->assertSessionHasErrors('barcode_id');
    }

    public function test_can_add_copy_to_existing_book_with_unassigned_barcode()
    {
        $unassignedCopy = BookCopy::create([
            'barcode_id' => 'LIB-999002',
            'book_id' => null,
            'status' => 'Unassigned',
        ]);

        $initialCopiesCount = $this->book->copies()->count();

        $response = $this->actingAs($this->admin)
            ->post(route('books.store'), [
                'book_mode' => 'existing',
                'barcode_id' => 'LIB-999002',
                'book_id' => $this->book->id,
            ]);

        $response->assertRedirect(route('books.show', $this->book->id));

        $this->assertEquals($initialCopiesCount + 1, $this->book->copies()->count());

        $unassignedCopy->refresh();
        $this->assertEquals($this->book->id, $unassignedCopy->book_id);
        $this->assertEquals('Available', $unassignedCopy->status);
        $this->assertNotNull($unassignedCopy->assigned_at);
    }

    public function test_can_link_barcode_via_ajax_successfully()
    {
        $unassignedCopy = BookCopy::create([
            'barcode_id' => 'LIB-888001',
            'book_id' => null,
            'status' => 'Unassigned',
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('books.link-barcode', $this->book->id), [
                'barcode_id' => 'LIB-888001',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'barcode_id' => 'LIB-888001',
        ]);

        $unassignedCopy->refresh();
        $this->assertEquals($this->book->id, $unassignedCopy->book_id);
        $this->assertEquals('Available', $unassignedCopy->status);
    }

    public function test_cannot_link_already_assigned_barcode_via_ajax()
    {
        // $this->book has copies already from setUp
        $assignedCopy = $this->book->copies()->first();

        $response = $this->actingAs($this->admin)
            ->postJson(route('books.link-barcode', $this->book->id), [
                'barcode_id' => $assignedCopy->barcode_id,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_cannot_link_non_existent_barcode_via_ajax()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('books.link-barcode', $this->book->id), [
                'barcode_id' => 'LIB-NONEXISTENT',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_can_access_lookup_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('books.lookup'));

        $response->assertStatus(200);
        $response->assertViewIs('books.lookup');
    }

    public function test_can_lookup_unassigned_barcode()
    {
        $copy = BookCopy::create([
            'barcode_id' => 'LIB-777001',
            'book_id' => null,
            'status' => 'Unassigned',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('books.lookup', ['barcode_id' => 'LIB-777001']));

        $response->assertStatus(200);
        $response->assertViewHas('copy');
        $response->assertSee('Unassigned Barcode Sticker');
    }

    public function test_can_lookup_assigned_barcode_and_see_details()
    {
        $assignedCopy = $this->book->copies()->first();

        $response = $this->actingAs($this->admin)
            ->get(route('books.lookup', ['barcode_id' => $assignedCopy->barcode_id]));

        $response->assertStatus(200);
        $response->assertViewHas('copy');
        $response->assertSee($this->book->title);
        $response->assertSee($assignedCopy->barcode_id);
    }

    public function test_lookup_non_existent_barcode_shows_error()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('books.lookup', ['barcode_id' => 'LIB-NONEXISTENT']));

        $response->assertStatus(200);
        $response->assertSessionHas('error_lookup');
    }
}
