<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ValidateTrait
{
    protected function validate(array $data, array $rule)
    {
        $data = Validator::make($data, $rule);

        if ($data->fails()) {
            throw ValidationException::withMessages($data->errors()->messages());
        }

        return $data->validated();
    }
}
