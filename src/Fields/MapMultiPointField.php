<?php

namespace Mostafaznv\NovaMapField\Fields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\SupportsDependentFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Mostafaznv\NovaMapField\Rules\MultiPointRequiredRule;
use Mostafaznv\NovaMapField\Traits\CapturesScreenshot;
use Mostafaznv\NovaMapField\Traits\HandlesValidation;
use Mostafaznv\NovaMapField\Traits\WithMapProps;

class MapMultiPointField extends Field
{
    use SupportsDependentFields, WithMapProps, HandlesValidation, CapturesScreenshot;

    public $component = 'nova-map-field';

    private string      $mapType              = 'MULTI_POINT';
    protected string    $validationRulesClass = MultiPointRequiredRule::class;

    public function markerIcon(int $icon): self
    {
        if (in_array($icon, [1, 2, 3])) {
            $this->markerIcon = url($this->markerIconPath . "ic-pin-{$icon}.png");
        }

        return $this;
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        $this->validate($request, $attribute);

        $mapAttribute = "$requestAttribute.value";

        if ($request->exists($mapAttribute)) {
            $points = json_decode($request->{$mapAttribute});

            if (is_array($points) and count($points)) {
                $multiPoint = [];
                foreach ($points as $point) {
                    if ($point?->latitude and $point?->longitude) {
                        $multiPoint[] = new Point($point->latitude, $point->longitude, $this->srid);
                    }
                }

                $model->{$attribute} = new MultiPoint($multiPoint, $this->srid);
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
