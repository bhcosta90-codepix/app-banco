<?php

namespace App\Console\Commands\Transaction;

use App\Services\TransactionService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        app('pubsub')->consume('queue_transaction_approved.' . config('codepix.credential'), [
            'transaction.approved.' . config('codepix.credential')
        ], function ($data) use ($transactionService) {
            $rs = $transactionService->find($data['uuid']);

            if (!empty($rs)) {
                $transactionService->transactionApprroved($rs);
            } else {
                Log::channel('pubsub')->error('transaction ' . $data['uuid'] . ' do not exist');
            }

        });
    }
}
