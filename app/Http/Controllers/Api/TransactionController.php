<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Services\AccountService;
use App\Services\CodePixService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(
        string $account,
        Request $request,
        CodePixService $codePixService,
        TransactionService $transactionService,
        AccountService $accountService
    ) {
        $objAccount = $accountService->find($account);
        $data = $codePixService->newTransaction($objAccount, $request->kind, $request->key, $request->amount, $request->description);
        $obj = $transactionService->newTransaction($objAccount, $request->kind, $request->key, $request->description, $data);

        return new TransactionResource($obj);
    }

    public function show(TransactionService $transactionService, string $uuid)
    {
        return new TransactionResource($transactionService->find($uuid));
    }
}
