<?php

namespace Mostafaznv\NovaMapField\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class MultiPointRequiredRule implements ValidationRule
{
    private string $message = 'The :attribute must be a geometry multi-point.';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value) {
            $points = json_decode($value);

            if (is_array($points) and count($points)) {
                foreach ($points as $point) {
                    if (!is_object($point) || !$point?->latitude || !$point?->longitude) {
                        $fail(__($this->message));
                    }
                }

                return;
            }

            $fail(__($this->message));
        }
    }
}
