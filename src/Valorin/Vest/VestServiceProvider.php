<?php namespace Valorin\Vest;

use Illuminate\Support\ServiceProvider;
use Valorin\Vest\Command\Vest;

class VestServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('valorin/vest');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommand();
    }


    /**
     * Registers Artisan Commands
     *
     * @return void
     */
    protected function registerCommand()
    {
        $app = $this->app;

        $app['command.vest'] = $app->share(function($app)
        {
            return new Vest;
        });

        $this->commands('command.vest');
    }
}
