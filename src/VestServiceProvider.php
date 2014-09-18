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
        $this->app['command.vest'] = $this->app->share(function () {
            return new Command\Vest;
        });
        $this->commands('command.vest');

        $this->app['command.vest.run'] = $this->app->share(function () {
            return new Command\Run;
        });
        $this->commands('command.vest.run');

        $this->app['command.vest.coverage'] = $this->app->share(function () {
            return new Command\Coverage;
        });
        $this->commands('command.vest.coverage');
    }
}
