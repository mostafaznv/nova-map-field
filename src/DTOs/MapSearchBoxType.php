<?php

namespace Mostafaznv\NovaMapField\DTOs;

use Mostafaznv\NovaMapField\Utils\MapEnum;

/**
 * @method static self TEXT_FIELD()
 * @method static self BUTTON()
 */
class MapSearchBoxType extends MapEnum
{
    public function getValue(): int|string
    {
        return match ($this->value) {
            'BUTTON' => 'glass-button',
            default  => 'text-input',
        };
    }
}
