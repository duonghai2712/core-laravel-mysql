<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valid_array', function($attribute, $value, $parameters){
            $value = array_filter($value,function ($value){
                if(is_numeric($value)){
                    return $value;
                }
            });
            if(is_array($value) && count($value) > 0){
                return true;
            }
            return false;
        });
    }
}
