<?php

namespace App\Models;

use App\Services\PixKeyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Transaction extends Model
{
    use HasFactory, Traits\ValidateEntity;

    public $fillable = [
        'uuid',
        'account_from_id',
        'pix_key_id',
        'amount',
        'description',
    ];

    public static function rulesCreated(): array|null
    {
        return self::rulesUpdated();
    }

    public static function rulesUpdated(): array|null
    {
        return [
            'uuid' => 'required|uuid',
            'account_from_id' => 'required',
            'pix_key_id' => 'required',
            'amount' => 'required|min:0|numeric',
            'description' => 'nullable|max:120'
        ];
    }

    public function validateCreated()
    {
        if ($this->pixKey->account_id == $this->account_from_id) {
            throw ValidationException::withMessages([
                'pix_key_id' => "Você não pode transferir para a mesma conta bancária",
            ]);
        }
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
