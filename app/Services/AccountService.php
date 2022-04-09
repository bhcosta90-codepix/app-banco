<?php

namespace App\Services;

use App\Models\{Account, Bank};

final class AccountService
{
    use Traits\ValidateTrait;

    public function __construct(private Account $repository, private CodePixService $codePixService)
    {
        //
    }

    public function newAccount(string $name = null, string $number = null, float $amount = null)
    {
        $dataAccountSync = $this->codePixService->newAccount($name, $number);
        return $this->repository->create($dataAccountSync);
    }

    public function find(string $uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }

    public function findExternalId(string $uuid)
    {
        return $this->repository->where('external_id', $uuid)->firstOrFail();
    }
}
