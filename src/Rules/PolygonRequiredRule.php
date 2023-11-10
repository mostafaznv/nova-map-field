<?php

namespace Mostafaznv\NovaMapField\Rules;

use Illuminate\Contracts\Validation\Rule;

class PolygonRequiredRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if ($value) {
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

        return true;
    }

    public function message(): string
    {
        return __('The :attribute must be a geometry polygon.');
    }
}
