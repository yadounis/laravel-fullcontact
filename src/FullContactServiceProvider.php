<?php

namespace Yadounis\FullContact;

use Illuminate\Support\ServiceProvider;

class FullContactServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/fullcontact.php' => config_path('fullcontact.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/fullcontact.php', 'fullcontact');

        $this->app->bindShared('fullcontact', function($app)
        {
            $fullcontact = new Services_FullContact($app['fullcontact.apikey']);

            return $fullcontact;
        });

        $this->app->alias('fullcontact', 'Yadounis\Fullcontact\Services_FullContact');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('fullcontact', 'Yadounis\Fullcontact\Services_FullContact');
    }
}
