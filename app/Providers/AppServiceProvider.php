<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $cart = session('cart', []);
            $totalQuantity = 0;
            foreach ($cart as $item) {
                $totalQuantity += $item['quantity'];
            }
            $view->with('totalQuantity', $totalQuantity);
        });
    }
}
