<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookCopy;

class GenerateBookBarcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:generate-barcodes {--count=100 : Number of unassigned barcode copies to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new unassigned physical barcode copies in bulk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');

        if ($count <= 0) {
            $this->error('Count must be a positive integer.');
            return 1;
        }

        $this->info("Generating {$count} unassigned barcode copies...");

        $barcodesGenerated = 0;

        for ($i = 0; $i < $count; $i++) {
            $copy = BookCopy::create([
                'book_id' => null,
                'status' => 'Unassigned',
                'assigned_at' => null,
            ]);

            $this->line("Generated unassigned barcode: {$copy->barcode_id}");
            $barcodesGenerated++;
        }

        $this->info("Successfully generated {$barcodesGenerated} unassigned barcodes!");
        return 0;
    }
}
