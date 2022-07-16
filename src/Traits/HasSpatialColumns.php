<?php

namespace Mostafaznv\NovaMapField\Traits;

use Illuminate\Support\Arr;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

/**
 * @method static SpatialBuilder query()
 */
trait HasSpatialColumns
{
    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    public function originalIsEquivalent($key)
    {
        if (!array_key_exists($key, $this->original)) {
            return false;
        }

        if (parent::originalIsEquivalent($key)) {
            return true;
        }

        if ($this->isClassCastable($key) && is_subclass_of($this->getCasts()[$key], Geometry::class)) {
            $originalGeometry = $this->getOriginal($key);
            $attributeWkbOrWkt = Arr::get($this->attributes, $key);

            return $this->castAttribute($key, $attributeWkbOrWkt) == $originalGeometry;
        }

        return false;
    }
}
