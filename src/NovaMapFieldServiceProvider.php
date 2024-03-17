<?php

namespace Mostafaznv\NovaMapField;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;


class NovaMapFieldServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publish();
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'nova-map-field');
    }

    public function boot(): void
    {
        Nova::serving(function() {
            Nova::style('nova-map-field', __DIR__ . '/../dist/field.css');
            Nova::script('nova-map-field', __DIR__ . '/../dist/field.js');
        });
    }

    protected function publish(): void
    {
        $this->publishes([__DIR__ . '/../dist/vendor' => public_path('vendor')], 'assets');

        $this->publishes([__DIR__ . '/../config/config.php' => config_path('nova-map-field.php')], 'config');
    }
}
