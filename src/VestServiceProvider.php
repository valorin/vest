<?php namespace Valorin\Vest;

use Illuminate\Support\ServiceProvider;
use Valorin\Vest\Command;

class VestServiceProvider extends ServiceProvider
{
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
        $this->package('valorin/vest', 'vest', __DIR__);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }


    /**
     * Registers Artisan Commands
     *
     * @return void
     */
    protected function registerCommands()
    {

        $app = $this->app;

        // 'vest'
        $app['command.vest'] = $app->share(function ($app) {
            return new Command\Vest;
        });
        $this->commands('command.vest');

        // 'vest:createdb'
        $app['command.vest.coverage'] = $app->share(function ($app) {
            return new Command\Coverage;
        });
        $this->commands('command.vest.coverage');

        // 'vest:createdb'
        $app['command.vest.createdb'] = $app->share(function ($app) {
            return new Command\CreateDb;
        });
        $this->commands('command.vest.createdb');
    }
}
