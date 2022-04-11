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
        app('pubsub')->consume('queue_bank_confirmed.' . config('codepix.credential'), [
            'transaction.confirmed.' . config('codepix.credential')
        ], function ($data) use ($transactionService) {
            $objTransaction = $transactionService->getByExternalId($data['external_id'], TransactionService::TRANSACTION_PENDING);
            $transactionService->transactionConfirmed($objTransaction);

            app('pubsub')->publish(['transaction_approved'], [
                'internal_id' => $data['internal_id'],
                'external_id' => $objTransaction->uuid,
                'transaction_id' => $data['external_id'],
            ]);
        });
    }
}
