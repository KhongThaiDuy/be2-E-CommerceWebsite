<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\View;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Đăng ký route model binding với hashid
        Route::bind('hashid', function ($value) {
            $decoded = Hashids::decode($value);
            if (count($decoded) === 0) {
                abort(404);
            }
            return User::findOrFail($decoded[0]);
        });

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
