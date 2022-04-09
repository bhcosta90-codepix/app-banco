<?php

namespace App\Console\Commands\Transaction;

use App\Services\TransactionService;
use Illuminate\Console\Command;

class ConfirmedTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:confirmed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TransactionService $transactionService)
    {
        app('pubsub')->consume('queue_confirmed_transaction_' . config('codepix.credential'), [
            'confirm_transaction.' . config('codepix.credential') . '.confirmed'
        ], function ($data) use ($transactionService) {
            $transactionService->transactionConfirmed($data['uuid']);
        });
    }
}
