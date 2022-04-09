<?php

namespace App\Services;

use App\Models\{Account, PixKey};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class PixKeyService
{
    use Traits\ValidateTrait;

    public function __construct(private PixKey $repository)
    {
        //
    }

    public function newPixKey(Account $account, string $kind = null, string $key = null)
    {
        $data = [
            'account_id' => $account->id,
            'kind' => $kind,
            'key' => $key,
        ];

        return $this->repository->create($data);
    }

    public function get($id)
    {
        return $this->repository->where('id', $id)->first();
    }
}
