<?php

namespace App\Providers;

use App\Jobs\InformationRequest;
use App\Models\Order;
use Illuminate\Support\ServiceProvider;

class JobServiceProvider  extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bindMethod([InformationRequest::class, 'handle'], function ($job, $app) {
            return $job->handle($app->make(Order::class));
        });
    }
}
