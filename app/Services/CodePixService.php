<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

final class CodePixService
{
    use Traits\ValidateTrait;

    private string $endpoint;

    private string $credential;

    private string $secret;

    public function __construct()
    {
        $this->endpoint = config('codepix.endpoint');
        $this->credential = config('codepix.credential');
        $this->secret = config('codepix.secret');
    }

    public function newAccount(string $name = null, string $number = null)
    {
        $response = Http::acceptJson()->withHeaders([
            'Authorization' => "Bearer {$this->credential}:{$this->secret}"
        ])->post($this->endpoint . '/api/account', $data = [
            'name' => $name,
            'number' => $number,
        ]);

        return match ($response->status()) {
            401 => abort(401, $response->json('message')),
            422 => throw ValidationException::withMessages($response->json('errors')),
            201 => ['external_id' => $response->json('data.id')] + $data,
            default => dd("codepix", $response->body(), $response->status()),
        };
    }

    public function newPixKey(string $uuid = null, string $kind = null, string $key = null)
    {

        if($kind == 'random' && empty($key)) {
            $key = (string) str()->uuid();
        }

        $response = Http::acceptJson()->withHeaders([
            'Authorization' => "Bearer {$this->credential}:{$this->secret}"
        ])->post($this->endpoint . "/api/pixkey/{$uuid}", $data = [
            'kind' => $kind,
            'key' => $key,
        ]);

        return match ($response->status()) {
            401 => abort(401, $response->json('message')),
            422 => throw ValidationException::withMessages($response->json('errors')),
            201 => ['external_id' => $response->json('data.id')] + $data,
            default => dd("codepix", $response->body(), $response->status()),
        };
    }

    public function newTransaction(Account $account, string $kind = null, string $key = null, float $amount = null)
    {

        if($kind == 'random' && empty($key)) {
            $key = (string) str()->uuid();
        }

        $response = Http::acceptJson()->withHeaders([
            'Authorization' => "Bearer {$this->credential}:{$this->secret}"
        ])->post($this->endpoint . "/api/transaction/{$kind}/{$key}", $data = [
            'account' => $account->external_id,
            'amount' => $amount,
        ]);

        return match ($response->status()) {
            401 => abort(401, $response->json('message')),
            422 => throw ValidationException::withMessages($response->json('errors')),
            201 => ['external_id' => $response->json('data.id')] + $data,
            default => dd("codepix", $response->body(), $response->status()),
        };
    }
}
