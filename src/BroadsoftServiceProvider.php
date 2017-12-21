<?php

namespace Jvleeuwen\Broadsoft;

use Illuminate\Support\ServiceProvider;
use Jvleeuwen\Broadsoft\Services\XmlService;
use Jvleeuwen\Broadsoft\Services\CallCenterMonitoringService;
use Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract;
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
        $this->loadRoutesFrom(__DIR__ . '/../routes/broadsoft.php');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->publishes(['/../config/broadsoft.php' => config_path('broadsoft.php')]);

        /**
         * Queue components
         */
        $this->publishes(['/../assets/css/components/queue.css' => resource_path('assets/css/components/queue.css')], 'css');
        $this->publishes(['/../assets/js/components/Queue.vue' => resource_path('assets/js/components/Queue.vue')], 'vue');
        $this->publishes(['/../assets/js/components/QueueAgents.vue' => resource_path('assets/js/components/QueueAgents.vue')], 'vue');

        /**
         * Ticket components
         */
        $this->publishes(['/../assets/css/components/tickets.css' => resource_path('assets/css/components/tickets.css'), 'css']);
        $this->publishes([__DIR__ . '/../assets/js/components/Tickets.vue' => resource_path('assets/js/components/Tickets.vue')], 'vue');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CallCenterMonitoringContract::class, CallCenterMonitoringRepository::class);

        $this->app->make('Jvleeuwen\Broadsoft\Controllers\CallCenterMonitoringController');

        $this->app->singleton('callcentermonitoring', function ($app) {
            return new CallCenterMonitoringService($app->make(CallCenterMonitoringRepository::class));
        });

        $this->app->singleton('xml', function () {
            return new XmlService;
        });
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return ['callcentermonitoring', 'xml'];
    }
}
