<?php

namespace  Zcrud\Zcrud;

use Illuminate\Support\ServiceProvider;

class ZCRUDServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CRUDCommand::class,
            ]);
        }
    }
}