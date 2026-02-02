<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;

class CleanupExpiredTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark expired pending transactions as expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // cari transaksi status
        // * status masih 'pending'
        // * expired_at < waktu sekarang / sudah lewat
        $expiredTransactions = Transaction::where('status', 'pending')
                                            ->where('expired_at', '<=', now())
                                            ->get();
        // update status menjadi 'expired'
        foreach($expiredTransactions as $transaction){
            $transaction->update([
                'status' => 'expire',
                'payment_status' => 'expire'
            ]);
        }

        $this->info("✅ Total : {$expiredTransactions->count()} transactions marked as expired");
    }
}