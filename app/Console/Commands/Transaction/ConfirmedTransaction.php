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
        app('pubsub')->consume('queue_new_transaction_' . config('codepix.credential'), [
            'transaction.confirmed.' . config('codepix.credential')
        ], function ($data) use ($transactionService) {
            $results = $transactionService->getAllByExternalId($data['uuid'], TransactionService::TRANSACTION_PENDING);
            foreach ($results as $objTransaction) {
                $transactionService->transactionConfirmed($objTransaction);
            }

            app('pubsub')->publish(['transaction_approved'], $data);
        });
    }
}
