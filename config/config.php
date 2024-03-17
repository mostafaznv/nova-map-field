<?php

use Mostafaznv\NovaMapField\Enums\MapSearchBoxType;
use Mostafaznv\NovaMapField\Enums\MapSearchProvider;

return [
    /*
    |--------------------------------------------------------------------------
    | Template URL
    |--------------------------------------------------------------------------
    |
    | Must include {x}, {y} or {-y}, and {z} placeholders.
    |
    */

    'template-url' => 'https://{a-c}.tile.openstreetmap.org/{z}/{x}/{y}.png',

    /*
    |--------------------------------------------------------------------------
    | Projection
    |--------------------------------------------------------------------------
    |
    | Using this item, you can specify default projection for maps.
    |
    */

    'projection' => 'EPSG:3857',

    /*
    |--------------------------------------------------------------------------
    | SRID
    |--------------------------------------------------------------------------
    |
    | Using this item, you can specify default for geometry objects.
    |
    */

    'srid' => 0,

    /*
    |--------------------------------------------------------------------------
    | Default Latitude
    |--------------------------------------------------------------------------
    |
    | Using this item, you can specify default latitude for map fields
    |
    */

    'default-latitude' => 0,

    /*
    |--------------------------------------------------------------------------
    | Default Longitude
    |--------------------------------------------------------------------------
    |
    | Using this item, you can specify default longitude for map fields
    |
    */

    'default-longitude' => 0,

    /*
    |--------------------------------------------------------------------------
    | Zoom
    |--------------------------------------------------------------------------
    |
    | Using this item, you can specify default zoom for map fields
    |
    */

    'zoom' => 12,

    'controls' => [
        /*
        |--------------------------------------------------------------------------
        | Zoom Control
        |--------------------------------------------------------------------------
        |
        | Using this item, you can toggle displaying zoom buttons on maps
        |
        */

        'zoom-control' => true,

        /*
        |--------------------------------------------------------------------------
        | Zoom Slider
        |--------------------------------------------------------------------------
        |
        | Using this item, you can toggle displaying zoom slider on maps
        |
        */

        'zoom-slider' => true,

        /*
        |--------------------------------------------------------------------------
        | Full Screen Control
        |--------------------------------------------------------------------------
        |
        | Using this item, you can toggle displaying full-screen button on maps
        |
        */

        'full-screen-control' => false,

        /*
        |--------------------------------------------------------------------------
        | Undo Control
        |--------------------------------------------------------------------------
        |
        | Using this item, you can toggle displaying Undo button on maps (Only for Polygon and MultiPolygon)
        |
        */

        'undo-control' => true,

        /*
        |--------------------------------------------------------------------------
        | Clear Map Control
        |--------------------------------------------------------------------------
        |
        | Using this item, you can toggle displaying Clear button on maps (Only for Polygon and MultiPolygon)
        |
        */

        'clear-map-control' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Map Height
    |--------------------------------------------------------------------------
    |
    | Using this item, you can specify default height of maps
    |
    */

    'map-height' => 400,

    /*
    |--------------------------------------------------------------------------
    | Marker Icon
    |--------------------------------------------------------------------------
    |
    | Using this item, you can change marker icon
    | Available Icons: 1, 2, 3
    |
    */

    'icon' => 1,

    'style' => [
        /*
        |--------------------------------------------------------------------------
        | Stroke Color
        |--------------------------------------------------------------------------
        |
        | Using this property, you can specify stroke color of polygons or other shapes.
        | Either in hexadecimal or as RGBA array.
        | Example: red, #ff0000, rgb(255, 0, 0), rgba(255, 0, 0, 1)
        |
        */

        'stroke-color' => 'red',

        /*
        |--------------------------------------------------------------------------
        | Stroke Width
        |--------------------------------------------------------------------------
        |
        | Width of the stroke (px)
        |
        */

        'stroke-width' => 2,

        /*
        |--------------------------------------------------------------------------
        | Fill Color
        |--------------------------------------------------------------------------
        |
        | Using this property, you can specify filling color of polygons or other shapes.
        | Either in hexadecimal or as RGBA array.
        | Example: red, #ff0000, rgb(255, 0, 0), rgba(255, 0, 0, 1)
        |
        */

        'fill-color' => 'rgba(255, 0, 0, 0.4)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Show Detail Button
    |--------------------------------------------------------------------------
    |
    | Using this item, you can show/hide detail button on detail pages
    |
    */

    'show-detail-button' => true,

    'search' => [
        /*
        |--------------------------------------------------------------------------
        | Enable/Disable Search Box
        |--------------------------------------------------------------------------
        |
        | Using this item, you can toggle displaying search box on maps
        |
        */

        'enable' => true,

        /*
        |--------------------------------------------------------------------------
        | Search Provider
        |--------------------------------------------------------------------------
        |
        | Using this item, you can specify search provider
        | Available providers: osm, mapquest, photon, pelias, bing, opencage
        |
        */

        'provider' => MapSearchProvider::OSM,

        /*
        |--------------------------------------------------------------------------
        | API Key
        |--------------------------------------------------------------------------
        |
        | Using this item, you can specify API Key if required
        |
        */

        'api-key' => '',

        /*
        |--------------------------------------------------------------------------
        | Enable/Disable Autocomplete
        |--------------------------------------------------------------------------
        |
        | Using this item, you can toggle autocomplete feature for search box
        |
        */

        'autocomplete' => false,

        /*
        |--------------------------------------------------------------------------
        | Autocomplete Min Length
        |--------------------------------------------------------------------------
        |
        | The minimum number of characters to trigger search
        |
        */

        'autocomplete-min-length' => 2,

        /*
        |--------------------------------------------------------------------------
        | Autocomplete Timeout
        |--------------------------------------------------------------------------
        |
        | The minimum number of ms to wait before triggering search action
        |
        */

        'autocomplete-timeout' => 200,

        /*
        |--------------------------------------------------------------------------
        | Language
        |--------------------------------------------------------------------------
        |
        | Preferable language
        |
        */

        'language' => 'en-US',

        /*
        |--------------------------------------------------------------------------
        | Placeholder
        |--------------------------------------------------------------------------
        |
        | Placeholder for text input
        |
        */

        'placeholder' => 'Search for an address',

        /*
        |--------------------------------------------------------------------------
        | Search Box Type
        |--------------------------------------------------------------------------
        |
        | Using this item, you can specify type of search box
        | Available types: button, text-field
        |
        */

        'box-type' => MapSearchBoxType::TEXT_FIELD,

        /*
        |--------------------------------------------------------------------------
        | Search Result Limit
        |--------------------------------------------------------------------------
        |
        | Limit of results
        |
        */

        'limit' => 5,

        /*
        |--------------------------------------------------------------------------
        | Search Result Limit
        |--------------------------------------------------------------------------
        |
        | Whether the results keep opened
        |
        */

        'keep-open' => false,
    ],

    'transform' => [
        /*
         |--------------------------------------------------------------------------
         | Enable/Disable Transformation
         |--------------------------------------------------------------------------
         |
         | Using this item, you can toggle transforming polygons maps
         |
         */

        'enable' => true,

        /*
        |--------------------------------------------------------------------------
        | Scale
        |--------------------------------------------------------------------------
        |
        | Using this property, you can toggle scaling features
        |
        */

        'scale' => true,

        /*
        |--------------------------------------------------------------------------
        | Rotate
        |--------------------------------------------------------------------------
        |
        | Using this property, you can toggle rotating features
        |
        */

        'rotate' => true,

        /*
        |--------------------------------------------------------------------------
        | Stretch
        |--------------------------------------------------------------------------
        |
        | Using this property, you can enable/disable stretch option
        |
        */

        'stretch' => true,
    ]
];
