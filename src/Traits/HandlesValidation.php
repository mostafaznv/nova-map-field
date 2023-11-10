<?php

namespace Mostafaznv\NovaMapField\Traits;

use Illuminate\Support\Arr;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaMapField\Fields\MapMultiPolygonField;
use Mostafaznv\NovaMapField\Fields\MapPointField;
use Mostafaznv\NovaMapField\Fields\MapPolygonField;


trait HandlesValidation
{
    public array $screenshotRules = [
        'nullable', 'file', 'mimetypes:image/jpeg,image/png', 'mimes:png,jpg,jpeg'
    ];

    public array $mapRules         = [];
    public array $mapCreationRules = [];
    public array $mapUpdateRules   = [];


    /**
     * @inheritDoc
     */
    public function validationKey(): string
    {
        return "$this->attribute.value";
    }

    /**
     * Validation rules for the screenshot file
     *
     * @param array $rules
     * @return MapPointField|MapPolygonField|MapMultiPolygonField|HandlesValidation
     */
    public function screenshotRules(array $rules): self
    {
        $this->screenshotRules = $rules;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rules(...$rules): self
    {
        parent::rules($rules);

        $this->mapRules = $this->rules;
        $this->rules = [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function creationRules(...$rules): self
    {
        parent::creationRules($rules);

        $this->mapCreationRules = $this->creationRules;
        $this->creationRules = [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function updateRules(...$rules): self
    {
        parent::updateRules($rules);

        $this->mapUpdateRules = $this->updateRules;
        $this->updateRules = [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isRequired(NovaRequest $request)
    {
        return with($this->requiredCallback, function ($callback) use ($request) {
            if ($callback === true || (is_callable($callback) && call_user_func($callback, $request))) {
                return true;
            }

            if (!empty($this->attribute) && is_null($callback)) {
                if ($request->isResourceIndexRequest() || $request->isActionRequest()) {
                    return in_array('required', $this->mapRules) or in_array('required', $this->mapCreationRules);
                }

                if ($request->isCreateOrAttachRequest()) {
                    return in_array('required', $this->mapRules) or in_array('required', $this->mapCreationRules);
                }

                if ($request->isUpdateOrUpdateAttachedRequest()) {
                    return in_array('required', $this->mapRules) or in_array('required', $this->mapUpdateRules);
                }
            }

            return false;
        });
    }


    /**
     * @inheritDoc
     */
    public function required($callback = true): self
    {
        parent::required($callback);

        if (!Arr::exists($this->mapRules, 'required')) {
            $this->mapRules[] = 'required';
        }

        return $this;
    }

    /**
     * Set required rule for the map creation field
     *
     * @param bool $status
     * @return MapPointField|MapMultiPolygonField|MapPolygonField|HandlesValidation
     */
    public function requiredOnCreate(bool $status = true): self
    {
        if ($status) {
            if (!Arr::exists($this->mapCreationRules, 'required')) {
                $this->mapCreationRules[] = 'required';
            }
        }
        else {
            $this->mapCreationRules = Arr::except($this->mapCreationRules, ['required']);
        }


        return $this;
    }

    /**
     * Set required rule for the map update field
     *
     * @param bool $status
     * @return MapPointField|MapMultiPolygonField|MapPolygonField|HandlesValidation
     */
    public function requiredOnUpdate(bool $status = true): self
    {
        if ($status) {
            if (!Arr::exists($this->mapUpdateRules, 'required')) {
                $this->mapUpdateRules[] = 'required';
            }
        }
        else {
            $this->mapUpdateRules = Arr::except($this->mapUpdateRules, ['required']);
        }

        return $this;
    }


    /**
     * Custom validation for nova-map-field fields
     *
     * @param NovaRequest $request
     * @param string $attribute
     * @return void
     */
    protected function validate(NovaRequest $request, string $attribute): void
    {
        $originalAttribute = "$attribute.value";
        $screenshotAttribute = "$attribute.image";

        $rules = $this->mapRules;

        if ($request->isCreateOrAttachRequest()) {
            $creationRules = array_merge_recursive(
                $this->mapCreationRules,
                $rules,
                [new $this->validationRulesClass]
            );

            $request->validate([
                $originalAttribute   => $creationRules,
                $screenshotAttribute => $this->screenshotRules,
            ]);
        }
        else if ($request->isUpdateOrUpdateAttachedRequest()) {
            $updateRules = array_merge_recursive(
                $this->mapUpdateRules,
                $rules,
                [new $this->validationRulesClass]
            );

            $request->validate([
                $originalAttribute   => $updateRules,
                $screenshotAttribute => $this->screenshotRules,
            ]);
        }
    }
}
