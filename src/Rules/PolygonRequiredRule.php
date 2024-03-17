<?php

namespace Mostafaznv\NovaMapField\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class PolygonRequiredRule implements ValidationRule
{
    private string $message = 'The :attribute must be a geometry polygon.';


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value) {
            $coordinates = json_decode($value);

            if (is_array($coordinates) and count($coordinates) >= 3) {
                foreach ($coordinates as $coordinate) {
                    if (!is_array($coordinate) or count($coordinate) !== 2) {
                        $fail(__($this->message));
                    }
                }

                return;
            }

            $fail(__($this->message));
        }
    }
}
