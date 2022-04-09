<?php

namespace App\Services;

use App\Models\{Account, PixKey};

final class PixKeyService
{
    use Traits\ValidateTrait;

    public function __construct(private PixKey $repository, private CodePixService $codePixService)
    {
        //
    }

    public function newPixKey(Account $account, string $kind = null, string $key = null)
    {
        $data = $this->codePixService->newPixKey($account->external_id, $kind, $key);

        return $this->repository->create($data + [
            'account_id' => $account->id,
        ]);
    }

    public function findByKind(string $kind, string $key)
    {
        return $this->repository->where('kind', $kind)->where('key', $key)->first();
    }

    public function get($id)
    {
        return $this->repository->where('id', $id)->first();
    }
}
