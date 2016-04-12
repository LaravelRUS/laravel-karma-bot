<?php

namespace Core\Providers;

use Doctrine\Common\Annotations\AnnotationReader;
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
        AnnotationReader::addGlobalIgnoredName('readonly');
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
