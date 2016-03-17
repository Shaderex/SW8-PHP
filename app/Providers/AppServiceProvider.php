<?php

namespace DataCollection\Providers;

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
        \Validator::extend('greater_than', function ($attribute, $value, $parameters) {

            $other = Input::get($parameters[0]);

            return isset($other) && intval($value) > intval($other);
        }, "Du er ikke god");



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
