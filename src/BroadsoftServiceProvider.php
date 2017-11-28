<?php

namespace Jvleeuwen\Broadsoft;

use Illuminate\Support\ServiceProvider;
use Jvleeuwen\Broadsoft\Services\CallCenterMonitoringService;
use Jvleeuwen\Broadsoft\Events\Broadsoft\CallCenterMonitoringEvent;
use Jvleeuwen\Broadsoft\Repositories\CallCenterMonitoringRepository;

// use jvleeuwen\broadsoft\Commands\bsGetUsers;
// use jvleeuwen\broadsoft\Commands\bsGetCallCenterServices;
// use jvleeuwen\broadsoft\Commands\getACDAgentUnavailableCodes;

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
        // $this->bindEvents($this->app);


        // include __DIR__.'/routes/broadsoft.php';

        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         bsGetUsers::class,
        //         bsGetCallCenterServices::class,
        //         getACDAgentUnavailableCodes::class,
        //     ]);
        // }
        // include __DIR__.'/Routes/routes.php';
        // $this->loadViewsFrom(__DIR__.'/Views', 'broadsoft');
        // $this->publishes([
        //     __DIR__.'/Assets/js/broadsoft.js' => base_path('resources/assets/js/broadsoft.js'),
        //     __DIR__.'/Assets/js/components/CallCenterQueue.vue' => base_path('resources/assets/js/components/CallCenterQueue.js'),
        //     __DIR__.'/Assets/js/components/CallCenterAgents.vue' => base_path('resources/assets/js/components/CallCenterAgents.vue'),
        //     __DIR__.'/Assets/js/components/AdvancedCall.vue' => base_path('resources/assets/js/components/AdvancedCall.vue'),
        // ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract::class, Jvleeuwen\Broadsoft\Repositories\CallCenterMonitoringRepository::class);
        $this->app->bind(CallCenterMonitoringContract::class, function () {
            $dependancy = new Dependancy();
            return new CallCenterMonitoringRepository($dependancy);// inject what ever u need
        });
        $this->app->make('Jvleeuwen\Broadsoft\Controllers\CallCenterMonitoringController');

        $this->app->singleton('callcentermonitoring', function () {
            return new CallCenterMonitoringService;
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
