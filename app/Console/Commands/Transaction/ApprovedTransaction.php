<?php

namespace App\Console\Commands\Transaction;

use App\Services\TransactionService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApprovedTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:approved';

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
        app('pubsub')->consume('queue_transaction_approved' . config('codepix.credential'), [
            'transaction.approved.' . config('codepix.credential')
        ], function ($data) use ($transactionService) {
            if (!empty($data['internal_id'])) {
                $rs = $transactionService->find($data['internal_id']);
            }

            if (!empty($data['external_id'])) {
                $rs = $transactionService->getByExternalId($data['external_id'], TransactionService::TRANSACTION_CONFIRMED);
            }

            if(isset($rs)){
                $transactionService->transactionApprroved($rs);
            }
        });
    }
}
