<?php

namespace App\Models\Traits;

use App\Services\Traits\ValidateTrait;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait ValidateEntity
{
    use ValidateTrait;

    protected static array $dataModel = [];

    public static abstract function rulesCreated(): array|null;

    public static abstract function rulesUpdated(): array|null;

    public function validateCreated(): void {
        //
    }

    public function validateUpdated(): void {
        //
    }

    public static function bootValidateEntity()
    {
        static::creating(function (Model $obj) {
            if (count($rules = $obj->rulesCreated($obj->toArray()))) {
                $obj->validaFillableWithRules($rules);
                $obj->validate($obj->toArray(), $rules);
                $obj->validateCreated();
            }
        });

        static::updating(function (Model $obj) {
            if (count($rules = $obj->rulesUpdated($obj->toArray()))) {
                $obj->validaFillableWithRules($rules);
                $obj->validate($obj->toArray(), $rules);
                $obj->validateUpdated();
            }
        });
    }

    private function validaFillableWithRules(array $rules)
    {
        $keys = array_keys($rules);
        $fill = $this->getFillable();

        rsort($keys);
        rsort($fill);

        if ($fill !== $keys) {
            Log::error("validaFillableWithRules");
            Log::error(["keys" => $keys, "fill" => $fill]);
            throw new Exception('Fillable do not equal rules');
        }
    }
}
