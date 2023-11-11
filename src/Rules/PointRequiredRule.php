<?php

namespace Mostafaznv\NovaMapField\Rules;

use Illuminate\Contracts\Validation\Rule;


class PointRequiredRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if ($value) {
            $value = json_decode($value);

            return is_object($value) and $value?->latitude and $value?->longitude;
        }

        return true;
    }

    public function message(): string
    {
        return __('The :attribute must be a geometry point.');
    }
}
