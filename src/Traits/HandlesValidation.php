<?php

namespace Mostafaznv\NovaMapField\Traits;

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
                $rules = $this->mapRules;
                $creationRules = array_merge_recursive($this->mapCreationRules, $rules);
                $updateRules = array_merge_recursive($this->mapUpdateRules, $rules);

                if ($request->isResourceIndexRequest() || $request->isActionRequest()) {
                    return $this->required or $this->requiredOnCreate or in_array('required', $creationRules);
                }

                if ($request->isCreateOrAttachRequest()) {
                    return $this->required or $this->requiredOnCreate or in_array('required', $creationRules);
                }

                if ($request->isUpdateOrUpdateAttachedRequest()) {
                    return $this->required or $this->requiredOnUpdate or in_array('required', $updateRules);
                }
            }

            return false;
        });
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

        if ($this->required) {
            $rules = array_merge_recursive($rules, [new $this->validationRulesClass]);
        }
        else if ($this->requiredOnCreate) {
            $rules = array_merge_recursive($rules, [new $this->validationRulesClass]);
        }
        else if ($this->requiredOnUpdate) {
            $rules = array_merge_recursive($rules, [new $this->validationRulesClass]);
        }


        if ($request->isCreateOrAttachRequest()) {
            $creationRules = array_merge_recursive($this->mapCreationRules, $rules);

            $request->validate([
                $originalAttribute   => $creationRules,
                $screenshotAttribute => $this->screenshotRules,
            ]);
        }
        else if ($request->isUpdateOrUpdateAttachedRequest()) {
            $updateRules = array_merge_recursive($this->mapUpdateRules, $rules);

            $request->validate([
                $originalAttribute   => $updateRules,
                $screenshotAttribute => $this->screenshotRules,
            ]);
        }
    }
}
