<?php

namespace Mostafaznv\NovaMapField\Enums;


enum MapSearchProvider: string
{
    case OSM      = 'osm';
    case MAPQUEST = 'mapquest';
    case PHOTON   = 'photon';
    case PELIAS   = 'pelias';
    case BING     = 'bing';
    case OPENCAGE = 'opencage';
}
