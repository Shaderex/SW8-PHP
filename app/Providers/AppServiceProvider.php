<?php

namespace DataCollection\Providers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('lte', function ($attribute, $value, $parameters) {

            $other = Input::get($parameters[0]);
            return isset($other) && intval($value) <= intval($other);
        }, "This should be bigger than the one above");
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
