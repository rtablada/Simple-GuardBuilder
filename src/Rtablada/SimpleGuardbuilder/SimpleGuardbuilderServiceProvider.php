<?php namespace Rtablada\SimpleGuardbuilder;

use Illuminate\Support\ServiceProvider;

class SimpleGuardbuilderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['guard.deploy'] = $this->app->share(function($app)
		{
			$builder = new GuardBuilder($app['files'], $app['config']);
			return new GuardfileDeployCommand($builder);
		});

		$this->registerCommands();
	}

	public function boot()
	{
		$this->package('rtablada/simple-guardbuilder');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('');
	}

	/**
	 * Make commands visible to Artisan
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		$this->commands(
			'guard.deploy'
		);
	}

}
