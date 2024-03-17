<?php

namespace Mostafaznv\NovaMapField\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class PointRequiredRule implements ValidationRule
{
    private string $message = 'The :attribute must be a geometry point.';


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value) {
            $value = json_decode($value);

            if (is_object($value) and $value?->latitude and $value?->longitude) {
                return;
            }

            $fail(__($this->message));
        }
    }
}
