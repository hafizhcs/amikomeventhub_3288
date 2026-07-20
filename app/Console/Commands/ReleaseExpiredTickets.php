<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredTickets extends Command
{
    protected $signature = 'tickets:release-expired';
    protected $description = 'Release reserved tickets that expired';

    public function handle()
    {
        $expired = Transaction::where('status','pending')
            ->where('expires_at','<',now())
            ->get();

        foreach ($expired as $trx) {
            DB::transaction(function () use ($trx) {
                $trx->event->increment('stock',1);
                $trx->update(['status'=>'cancelled']);
            });
        }

        $this->info('Expired tickets released.');
    }

    
}
