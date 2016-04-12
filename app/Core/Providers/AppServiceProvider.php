<?php

namespace Core\Providers;

use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\DateTimeZone::class, function() {
            return Carbon::now()->getTimezone();
        });

        $this->app->bind(\DateTime::class, function(Container $app) {
            return new \DateTime('now', $app->make(\DateTimeZone::class));
        });
    }
}
