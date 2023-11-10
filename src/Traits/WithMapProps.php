<?php

namespace Mostafaznv\NovaMapField\Traits;

use Illuminate\Support\Arr;
use Mostafaznv\NovaMapField\DTOs\Capture;
use Mostafaznv\NovaMapField\DTOs\MapSearchBoxType;
use Mostafaznv\NovaMapField\DTOs\MapSearchProvider;


trait WithMapProps
{
    private string $templateUrl;

    private ?string $projection;
    private int     $srid;
    private ?float  $defaultLatitude;
    private ?float  $defaultLongitude;
    private int     $zoom;

    private bool $withZoomControl;
    private bool $withZoomSlider;
    private bool $withUndoControl;
    private bool $withClearMapControl;
    private bool $withFullScreenControl;

    private int $mapHeight;

    /**
     * @readonly
     */
    private string $markerIconPath = '/vendor/nova-map-field/dist/images/';
    private string $markerIcon;

    private string $strokeColor;
    private string $strokeWidth;
    private string $fillColor;

    private bool $showDetailButton;

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

    private bool $transformStatus;
    private bool $transformScale;
    private bool $transformRotate;
    private bool $transformStretch;

    private ?Capture $capture = null;


    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $config = config('nova-map-field');
        $searchConfig = $config['search'];
        $transformConfig = $config['transform'];

        $this->templateUrl = $config['template-url'];
        $this->projection = $config['projection'];
        $this->srid = $config['srid'] ?? 0;
        $this->defaultLatitude = $config['default-latitude'];
        $this->defaultLongitude = $config['default-longitude'];
        $this->zoom = $config['zoom'];
        $this->withZoomControl = $config['controls']['zoom-control'] ?? true;
        $this->withZoomSlider = $config['controls']['zoom-slider'] ?? true;
        $this->withFullScreenControl = $config['controls']['full-screen-control'] ?? false;
        $this->withUndoControl = $config['controls']['undo-control'] ?? true;
        $this->withClearMapControl = $config['controls']['clear-map-control'] ?? true;
        $this->mapHeight = $config['map-height'];
        $this->markerIcon = url($this->markerIconPath . "ic-pin-{$config['icon']}.png");
        $this->strokeColor = $config['style']['stroke-color'];
        $this->strokeWidth = $config['style']['stroke-width'];
        $this->fillColor = $config['style']['fill-color'];
        $this->showDetailButton = $config['show-detail-button'];

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

        $this->transformStatus = $transformConfig['enable'];
        $this->transformScale = $transformConfig['scale'];
        $this->transformRotate = $transformConfig['rotate'];
        $this->transformStretch = $transformConfig['stretch'];
    }


    public function templateUrl(string $url): self
    {
        $this->templateUrl = $url;

        return $this;
    }

    public function projection(string $projection): self
    {
        $this->projection = $projection;

        return $this;
    }

    public function srid(int $srid): self
    {
        $this->srid = $srid;

        return $this;
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

    public function withoutUndoControl(bool $status = true): self
    {
        $this->withUndoControl = !$status;

        return $this;
    }

    public function withoutClearMapControl(bool $status = true): self
    {
        $this->withClearMapControl = !$status;

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

    public function hideDetailButton(bool $status = true): self
    {
        $this->showDetailButton = !$status;

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

    public function withTransformation(bool $status = true): self
    {
        $this->transformStatus = $status;

        return $this;
    }

    public function transformScale(bool $status): self
    {
        $this->transformScale = $status;

        return $this;
    }

    public function transformRotate(bool $status): self
    {
        $this->transformRotate = $status;

        return $this;
    }

    public function transformStretch(bool $status): self
    {
        $this->transformStretch = $status;

        return $this;
    }

    public function capture(Capture $capture): self
    {
        $this->capture = $capture;

        return $this;
    }


    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'mapType'               => $this->mapType ?? 'POINT',
            'templateUrl'           => $this->templateUrl,
            'projection'            => $this->projection,
            'srid'                  => $this->srid,
            'defaultLatitude'       => $this->defaultLatitude,
            'defaultLongitude'      => $this->defaultLongitude,
            'zoom'                  => $this->zoom,
            'withZoomControl'       => $this->withZoomControl,
            'withZoomSlider'        => $this->withZoomSlider,
            'withUndoControl'       => $this->withUndoControl,
            'withClearMapControl'   => $this->withClearMapControl,
            'withFullScreenControl' => $this->withFullScreenControl,
            'mapHeight'             => $this->mapHeight,
            'markerIcon'            => $this->markerIcon,
            'showDetailButton'      => $this->showDetailButton,
            'capture'               => $this->capture?->toArray(),
            'search'                => [
                'isEnabled'             => $this->showSearchBox,
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
            'transform'             => [
                'isEnabled' => $this->transformStatus,
                'scale'     => $this->transformScale,
                'rotate'    => $this->transformRotate,
                'stretch'   => $this->transformStretch
            ],
            'style'                 => [
                'strokeColor' => $this->strokeColor,
                'strokeWidth' => $this->strokeWidth,
                'fillColor'   => $this->fillColor
            ],
        ]);
    }
}
