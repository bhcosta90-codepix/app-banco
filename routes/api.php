<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\PixKeyController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/account', [AccountController::class, 'store']);
Route::post('/pixkey/{account}', [PixKeyController::class, 'store']);
Route::post('/transaction/{kind}/{key}', [TransactionController::class, 'store']);
