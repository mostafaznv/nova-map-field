<?php

namespace Mostafaznv\NovaMapField\Traits;

use Mostafaznv\NovaMapField\DTOs\MapSearchBoxType;
use Mostafaznv\NovaMapField\DTOs\MapSearchProvider;

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

    private bool              $showSearchBox;
    private MapSearchProvider $searchProvider;
    private string            $searchProviderApiKey;
    private bool              $searchAutocomplete;
    private int               $searchAutocompleteMinLength;
    private int               $searchAutocompleteTimeout;
    private string            $searchLanguage;
    private string            $searchPlaceholder;
    private MapSearchBoxType  $searchBoxType;
    private int               $searchResultLimit;
    private bool              $searchResultKeepOpen;

    private bool $required         = false;
    private bool $requiredOnCreate = false;
    private bool $requiredOnUpdate = false;


    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $config = config('nova-map-field');
        $searchConfig = $config['search'];

        $this->defaultLatitude = $config['default-latitude'];
        $this->defaultLongitude = $config['default-longitude'];
        $this->zoom = $config['zoom'];
        $this->withZoomControl = $config['controls']['zoom-control'];
        $this->withZoomSlider = $config['controls']['zoom-slider'];
        $this->withFullScreenControl = $config['controls']['full-screen-control'];
        $this->mapHeight = $config['map-height'];
        $this->markerIcon = $config['icon'];

        $this->showSearchBox = $searchConfig['enable'];
        $this->searchProvider = $searchConfig['provider'];
        $this->searchProviderApiKey = $searchConfig['api-key'];
        $this->searchAutocomplete = $searchConfig['autocomplete'];
        $this->searchAutocompleteMinLength = $searchConfig['autocomplete-min-length'];
        $this->searchAutocompleteTimeout = $searchConfig['autocomplete-timeout'];
        $this->searchLanguage = $searchConfig['language'];
        $this->searchPlaceholder = __($searchConfig['placeholder']);
        $this->searchBoxType = $searchConfig['box-type'];
        $this->searchResultLimit = $searchConfig['limit'];
        $this->searchResultKeepOpen = $searchConfig['keep-open'];
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

    public function withSearchBox(bool $status = true): self
    {
        $this->showSearchBox = $status;

        return $this;
    }

    public function searchProvider(MapSearchProvider $provider): self
    {
        $this->searchProvider = $provider;

        return $this;
    }

    public function searchProviderApiKey(string $apiKey): self
    {
        $this->searchProviderApiKey = $apiKey;

        return $this;
    }

    public function withAutocompleteSearch(bool $status = true): self
    {
        $this->searchAutocomplete = $status;

        return $this;
    }

    public function searchAutocompleteMinLength(int $minLength): self
    {
        $this->searchAutocompleteMinLength = $minLength;

        return $this;
    }

    public function searchAutocompleteTimeout(int $timeout): self
    {
        $this->searchAutocompleteTimeout = $timeout;

        return $this;
    }

    public function searchLanguage(string $language): self
    {
        $this->searchLanguage = $language;

        return $this;
    }

    public function searchPlaceholder(string $placeholder): self
    {
        $this->searchPlaceholder = $placeholder;

        return $this;
    }

    public function searchBoxType(MapSearchBoxType $type): self
    {
        $this->searchBoxType = $type;

        return $this;
    }

    public function searchResultLimit(int $limit): self
    {
        $this->searchResultLimit = $limit;

        return $this;
    }

    public function searchResultKeepOpen(bool $status): self
    {
        $this->searchResultKeepOpen = $status;

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
            'search'                => [
                'isEnabled'              => $this->showSearchBox,
                'provider'              => $this->searchProvider->getValue(),
                'apiKey'                => $this->searchProviderApiKey,
                'withAutocomplete'      => $this->searchAutocomplete,
                'autocompleteMinLength' => $this->searchAutocompleteMinLength,
                'autocompleteTimeout'   => $this->searchAutocompleteTimeout,
                'language'              => $this->searchLanguage,
                'placeholder'           => $this->searchPlaceholder,
                'boxType'               => $this->searchBoxType->getValue(),
                'resultLimit'           => $this->searchResultLimit,
                'resultKeepOpen'        => $this->searchResultKeepOpen
            ],
        ]);
    }
}
