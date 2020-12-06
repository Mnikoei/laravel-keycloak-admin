<?php

namespace Mnikoei;

use Mnikoei\AdminService;
use Illuminate\Support\ServiceProvider;

class KeycloakAdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('KeycloakAdmin' , function ($app){
            return $app->make(AdminService::class);
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/keycloakAdmin.php' => config_path('keycloakAdmin.php')
        ] , 'KeycloakAdmin');
    }
}
