<?php

namespace Webkul\CustomWorkflow\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('contacts.person.update.before', 'Webkul\CustomWorkflow\Listeners\Person@update');
    }
}
