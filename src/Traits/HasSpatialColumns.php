<?php

namespace Mostafaznv\NovaMapField\Traits;

use Illuminate\Support\Arr;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

trait HasSpatialColumns
{
    private array $supportedSpatialTypes = [
        Point::class,
        Polygon::class
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    public function getSpatialColumns(): array
    {
        $columns = [];

        foreach ($this->casts as $key => $cast) {
            if (in_array($cast, $this->supportedSpatialTypes)) {
                $columns[] = $key;
            }
        }

        return $columns;
    }

    public function getRawOriginal($key = null, $default = null)
    {
        $spatialColumns = $this->getSpatialColumns();

        if (is_null($key)) {
            foreach ($spatialColumns as $column) {
                $this->original[$column] = $this->spatialToGeometry($column, $default);
            }
        }
        else if ($this->columnIsSpatial($key, $spatialColumns)) {
            return $this->spatialToGeometry($key, $default);
        }

        return Arr::get($this->original, $key, $default);
    }

    private function spatialToGeometry(string $key, mixed $default = null): ?Geometry
    {
        if (isset($this->original[$key])) {
            return Geometry::fromWkb(Arr::get($this->original, $key, $default));
        }

        return null;
    }

    private function columnIsSpatial(string $column, array $spatialColumns = []): bool
    {
        if (!count($spatialColumns)) {
            $spatialColumns = $this->getSpatialColumns();
        }

        return in_array($column, $spatialColumns);
    }
}
