<?php

namespace App\Console\Commands\Transaction;

use App\Services\TransactionService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CompletedTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:completed';

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
        app('pubsub')->consume('queue_bank_completed.' . config('codepix.credential'), [
            'transaction.completed.' . config('codepix.credential')
        ], function ($data) use ($transactionService) {
            $rs = $transactionService->find($data['uuid']);

            if (!empty($rs)) {
                $transactionService->transactionCompleted($rs);
            } else {
                Log::channel('pubsub')->error('transaction ' . $data['uuid'] . ' do not exist');
            }

        });
    }
}
