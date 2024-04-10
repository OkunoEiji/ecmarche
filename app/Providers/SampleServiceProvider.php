<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SampleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        app()->bind('serviceProviderTest', function(){
            return 'サービスプロバイダーのテスト';
        });
    }

    public function boot(): void
    {
        //
    }
}
