<?php

namespace App\Console\Commands\Transaction;

use App\Models\Transaction;
use App\Services\PixKeyService;
use App\Services\TransactionService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NewTransactionConfirmed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:new-transaction';

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
    public function handle(PixKeyService $pixKeyService)
    {
        app('pubsub')->consume('queue_new_transaction_' . config('codepix.credential'), [
            'new_transaction.' . config('codepix.credential') . '.created'
        ], function ($data) use ($pixKeyService) {

            $objPix = $pixKeyService->findByKind($data['pix_key']['kind'], $data['pix_key']['key']);

            $newData = [
                'external_id' => $data['uuid'],
                'account_from_id' => $objPix->account->id,
                'amount' => $data['amount'],
                'moviment' => 'credit',
                'status' => TransactionService::TRANSACTION_CONFIRMED,
                'description' => $data['description'],
                'cancel_description' => $data['cancel_description'],
            ];

            $obj = Transaction::create($newData);

            app('pubsub')->publish([
                'transaction.confirmed'
            ], [
                'internal_id' => $obj->uuid,
                'external_id' => $data['uuid'],
            ]);
        });
    }
}
