<?php

namespace App\Models\Traits;

trait Uuid
{
    public static function bootUuid()
    {
        static::creating(function ($obj) {
            $obj->uuid = $obj->uuid ?: str()->uuid();
        });
    }
}
