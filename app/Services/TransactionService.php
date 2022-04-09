<?php

namespace App\Services;

use App\Models\{Account, PixKey, Transaction};
use Exception;

final class TransactionService
{
    use Traits\ValidateTrait;

    const TRANSACTION_PENDING = 'pending';
    const TRANSACTION_CONFIRMED = 'confirmed';
    const TRANSACTION_APPROVED = 'approved';

    public function __construct(private Transaction $repository)
    {
        //
    }

    public function newTransaction(Account $account, $kind, $key, array $data = [])
    {
        return $this->repository->create([
            'account_from_id' => $account->id,
            'pix_key_kind' => $kind,
            'pix_key_key' => $key,
        ] + $data);
    }

    public function transactionConfirmed(string $uuid)
    {
        throw new Exception('do not implemented ' . __FUNCTION__);
    }

    public function transactionApprroved(string $uuid)
    {
        throw new Exception('do not implemented ' . __FUNCTION__);
    }

    public function find(string $uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }
}
