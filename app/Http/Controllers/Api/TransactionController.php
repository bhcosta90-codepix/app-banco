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
        string $kind,
        string $key,
        Request $request,
        CodePixService $codePixService,
        TransactionService $transactionService,
        AccountService $accountService
    ) {
        $objAccount = $accountService->findExternalId($request->account);
        $data = $codePixService->newTransaction($request->account, $kind, $key, $request->amount);
        $obj = $transactionService->newTransaction($objAccount, $kind, $key, $data);

        return new TransactionResource($obj);
    }
}
