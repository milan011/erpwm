<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class VailidatorServiceProvider extends ServiceProvider
{   
    
    public function boot(){

        Validator::extend('phone_number', function($attribute, $value, $parameters, $validator) {
            if(preg_match("/^1[34578]\d{9}$/", $value)){
                return true;
            }
            return false;
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