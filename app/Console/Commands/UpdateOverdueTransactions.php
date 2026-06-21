<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;

class UpdateOverdueTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'circulation:update-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of transactions and copies that are overdue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('status', 'Issued')
            ->where('due_date', '<', now())
            ->get();

        $updatedCount = 0;
        foreach ($transactions as $transaction) {
            $transaction->update(['status' => 'Overdue']);
            if ($transaction->bookCopy) {
                $transaction->bookCopy->update(['status' => 'Overdue']);
            }
            $updatedCount++;
        }

        $this->info("Updated {$updatedCount} transactions and copies to Overdue.");
        return 0;
    }
}
