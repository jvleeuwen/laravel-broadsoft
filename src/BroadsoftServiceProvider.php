<?php

namespace Jvleeuwen\Broadsoft;

use Illuminate\Support\ServiceProvider;
use Jvleeuwen\Broadsoft\Services\CallCenterMonitoringService;
use Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract;
use Jvleeuwen\Broadsoft\Events\Broadsoft\CallCenterMonitoringEvent;
use Jvleeuwen\Broadsoft\Repositories\CallCenterMonitoringRepository;

class BroadsoftServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/broadsoft.php');
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
        $this->publishes(['/../config/broadsoft.php' => config_path('broadsoft.php')]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CallCenterMonitoringContract::class, CallCenterMonitoringRepository::class);

        // $this->app->bind(CallCenterMonitoringContract::class, function () {
        //     $dependancy = new CallCenterMonitoringContract();
        //     return new CallCenterMonitoringRepository($dependancy);// inject what ever u need
        // });

        $this->app->make('Jvleeuwen\Broadsoft\Controllers\CallCenterMonitoringController');

        $this->app->singleton('callcentermonitoring', function ($app) {
            return new CallCenterMonitoringService($app->make(CallCenterMonitoringRepository::class));
        });
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return ['callcentermonitoring'];
    }
}
