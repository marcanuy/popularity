<?php namespace Marcanuy\Popularity;

use Illuminate\Support\ServiceProvider;

class PopularityServiceProvider extends ServiceProvider {

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
		$this->package('marcanuy/popularity');

        include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['popularity'] = $this->app->share(function($app)
        {
            return new Popularity;
        });

        //register aliases in container
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Stats', 'Marcanuy\Popularity\Stats');
            $loader->alias('Popularity', 'Marcanuy\Popularity\Facades\Popularity');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}