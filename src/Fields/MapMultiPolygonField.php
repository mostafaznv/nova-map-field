<?php

namespace Mostafaznv\NovaMapField\Fields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Mostafaznv\NovaMapField\Rules\MultiPolygonRequiredRule;
use Mostafaznv\NovaMapField\Traits\WithMapProps;

class MapMultiPolygonField extends Field
{
    use WithMapProps;

    public $component = 'nova-map-field';

    private string $mapType = 'MULTI_POLYGON';


    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $polygons = json_decode($request->{$requestAttribute});

            if (is_array($polygons) and count($polygons)) {
                $multiPolygon = [];

                foreach ($polygons as $polygon) {
                    $points = [];

                    foreach ($polygon as $coordinate) {
                        $points[] = new Point($coordinate[0], $coordinate[1]);
                    }

                    $multiPolygon[] = new Polygon([
                        new LineString($points)
                    ]);
                }

                $model->{$attribute} = new MultiPolygon($multiPolygon);
            }
            else {
                $model->{$attribute} = null;
            }
        }
    }

    public function resolve($resource, $attribute = null): void
    {
        $this->setRules();

        $attribute = $attribute ?? $this->attribute;

        $this->value = json_encode($resource->{$attribute}?->getCoordinates() ?? []);
    }

    public function setRules(): void
    {
        if ($this->required) {
            $this->rules[] = new MultiPolygonRequiredRule;
        }
        else if ($this->requiredOnCreate) {
            $this->creationRules[] = new MultiPolygonRequiredRule;
        }
        else if ($this->requiredOnUpdate) {
            $this->updateRules[] = new MultiPolygonRequiredRule;
        }
    }
}
