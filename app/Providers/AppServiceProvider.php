<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Blade::component('frontOffice.components.ui.modal', 'ui.modal');
        Blade::component('frontOffice.components.ui.button', 'ui.button');
        Blade::component('frontOffice.components.ui.card', 'ui.card');
    }
}
