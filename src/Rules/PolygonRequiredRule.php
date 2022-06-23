<?php

namespace Mostafaznv\NovaMapField\Rules;

use Illuminate\Contracts\Validation\Rule;

class PolygonRequiredRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $coordinates = json_decode($value);

        if (is_array($coordinates) and count($coordinates) >= 3) {
            foreach ($coordinates as $coordinate) {
                if (!is_array($coordinate) or count($coordinate) !== 2) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function message(): string
    {
        return __('This field is required');
    }
}
