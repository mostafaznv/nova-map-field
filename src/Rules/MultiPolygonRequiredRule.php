<?php

namespace Mostafaznv\NovaMapField\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class MultiPolygonRequiredRule implements ValidationRule
{
    private string $message = 'The :attribute must be a geometry multi-polygon.';


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value) {
            $polygons = json_decode($value);

            if (is_array($polygons) and count($polygons)) {
                foreach ($polygons as $polygon) {
                    if (is_array($polygon) and count($polygon) >= 3) {
                        foreach ($polygon as $coordinate) {
                            if (!is_array($coordinate) or count($coordinate) !== 2) {
                                $fail(__($this->message));
                            }
                        }
                    }
                    else {
                        $fail(__($this->message));
                    }
                }

                return;
            }

            $fail(__($this->message));
        }
    }
}
