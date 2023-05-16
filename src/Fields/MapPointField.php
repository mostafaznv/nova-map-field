<?php

namespace Mostafaznv\NovaMapField\Fields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\SupportsDependentFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Mostafaznv\NovaMapField\DTOs\PointValue;
use Mostafaznv\NovaMapField\Rules\PointRequiredRule;
use Mostafaznv\NovaMapField\Traits\WithMapProps;

class MapPointField extends Field
{
    use SupportsDependentFields, WithMapProps;

    public $component = 'nova-map-field';

    private string      $mapType      = 'POINT';
    private ?PointValue $defaultValue = null;

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
                $model->{$attribute} = new Point($result->latitude, $result->longitude, $this->srid);
            }
        }
    }

    public function resolve($resource, $attribute = null): void
    {
        $this->setRules();

        $attribute = $attribute ?? $this->attribute;

        if (is_null($resource->{$attribute}) and is_null($resource->id) and $this->defaultValue) {
            $this->value = json_encode([
                'latitude'  => $this->defaultValue->getLatitude(),
                'longitude' => $this->defaultValue->getLongitude()
            ]);
        }
        else {
            $this->value = json_encode([
                'latitude'  => $resource->{$attribute}?->latitude,
                'longitude' => $resource->{$attribute}?->longitude
            ]);
        }
    }

    public function default($callback): self
    {
        if ($callback instanceof PointValue) {
            $this->defaultValue = $callback;
        }

        return $this;
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
