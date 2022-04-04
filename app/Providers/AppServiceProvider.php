<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
        resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme('https');
        view()->composer('asbab.*', function($view) {
            $cart = session()->get('cart');
            $brands = \App\Models\Brand::all();
            $settings = \App\Models\Setting::all();
            $view->with('carts', $cart)->with('brands', $brands)->with('settings', $settings);
        });
    }
}
