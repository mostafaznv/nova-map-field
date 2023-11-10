<?php

namespace Mostafaznv\NovaMapField\Rules;

use Illuminate\Contracts\Validation\Rule;


class MultiPolygonRequiredRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if ($value) {
            $polygons = json_decode($value);

            if (is_array($polygons) and count($polygons)) {
                foreach ($polygons as $polygon) {
                    if (is_array($polygon) and count($polygon) >= 3) {
                        foreach ($polygon as $coordinate) {
                            if (!is_array($coordinate) or count($coordinate) !== 2) {
                                return false;
                            }
                        }
                    }
                    else {
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
        return __('The :attribute must be a geometry multi-polygon.');
    }
}
