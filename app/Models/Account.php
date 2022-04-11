<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, Traits\Uuid, Traits\ValidateEntity;

    public $fillable = [
        'name',
        'amount',
    ];

    public static function booted()
    {
        parent::creating(function($obj){
            $obj->amount = $obj->amount ?: 0;
        });
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public static function rulesCreated(): ?array
    {
        return self::rulesUpdated();
    }

    public static function rulesUpdated(): ?array
    {
        return [
            'name' => 'required|min:3|max:120',
            'amount' => 'nullable|numeric',
        ];
    }

    public function pixKeys()
    {
        return $this->hasMany(PixKey::class);
    }
}
