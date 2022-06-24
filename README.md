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
    
    use MatanYadaev\EloquentSpatial\Objects\Point;
    use MatanYadaev\EloquentSpatial\Objects\Polygon;
    
    class Location extends Model
    {
        use HasSpatialColumns;

        protected $casts = [
            'location' => Point::class,
            'area'     => Polygon::class
        ];
    }
    ```

4. ##### Add map fields to resource
    ```php
    <?php
    
    namespace App\Nova\Resources;
    
    use Mostafaznv\NovaMapField\Fields\MapPointField;
    use Mostafaznv\NovaMapField\Fields\MapPolygonField;
    
    class Location extends Resource
    {
        public function fields(Request $request): array
        {
            return [
                MapPointField::make('location'),
                MapPolygonField::make('area')
            ];
        }
    }
    ```

5. ##### Done

----

## Map Field Methods

| method                | Arguments                                | description                                                       |
|-----------------------|------------------------------------------|-------------------------------------------------------------------|
| defaultLatitude       | latitude <br> `float`                    | Specifies latitude of map on page load                            |
| defaultLongitude      | longitude <br> `float`                   | Specifies longitude of map on page load                           |
| zoom                  | zoom <br> `integer`                      | Specifies default map zoom                                        |
| withoutZoomControl    | status <br> `bool` `default: true`       | Specifies whether zoom in/out button should display on map or not |
| withoutZoomSlider     | status <br> `bool` `default: true`       | Specifies whether zoom slider should display on map or not        |
| withFullScreenControl | status <br> `bool` `default: true`       | Specifies whether full screen button should display on map or not |
| mapHeight             | height <br> `integer` `default: 400`     | Map's height                                                      |
| markerIcon            | icon <br> `integer` `available: 1, 2, 3` | Marker icon                                                       |
| required              |                                          | Makes field required                                              |
| requiredOnCreate      | status <br> `bool` `default: true`       | Makes field required on creation                                  |
| requiredOnUpdate      | status <br> `bool` `default: true`       | Makes field required on update                                    |


## Config Properties

| Method                       | Type | Default | Description                                                                |
|------------------------------|------|---------|----------------------------------------------------------------------------|
| default-latitude             | bool | 0       | Default latitude of map                                                    |
| default-longitude            | bool | 0       | Default longitude of map                                                   |
| zoom                         | int  | 12      | Default zoom of map                                                        |
| controls.zoom-control        | bool | true    | Specifies if map should display zoom controls (zoom in/out buttons) or not |
| controls.zoom-slider         | bool | true    | Specifies if map should display zoom slider or not                         |
| controls.full-screen-control | bool | false   | Specifies if map should display full screen button or not                  |
| map-height                   | int  | 400     | Specifies map height                                                       |
| icon                         | int  | 1       | Specifies marker icon. available values: `1, 2, 3`                         |


## Complete Example
```php
<?php

namespace App\Nova\Resources;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use App\Models\Location as Model;
use Mostafaznv\NovaMapField\Fields\MapPointField;
use Mostafaznv\NovaMapField\Fields\MapPolygonField;

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
                ->defaultLatitude(35.6978527)
                ->defaultLongitude(51.4037269)
                ->zoom(14)
                ->withoutZoomControl()
                ->withoutZoomSlider()
                ->withFullScreenControl()
                ->mapHeight(360)
                ->markerIcon(3)
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
