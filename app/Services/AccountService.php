<?php

namespace App\Services;

use App\Models\{Account, Bank};

final class AccountService
{
    use Traits\ValidateTrait;

    public function __construct(private Account $repository)
    {
        //
    }

    public function newAccount(string $name = null, string $number = null, float $amount = null)
    {
        $data = $this->validate([
            'name' => $name,
            'number' => $number,
            'amount' => $amount,
        ], Account::rulesCreated());

        return $this->repository->create($data);
    }

    public function find(string $uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }
}
