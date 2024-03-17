<?php

namespace Mostafaznv\NovaMapField\Fields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\SupportsDependentFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Mostafaznv\NovaMapField\Rules\MultiPolygonRequiredRule;
use Mostafaznv\NovaMapField\Traits\CapturesScreenshot;
use Mostafaznv\NovaMapField\Traits\HandlesValidation;
use Mostafaznv\NovaMapField\Traits\WithMapProps;


class MapMultiPolygonField extends Field
{
    use SupportsDependentFields, WithMapProps, HandlesValidation, CapturesScreenshot;

    public $component = 'nova-map-field';

    private string   $mapType              = 'MULTI_POLYGON';
    protected string $validationRulesClass = MultiPolygonRequiredRule::class;


    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        $this->validate($request, $attribute);

        $mapAttribute = "$requestAttribute.value";

        if ($request->exists($mapAttribute)) {
            $polygons = json_decode($request->{$mapAttribute});

            if (is_array($polygons) and count($polygons)) {
                $multiPolygon = [];

                foreach ($polygons as $polygon) {
                    $points = [];

                    foreach ($polygon as $coordinate) {
                        $points[] = new Point($coordinate[0], $coordinate[1], $this->srid);
                    }

                    $multiPolygon[] = new Polygon(
                        geometries: [
                            new LineString($points)
                        ],
                        srid: $this->srid
                    );
                }

                $model->{$attribute} = new MultiPolygon($multiPolygon, $this->srid);
            }
            else {
                $model->{$attribute} = null;
            }
        }

        $this->storeScreenshot($request, $requestAttribute, $model);
    }

    public function resolve($resource, $attribute = null): void
    {
        $attribute = $attribute ?? $this->attribute;

        $this->value = json_encode($resource->{$attribute}?->getCoordinates() ?? []);
    }
}
