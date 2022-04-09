<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PixKey extends Model
{
    use HasFactory, Traits\Uuid, Traits\ValidateEntity;

    public static function rulesCreated(array $data = []): ?array
    {
        return self::rulesUpdated($data);
    }

    public static function rulesUpdated(array $data = []): ?array
    {
        if (!empty($data['kind']) && !empty($data['key'])) {
            if (DB::table('pix_keys')->select()->where('kind', $data['kind'])->where('key', $data['key'])->count()) {
                throw ValidationException::withMessages([
                    'key' => _('This key cannot be registered'),
                ]);
            }
        }

        $ret = [
            'external_id' => 'required|uuid',
            'kind' => 'required|in:' . implode(',', PixKey::KINDS),
            'account_id' => 'required|exists:accounts,id'
        ];

        if (!empty($data['kind'])) {
            match($data['kind']) {
                'random' => $ret['key'] = 'nullable|uuid',
                default => $ret['key'] = 'required|min:3|max:30',
            };
        }

        return $ret;
    }

    const KINDS = ['email', 'cpf', 'random'];

    public $fillable = [
        'external_id',
        'account_id',
        'kind',
        'key'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
