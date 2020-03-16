<?php

namespace App\Providers;

use App\Auth\Social\Two\TelegramProvider;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootTelegramSocialite();
    }

    private function bootTelegramSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'telegram',
            function ($app) use ($socialite) {
                $config = $app['config']['services.telegram'];
                return $socialite->buildProvider(TelegramProvider::class, $config);
            }
        );
    }
}
