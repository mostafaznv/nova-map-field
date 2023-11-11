<?php

namespace Mostafaznv\NovaMapField\DTOs;


class Capture
{
    public function __construct(
        public string $column,
        public int    $width,
        public int    $height,
        public int    $maxZoom = 13,
        public array  $padding = [0, 0, 0, 0],
        public bool   $nearest = true,
        public string $disk = 'public',
        public bool   $prunable = true
    ) {}

    public static function make(string $column, int $width, int $height): self
    {
        return new self($column, $width, $height);
    }

    public function maxZoom(int $maxZoom): self
    {
        $this->maxZoom = $maxZoom;

        return $this;
    }

    public function padding(array $padding): self
    {
        $this->padding = $padding;

        return $this;
    }

    public function nearest(bool $nearest): self
    {
        $this->nearest = $nearest;

        return $this;
    }

    public function disk(string $disk): self
    {
        $this->disk = $disk;

        return $this;
    }

    public function prunable(bool $status): self
    {
        $this->prunable = $status;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'column'   => $this->column,
            'width'    => $this->width,
            'height'   => $this->height,
            'maxZoom'  => $this->maxZoom,
            'padding'  => $this->padding,
            'nearest'  => $this->nearest,
            'disk'     => $this->disk,
            'prunable' => $this->prunable,
        ];
    }
}
