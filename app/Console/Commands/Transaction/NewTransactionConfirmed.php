<?php

namespace App\Console\Commands\Transaction;

use App\Models\Transaction;
use App\Services\PixKeyService;
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
    protected $signature = 'transaction:new-transaction-confirmed';

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
            'new_transaction.' . config('codepix.credential') . '.confirmed'
        ], function ($data) use ($pixKeyService) {

            $objPix = $pixKeyService->findByKind($data['pix_key']['kind'], $data['pix_key']['key']);

            $newData = [
                'external_id' => $data['uuid'],
                'account_from_id' => $objPix->account->id,
                'amount' => $data['amount'],
                'moviment' => 'credit',
                'status' => $data['status'],
                'description' => $data['description'],
                'cancel_description' => $data['cancel_description'],
            ];

            $objTransaction = Transaction::create($newData);

            app('pubsub')->publish(['confirm_transaction'], [
                'external_id' => $data['uuid'],
                'internal_id' => $objTransaction->uuid
            ]);
        });
    }
}
