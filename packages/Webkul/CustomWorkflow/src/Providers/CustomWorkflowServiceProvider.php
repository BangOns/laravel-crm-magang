<?php

namespace Webkul\CustomWorkflow\Providers;

use Illuminate\Support\ServiceProvider;

class CustomWorkflowServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {}
}
