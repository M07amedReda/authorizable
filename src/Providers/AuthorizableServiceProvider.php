<?php
namespace MohamedReda\Authorizable\Providers;

use Illuminate\Support\ServiceProvider;

class AuthorizableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPublishables();
    }

    public function registerPublishables()
    {
        $publishablePath = __DIR__ . '/../publishables';
        $this->publishes([
            $publishablePath . '/migrations' => database_path('migrations'),
            $publishablePath . '/seeders' => database_path('seeds'),
        ], 'authorizable');
    }
}
