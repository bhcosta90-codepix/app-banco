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

    const TRANSACTION_ALL = [
        self::TRANSACTION_PENDING,
        self::TRANSACTION_CONFIRMED,
        self::TRANSACTION_APPROVED,
    ];

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

    public function transactionConfirmed(Transaction $rs)
    {
        $rs->status = TransactionService::TRANSACTION_CONFIRMED;
        $rs->save();

        return $rs;
    }

    public function transactionApprroved(Transaction $rs)
    {
        $rs->status = TransactionService::TRANSACTION_APPROVED;
        $rs->save();

        match($rs->moviment) {
            'credit' => $rs->account_from->increment('amount', $rs->amount),
            'debit' => $rs->account_from->decrement('amount', $rs->amount),
        };

        $rs->account_from->save();

        return $rs;
    }

    public function find(string $uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }

    public function getAllByExternalId(string $uuid, string $status = TransactionService::TRANSACTION_PENDING)
    {
        return $this->repository->where('external_id', $uuid)->where('status', $status)->get();
    }

    public function getByExternalId(string $uuid, string $status = TransactionService::TRANSACTION_PENDING)
    {
        return $this->repository->where('external_id', $uuid)->where('status', $status)->first();
    }
}
