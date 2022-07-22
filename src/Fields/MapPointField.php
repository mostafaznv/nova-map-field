<?php

namespace Mostafaznv\NovaMapField\Fields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Mostafaznv\NovaMapField\Rules\PointRequiredRule;
use Mostafaznv\NovaMapField\Traits\WithMapProps;

class MapPointField extends Field
{
    use WithMapProps;

    public $component = 'nova-map-field';

    private string $mapType = 'POINT';

    public function markerIcon(int $icon): self
    {
        if (in_array($icon, [1, 2, 3])) {
            $this->markerIcon = url($this->markerIconPath . "ic-pin-{$icon}.png");
        }

        return $this;
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $result = json_decode($request->{$requestAttribute}, false);

            if ($result?->latitude and $result?->longitude) {
                $model->{$attribute} = new Point($result->latitude, $result->longitude);
            }
        }
    }

    public function resolve($resource, $attribute = null): void
    {
        $this->setRules();

        $attribute = $attribute ?? $this->attribute;

        $this->value = json_encode([
            'latitude'  => $resource->{$attribute}?->latitude,
            'longitude' => $resource->{$attribute}?->longitude
        ]);
    }

    public function setRules(): void
    {
        if ($this->required) {
            $this->rules[] = new PointRequiredRule;
        }
        else if ($this->requiredOnCreate) {
            $this->creationRules[] = new PointRequiredRule;
        }
        else if ($this->requiredOnUpdate) {
            $this->updateRules[] = new PointRequiredRule;
        }
    }
}
