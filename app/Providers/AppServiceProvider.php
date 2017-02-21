<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    if ($this->app->environment() == 'local') {
		    $this->app->register('Wn\Generators\CommandsServiceProvider');
	    }

	    // We have to manually register the mailer
	    // https://laracasts.com/discuss/channels/lumen/lumen-52-mail-not-working
	    $this->app->singleton('mailer', function ($app){
		    $app->configure('services');

		    return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
	    });
    }
}
