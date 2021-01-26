<?php

namespace ForWebSystem\NotificationWhatsApp;

use Illuminate\Support\ServiceProvider;

class NotificationWhatsAppServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'forwebsystem');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'forwebsystem');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->registerMigrations();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/notificationwhatsapp.php', 'notificationwhatsapp');

        // Register the service the package provides.
        $this->app->singleton('notificationwhatsapp', function ($app) {
            return new NotificationWhatsApp;
        });
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        if ( $this->app->runningInConsole() ) {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        }
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['notificationwhatsapp'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/notificationwhatsapp.php' => config_path('notificationwhatsapp.php'),
        ], 'notificationwhatsapp.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/forwebsystem'),
        ], 'notificationwhatsapp.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/forwebsystem'),
        ], 'notificationwhatsapp.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/forwebsystem'),
        ], 'notificationwhatsapp.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
