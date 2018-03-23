<?php

namespace Jvleeuwen\Broadsoft;

use Illuminate\Support\ServiceProvider;
use Jvleeuwen\Broadsoft\Services\XmlService;
use Jvleeuwen\Broadsoft\Contracts\AdvancedCallContract;
use Jvleeuwen\Broadsoft\Services\CallCenterAgentService;
use Jvleeuwen\Broadsoft\Contracts\CallCenterAgentContract;
use Jvleeuwen\Broadsoft\Repositories\AdvancedCallRepository;
use Jvleeuwen\Broadsoft\Services\CallCenterMonitoringService;
use Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract;
use Jvleeuwen\Broadsoft\Repositories\CallCenterAgentRepository;
use Jvleeuwen\Broadsoft\Repositories\CallCenterMonitoringRepository;
use Jvleeuwen\Broadsoft\Services\AdvancedCallService;
use Jvleeuwen\Broadsoft\Contracts\CallCenterQueueContract;
use Jvleeuwen\Broadsoft\Repositories\CallCenterQueueRepository;
use Jvleeuwen\Broadsoft\Services\CallCenterQueueService;

class BroadsoftServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Load Routes
         */
        $this->loadRoutesFrom(__DIR__ . '/../routes/broadsoft.php');

        /**
         * Load Migrations
         */
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        /**
         * Publish configuration file.
         */
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
        /**
         * Call Center Monitoring
         */
        $this->app->singleton(CallCenterMonitoringContract::class, CallCenterMonitoringRepository::class);

        $this->app->make('Jvleeuwen\Broadsoft\Controllers\CallCenterMonitoringController');

        $this->app->singleton('callcentermonitoring', function ($app) {
            return new CallCenterMonitoringService($app->make(CallCenterMonitoringRepository::class));
        });

        /**
         * Global XML Service
         */
        $this->app->singleton('xml', function () {
            return new XmlService;
        });

        /**
         * Call Center Agent
         */
        $this->app->singleton(CallCenterAgentContract::class, CallCenterAgentRepository::class);

        $this->app->make('Jvleeuwen\Broadsoft\Controllers\CallCenterAgentController');

        $this->app->singleton('callcenteragent', function ($app) {
            return new CallCenterAgentService($app->make(CallCenterAgentRepository::class));
        });

        /**
         * Advanced Call
         */
        $this->app->singleton(AdvancedCallContract::class, AdvancedCallRepository::class);
        $this->app->make('Jvleeuwen\Broadsoft\Controllers\AdvancedCallController');
        $this->app->singleton('advancedcall', function ($app) {
            return new AdvancedCallService($app->make(AdvancedCallRepository::class));
        });

        /**
         * Call Center Queue
         *
         */
        $this->app->singleton(CallCenterQueueContract::class, CallCenterQueueRepository::class);
        $this->app->make('Jvleeuwen\Broadsoft\Controllers\CallCenterQueueController');
        $this->app->singleton('callcenterqueue', function ($app) {
            return new CallCenterQueueService($app->make(CallCenterQueueRepositorycompo::class));
        });
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return ['callcentermonitoring', 'callcenteragent', 'advancedcall', 'callcenterqueue', 'xml'];
    }
}
