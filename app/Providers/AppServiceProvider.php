<?php

namespace App\Providers;

use App\StackFile;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::extend(function ($value) {
            return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value);
        });

        Response::macro('stackView', function (StackFile $file) {
            $stack = resolve('App\Stack\StackApi');
            return $stack->presentFile($file, false);
        });

        Response::macro('stackDownload', function (StackFile $file) {
            $stack = resolve('App\Stack\StackApi');
            return $stack->presentFile($file, true);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
