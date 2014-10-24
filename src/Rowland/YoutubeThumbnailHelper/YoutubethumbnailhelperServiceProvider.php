<?php namespace Rowland\YoutubeThumbnailHelper;

use Illuminate\Support\ServiceProvider;

class YoutubeThumbnailHelperServiceProvider extends ServiceProvider {

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
		$this->package('rowland/youtubethumbnailhelper');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['youtubethumbnailhelper'] = $this->app->share(function($app)
		{
			return new YoutubeThumbnailHelper;
		});

        /**
        * This allows the facade to work without the developer having to add it to the Alias array in app/config/app.php
        * http://fideloper.com/create-facade-laravel-4
        */
		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('YoutubeThumbnailHelper', 'Rowland\YoutubeThumbnailHelper\Facades\YoutubeThumbnailHelper');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('youtubethumbnailhelper');
	}

}
