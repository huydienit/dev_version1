<?php

namespace Nhvv\Core;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    protected $package = 'nhvv';
    protected $module = 'core';

    protected $namespace = __NAMESPACE__ . '\App\Http\Controllers';

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $routesDir = __DIR__ . '/routes';
        $ls = @scandir($routesDir);
        if ($ls) {
            foreach ($ls as $index => $routeFile) {
                switch (substr($routeFile, 0, -4)) {
                    case 'web':
                        if (file_exists(__DIR__ . '/routes/web.php')) {
                            Route::middleware('web')
                                ->namespace($this->namespace)
                                ->group(__DIR__ . '/routes/web.php');
                        }
                    case 'api':
                        if (file_exists(__DIR__ . '/routes/api.php')) {
                            Route::prefix('api')
                                ->middleware('api')
                                ->namespace($this->namespace)
                                ->group(__DIR__ . '/routes/api.php');
                        }
                        break;
                }
            }
        }

        /** load views **/
        $this->loadViewsFrom(__DIR__.'/views', strtoupper($this->package . '-' . $this->module));
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
