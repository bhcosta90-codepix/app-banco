<?php

namespace App\Services;

use App\Models\{Account, Bank};
use Exception;
use Illuminate\Support\Facades\DB;

final class AccountService
{
    use Traits\ValidateTrait;

    public function __construct(private Account $repository, private CodePixService $codePixService)
    {
        //
    }

    public function newAccount(string $name = null, float $amount = null)
    {
        DB::beginTransaction();
        try {
            $ret = $this->repository->create([
                'name' => $name,
                'amount' => $amount,
            ]);
            $dataSync = $this->codePixService->newAccount($name, str_pad($ret->id, 6, "0", STR_PAD_LEFT));
            $ret->external_id = $dataSync['external_id'];
            $ret->save();
            DB::commit();
            return $ret;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function find(string $uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }
}
