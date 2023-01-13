<?php

namespace Michaelmannucci\Webhook;

use Michaelmannucci\Webhook\Events\Webhook;
use Statamic\Providers\AddonServiceProvider;
use Illuminate\Support\Facades\Event;
use Statamic\Events\EntryCreated;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    public function boot()
    {
        // Listen for the event of a new entry being created
        Event::listen(
            EntryCreated::class,
            [Webhook::class, 'handle']
        );

        // Merge the config file from 'webhook.php' file in the config folder
        $this->mergeConfigFrom(__DIR__.'/../config/webhook.php', 'webhook');

         // If running in console
        if ($this->app->runningInConsole()) {

            // Publish the config file to config path
            $this->publishes([
                __DIR__.'/../config/webhook.php' => config_path('webhook.php'),
            ], 'webhook');
        }

        // Call vendor publish command
        Statamic::afterInstalled(function ($command) {
            $command->call('vendor:publish', ['--tag' => 'webhook']);
        });
    }
}