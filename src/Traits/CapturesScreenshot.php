<?php

namespace Mostafaznv\NovaMapField\Traits;

use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;


trait CapturesScreenshot
{
    private function storeScreenshot(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        $requestAttribute = "$requestAttribute.image";

        if ($this->capture and $request->hasFile($requestAttribute)) {
            $path = $request->file($requestAttribute)->store(
                "screenshot/$model->id",
                $this->capture->disk
            );


            if ($path) {
                $column = $this->capture->column;

                if ($model->{$column}) {
                    Storage::disk($this->capture->disk)->delete($model->{$column});
                }

                $model->{$column} = $path;
            }
        }
    }
}
