<?php

namespace Mostafaznv\NovaMapField\Rules;

use Illuminate\Contracts\Validation\Rule;

class PointRequiredRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $value = json_decode($value);

        return $value and $value?->latitude and $value?->longitude;
    }

    public function message(): string
    {
        return __('This field is required');
    }
}
