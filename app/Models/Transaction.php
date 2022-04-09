<?php

namespace App\Models;

use App\Services\PixKeyService;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Transaction extends Model
{
    use HasFactory, Traits\Uuid, Traits\ValidateEntity;

    public $fillable = [
        'external_id',
        'account_from_id',
        'pix_key_kind',
        'pix_key_key',
        'amount',
        'description',
        'status',
        'moviment',
    ];

    public static function rulesCreated(): array|null
    {
        return self::rulesUpdated();
    }

    public static function rulesUpdated(): array|null
    {
        return [
            'external_id' => 'required|uuid',
            'account_from_id' => 'required',
            'pix_key_kind' => 'nullable',
            'pix_key_key' => 'nullable',
            'amount' => 'required|min:0|numeric',
            'moviment' => 'nullable|in:debit,credit',
            'description' => 'nullable|max:120',
            'status' => 'nullable|in:' . implode(',', TransactionService::TRANSACTION_ALL),
        ];
    }

    public function account_from()
    {
        return $this->belongsTo(Account::class);
    }

    public function pixKey()
    {
        return $this->belongsTo(PixKey::class);
    }

    private static function getPixKeyService(): PixKeyService
    {
        return app(PixKeyService::class);
    }
}
