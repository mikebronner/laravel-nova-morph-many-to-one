<?php

namespace GeneaLabs\LaravelNovaMorphManyToOne\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class Service extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('laravel-nova-morph-many-to-one', __DIR__.'/../../dist/js/field.js');
            Nova::style('laravel-nova-morph-many-to-one', __DIR__.'/../../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
