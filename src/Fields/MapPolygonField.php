<?php

namespace Mostafaznv\NovaMapField\Fields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Mostafaznv\NovaMapField\Rules\PolygonRequiredRule;
use Mostafaznv\NovaMapField\Traits\WithMapProps;

class MapPolygonField extends Field
{
    use WithMapProps;

    public $component = 'nova-map-field';

    private string $mapType = 'POLYGON';


    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $coordinates = json_decode($request->{$requestAttribute});

            if (is_array($coordinates) and count($coordinates) > 2) {
                $points = [];

                foreach ($coordinates as $coordinate) {
                    $points[] = new Point($coordinate[0], $coordinate[1], $this->srid);
                }

                $model->{$attribute} = new Polygon(
                    geometries: [
                        new LineString($points)
                    ],
                    srid: $this->srid
                );
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
            $this->rules[] = new PolygonRequiredRule;
        }
        else if ($this->requiredOnCreate) {
            $this->creationRules[] = new PolygonRequiredRule;
        }
        else if ($this->requiredOnUpdate) {
            $this->updateRules[] = new PolygonRequiredRule;
        }
    }
}
