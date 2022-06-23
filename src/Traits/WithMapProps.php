<?php

namespace Mostafaznv\NovaMapField\Traits;

trait WithMapProps
{
    private ?float $defaultLatitude;
    private ?float $defaultLongitude;
    private int    $zoom;

    private bool $withZoomControl;
    private bool $withZoomSlider;
    private bool $withFullScreenControl;

    private int $mapHeight;

    private int $markerIcon;

    private bool $required         = false;
    private bool $requiredOnCreate = false;
    private bool $requiredOnUpdate = false;


    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $config = config('nova-map-field');

        $this->defaultLatitude = $config['default-latitude'];
        $this->defaultLongitude = $config['default-longitude'];
        $this->zoom = $config['zoom'];
        $this->withZoomControl = $config['controls']['zoom-control'];
        $this->withZoomSlider = $config['controls']['zoom-slider'];
        $this->withFullScreenControl = $config['controls']['full-screen-control'];
        $this->mapHeight = $config['map-height'];
        $this->markerIcon = $config['icon'];
    }


    public function defaultLatitude(float $latitude): self
    {
        $this->defaultLatitude = $latitude;

        return $this;
    }

    public function defaultLongitude(float $longitude): self
    {
        $this->defaultLongitude = $longitude;

        return $this;
    }

    public function zoom(int $zoom): self
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function withoutZoomControl(bool $status = true): self
    {
        $this->withZoomControl = !$status;

        return $this;
    }

    public function withoutZoomSlider(bool $status = true): self
    {
        $this->withZoomSlider = !$status;

        return $this;
    }

    public function withFullScreenControl(bool $status = true): self
    {
        $this->withFullScreenControl = $status;

        return $this;
    }

    public function mapHeight(int $height): self
    {
        $this->mapHeight = $height;

        return $this;
    }

    public function markerIcon(int $icon): self
    {
        if (in_array($icon, [1, 2, 3])) {
            $this->markerIcon = $icon;
        }

        return $this;
    }

    public function required($callback = true): self
    {
        $this->required = true;
        $this->requiredCallback = $callback;

        return $this;
    }

    public function requiredOnCreate(bool $status = true): self
    {
        $this->requiredOnCreate = $status;

        return $this;
    }

    public function requiredOnUpdate(bool $status = true): self
    {
        $this->requiredOnUpdate = $status;

        return $this;
    }


    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'mapType'               => $this->mapType ?? 'POINT',
            'defaultLatitude'       => $this->defaultLatitude,
            'defaultLongitude'      => $this->defaultLongitude,
            'zoom'                  => $this->zoom,
            'withZoomControl'       => $this->withZoomControl,
            'withZoomSlider'        => $this->withZoomSlider,
            'withFullScreenControl' => $this->withFullScreenControl,
            'mapHeight'             => $this->mapHeight,
            'markerIcon'            => $this->markerIcon,
        ]);
    }
}
