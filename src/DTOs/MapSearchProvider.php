<?php

namespace Mostafaznv\NovaMapField\DTOs;

use Mostafaznv\NovaMapField\Utils\MapEnum;

/**
 * @method static self OSM()
 * @method static self MAPQUEST()
 * @method static self PHOTON()
 * @method static self PELIAS()
 * @method static self BING()
 * @method static self OPENCAGE()
 */
class MapSearchProvider extends MapEnum
{
    public function getValue(): int|string
    {
        return strtolower($this->value);
    }
}
