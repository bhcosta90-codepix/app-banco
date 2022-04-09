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
            'approved_transaction'
        ], function ($data) use ($transactionService) {

            DB::beginTransaction();
            $results = $transactionService->getAllByExternalId($data['uuid']);

            foreach ($results as $rs) {
                try {
                    $transactionService->transactionApprroved($rs);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
        });
    }
}
