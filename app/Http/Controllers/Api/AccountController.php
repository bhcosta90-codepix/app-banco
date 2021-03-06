<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function store(Request $request, AccountService $accountService)
    {
        $obj = $accountService->newAccount($request->name, $request->number, $request->amount);
        return new AccountResource($obj);
    }

    public function show(AccountService $accountService, $uuid)
    {
        $obj = $accountService->find($uuid);
        return (new AccountResource($obj))->additional(['data' => ['amount' => (float) $obj->amount]]);
    }
}
