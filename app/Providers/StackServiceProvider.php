<?php

namespace App\Providers;

use App\Stack\StackApi;
use Illuminate\Support\ServiceProvider;

class StackServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StackApi::class, function () {
            return new StackApi(config('stack.baseurl'), config('stack.shareid'), config('stack.sharefolder'), config('stack.username'), config('stack.password'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            StackApi::class
        ];
    }
}
