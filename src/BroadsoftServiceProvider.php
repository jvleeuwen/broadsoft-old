<?php

namespace jvleeuwen\broadsoft;

use Illuminate\Support\ServiceProvider;
class BroadsoftServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/Routes/routes.php';
        $this->loadViewsFrom(__DIR__.'/Views', 'broadsoft');
        $this->publishes([
            __DIR__.'/Assets/js/broadsoft.js' => base_path('resources/assets/js/broadsoft.js', 'broadsoft'),
            __DIR__.'/Assets/js/components/debug/CallCenterQueue.vue' => base_path('resources/assets/js/components/CallCenterQueue.js', 'broadsoft'),
            __DIR__.'/Assets/js/components/debug/CallCenterAgent.vue' => base_path('resources/assets/js/components/CallCenterAgent.vue', 'broadsoft'),
            __DIR__.'/Assets/js/components/debug/AdvancedCall.vue' => base_path('resources/assets/js/components/AdvancedCall.vue', 'broadsoft'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('jvleeuwen\broadsoft\Repositories\Contracts\BsUserInterface','jvleeuwen\broadsoft\Repositories\BsUserRepository');
        $this->app->bind('jvleeuwen\broadsoft\Repositories\Contracts\BsCallCenterInterface','jvleeuwen\broadsoft\Repositories\BsCallCenterRepository');
        $this->app->bind('jvleeuwen\broadsoft\Repositories\Contracts\BsCallCenterMonitoringInterface','jvleeuwen\broadsoft\Repositories\BsCallCenterMonitoringRepository');
        $this->app->bind('jvleeuwen\broadsoft\Repositories\Contracts\BsExampleInterface','jvleeuwen\broadsoft\Repositories\BsExampleRepository');
        $this->app->make('jvleeuwen\broadsoft\Controllers\CallCenterAgentController');
        $this->app->make('jvleeuwen\broadsoft\Controllers\CallCenterQueueController');
        $this->app->make('jvleeuwen\broadsoft\Controllers\CallCenterMonitoringController');
        $this->app->make('jvleeuwen\broadsoft\Controllers\AdvancedCallController');
        $this->app->make('jvleeuwen\broadsoft\Controllers\DebugController');
        $this->app->make('jvleeuwen\broadsoft\Controllers\ActionController');
        $this->app->make('jvleeuwen\broadsoft\Controllers\ExampleController');
        $this->app->make('jvleeuwen\broadsoft\Controllers\EmailController');
    }
}
