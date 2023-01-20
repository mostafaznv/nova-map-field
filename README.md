# Map Field for Laravel Nova

[![License](https://img.shields.io/github/license/mostafaznv/nova-map-field?style=flat-square)](https://github.com/mostafaznv/nova-map-field/blob/master/LICENSE)
[![Packagist Downloads](https://img.shields.io/packagist/dt/mostafaznv/nova-map-field?style=flat-square&logo=packagist)](https://packagist.org/packages/mostafaznv/nova-map-field)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mostafaznv/nova-map-field.svg?style=flat-square&logo=composer)](https://packagist.org/packages/mostafaznv/nova-map-field)


Using this package, you can use spatial fields in Laravel Nova.


----
🚀 If you find this project interesting, please consider supporting me on the open source journey

[![Donate](https://mostafaznv.github.io/donate/donate.svg)](https://mostafaznv.github.io/donate)

----


## Requirements:

- PHP 8.0.2 or higher
- Laravel 8 or higher


## Installation

1. ##### Install the package via composer:
    ```shell
    composer require mostafaznv/nova-map-field
    ```

2. ##### Publish config and assets:
    ```shell
    php artisan vendor:publish --provider="Mostafaznv\NovaMapField\NovaMapFieldServiceProvider"
    ```

3. ##### Done


## Usage

1. ##### Create table with spatial fields
    ```php
    <?php

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('title', 150);
                $table->point('location')->nullable();
                $table->polygon('area')->nullable();
                $table->multiPolygon('areas')->nullable();
                $table->timestamps();
            });
        }
    };
    ```

2. ##### Add `HasSpatialColumns` trait to model
    ```php
    <?php
    
    namespace App\Models;
    
    use Mostafaznv\NovaMapField\Traits\HasSpatialColumns;
    
    class Location extends Model
    {
        use HasSpatialColumns;
    }
    ```

3. ##### Define spatial columns of model
    ```php
    <?php
   
    namespace App\Models;
   
    use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
    use MatanYadaev\EloquentSpatial\Objects\Point;
    use MatanYadaev\EloquentSpatial\Objects\Polygon;
    
    class Location extends Model
    {
        use HasSpatialColumns;

        protected $casts = [
            'location' => Point::class,
            'area'     => Polygon::class,
            'areas'    => MultiPolygon::class
        ];
    }
    ```

4. ##### Add map fields to resource
    ```php
    <?php
    
    namespace App\Nova\Resources;
    
    use Mostafaznv\NovaMapField\Fields\MapMultiPolygonField;
    use Mostafaznv\NovaMapField\Fields\MapPointField;
    use Mostafaznv\NovaMapField\Fields\MapPolygonField;
    
    class Location extends Resource
    {
        public function fields(Request $request): array
        {
            return [
                MapPointField::make('location'),
                MapPolygonField::make('area'),
                MapMultiPolygonField::make('areas'),
            ];
        }
    }
    ```

5. ##### Done

----

## Map Field Methods

| method                      | Arguments                                | description                                                                    |
|-----------------------------|------------------------------------------|--------------------------------------------------------------------------------|
| templateUrl                 | url <br> `string`                        | Specifies template of map                                                      |
| defaultLatitude             | latitude <br> `float`                    | Specifies latitude of map on page load                                         |
| defaultLongitude            | longitude <br> `float`                   | Specifies longitude of map on page load                                        |
| zoom                        | zoom <br> `integer`                      | Specifies default map zoom                                                     |
| withoutZoomControl          | status <br> `bool` `default: true`       | Specifies whether zoom in/out button should display on map or not              |
| withoutZoomSlider           | status <br> `bool` `default: true`       | Specifies whether zoom slider should display on map or not                     |
| withFullScreenControl       | status <br> `bool` `default: true`       | Specifies whether full screen button should display on map or not              |
| mapHeight                   | height <br> `integer` `default: 400`     | Map's height                                                                   |
| hideDetailButton            | status <br> `bool` `default: true`       | Specifies whether **Show Details** button should appear on detail pages or not |
| markerIcon                  | icon <br> `integer` `available: 1, 2, 3` | Marker icon                                                                    |
| withSearchBox               | `boolean`                                | Specifies whether map has search box or not                                    |
| searchProvider              | provider `MapSearchProvider`             |                                                                                |
| searchProviderApiKey        | apiKey `string`                          | Specifies api key for search provider, if needed                               |
| withAutocompleteSearch      | status `bool` `default: true`            | Specifies whether search results should load immediately or not                |
| searchAutocompleteMinLength | minLength `int`                          | Specifies the minimum number of characters to trigger search action            |
| searchAutocompleteTimeout   | timeout `int`                            | Specifies the minimum number of ms to wait before triggering search action     |
| searchLanguage              | language `string`                        | Specifies preferable language                                                  |
| searchPlaceholder           | placeholder `string`                     |                                                                                |
| searchBoxType               | type `MapSearchBoxType`                  | Using this item, you can specify type of search box (button, or text-field     |
| searchResultLimit           | limit `int`                              | Specifies limit of results                                                     |
| searchResultKeepOpen        | status `boolean`                         | Specifies whether the results keep opened                                      |
| withTransformation          | status `boolean`                         | Specifies whether transport feature should be enable on polygons               |
| transformScale              | status `boolean`                         | Using this method, you can enable/disable scaling features                     |
| transformRotate             | status `boolean`                         | Using this method, you can enable/disable rotating features                    |
| transformStretch            | status `boolean`                         | Using this method, you can enable/disable stretch option                       |
| requiredOnCreate            | status <br> `bool` `default: true`       | Makes field required on creation                                               |
| requiredOnUpdate            | status <br> `bool` `default: true`       | Makes field required on update                                                 |


## Config Properties

| Method                         | Type              | Default                                                | Description                                                                                                              |
|--------------------------------|-------------------|--------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------|
| template-url                   | string            | `https://{a-c}.tile.openstreetmap.org/{z}/{x}/{y}.png` | Default Template URL. Must include `{x}`, `{y}` or `{-y}`, and `{z}` placeholders.                                       |
| default-latitude               | bool              | 0                                                      | Default latitude of map                                                                                                  |
| default-longitude              | bool              | 0                                                      | Default longitude of map                                                                                                 |
| zoom                           | int               | 12                                                     | Default zoom of map                                                                                                      |
| controls.zoom-control          | bool              | true                                                   | Specifies if map should display zoom controls (zoom in/out buttons) or not                                               |
| controls.zoom-slider           | bool              | true                                                   | Specifies if map should display zoom slider or not                                                                       |
| controls.full-screen-control   | bool              | false                                                  | Specifies if map should display full screen button or not                                                                |
| controls.undo-control          | bool              | true                                                   | Specifies if map should display undo control or not (Only for `Polygon` and `MultiPolygon`)                              |
| controls.clear-map-control     | bool              | true                                                   | Specifies if map should display clear map control or not (Only for `Polygon` and `MultiPolygon`)                         |
| map-height                     | int               | 400                                                    | Specifies map height                                                                                                     |
| icon                           | int               | 1                                                      | Specifies marker icon. available values: `1, 2, 3`                                                                       |
| style.stroke-color             | string            | red                                                    | Using this property, you can specify stroke color of polygons or other shapes. Either in hexadecimal or as RGBA array    |
| style.stroke-width             | int               | 2                                                      | Width of the stroke (px)                                                                                                 |
| style.fill-color               | string            | rgba(255, 0, 0, 0.4)                                   | Using this property, you can specify filling color of polygons or other shapes. Either in hexadecimal or as RGBA array.  |
| show-detail-button             | bool              | true                                                   | Specifies whether **Show Details** button should appear on detail pages or not                                           |
| search.enable                  | bool              | true                                                   | Using this item, you can toggle displaying search box on maps                                                            |
| search.provider                | MapSearchProvider | OSM                                                    | Specifies search provider available providers: `OSM, MAPQUEST, PHOTON, PELIAS, BING, OPENCAGE`                           |
| search.api-key                 | string            | ''                                                     | Specifies API key if required                                                                                            |
| search.autocomplete            | bool              | false                                                  | Using this item, you can toggle autocomplete feature for search box                                                      |
| search.autocomplete-min-length | int               | 2                                                      | The minimum number of characters to trigger search                                                                       |
| search.autocomplete-timeout    | int               | 200                                                    | The minimum number of ms to wait before triggering search action                                                         |
| search.language                | string            | en-US                                                  | Specifies preferable language                                                                                            |
| search.placeholder             | string            | Search for an address                                  | Specifies placeholder for text input                                                                                     |
| search.box-type                | MapSearchBoxType  | TEXT_FIELD                                             | Specifies type of search box. available types: `BUTTON, TEXT_FIELD`                                                      |
| search.limit                   | int               | 5                                                      | Specifies limit of results                                                                                               |
| search.keep-open               | bool              | false                                                  | Specifies whether the results keep opened                                                                                |
| transform.enable               | bool              | true                                                   | Using this item, you can toggle transforming polygons maps                                                               |
| transform.scale                | bool              | true                                                   | Using this property, you can toggle scaling features                                                                     |
| transform.scale                | bool              | true                                                   | Using this property, you can toggle rotating features                                                                    |
| transform.stretch              | bool              | true                                                   | Using this property, you can enable/disable stretch option                                                               |

----

## Using Spatial Columns over Application

This package uses [Laravel Eloquent Spatial](https://github.com/MatanYadaev/laravel-eloquent-spatial/) under the hood. to use columns and querying them over the application, please read **Laravel Eloquent Spatial** documentation

----

## Tricks

### Transform Polygons

To transform polygons, You should press `Alt` (Option `⌥`) button and drag that polygon everywhere you want.

### Select Polygons

To select polygons (and modify them), You can press `Shift` button and then click on the polygon. 
By pressing the `Shift` key, drawing mode will be disabled and you can
select every polygon you want.

----

## Complete Example
```php
<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use App\Models\Location as Model;
use Mostafaznv\NovaMapField\DTOs\MapSearchBoxType;
use Mostafaznv\NovaMapField\DTOs\MapSearchProvider;
use Mostafaznv\NovaMapField\Fields\MapPointField;

class Location extends Resource
{
    public static string $model = Model::class;

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),
                
            MapPointField::make(trans('Location'), 'location')
                ->templateUrl('https://{a-c}.tile.openstreetmap.org/{z}/{x}/{y}.png')
                ->defaultLatitude(35.6978527)
                ->defaultLongitude(51.4037269)
                ->zoom(14)
                ->withoutZoomControl()
                ->withoutZoomSlider()
                ->withoutUndoControl()
                ->withFullScreenControl()
                ->mapHeight(360)
                ->hideDetailButton(false)
                ->markerIcon(3)
                ->searchProvider(MapSearchProvider::OSM())
                ->searchProviderApiKey('api-key')
                ->withAutocompleteSearch()
                ->searchAutocompleteMinLength(4)
                ->searchAutocompleteTimeout(500)
                ->searchLanguage('fa-IR')
                ->searchPlaceholder('Placeholder ...')
                ->searchBoxType(MapSearchBoxType::BUTTON())
                ->searchResultLimit(3)
                ->searchResultKeepOpen(true)
                ->withTransformation()
                ->transformScale()
                ->transformRotate()
                ->transformStretch()
                ->required()
                ->requiredOnCreate()
                ->requiredOnUpdate()
                ->stacked(),
        ];
    }
}

```

----
🚀 If you find this project interesting, please consider supporting me on the open source journey

[![Donate](https://mostafaznv.github.io/donate/donate.svg)](https://mostafaznv.github.io/donate)

----



## License

This software is released under [The MIT License (MIT)](LICENSE).



-----
### Sponsors

[![JetBrains Logo (Main) logo](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)](https://jb.gg/OpenSourceSupport)
