<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     * ホームルートのパス
     * Typically, users are redirected here after authentication.
     * 通常、「ユーザー」の認証後に'/dashboard'にリダイレクトされます。
     * 　　　「オーナー」の認証後に'/owner/dashboard'にリダイレクト。（public const OWNER_HOME）
     * 　　　「管理者」の認証後に'/admin/dashboard'にリダイレクト。（public const ADMIN_HOME）
     * @var string
     */
    public const HOME = '/';
    public const OWNER_HOME = '/owner/dashboard';
    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     * ルート、モデル、バインディング、パターン、フィルター、およびその他のルート構成を定義します。
     * 
     * サービスプロバイダが読み込まれた後に、実行される内容。
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // フロント側を全て、javascriptなどで作るパターン
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // 管理者のルート情報
            Route::prefix('admin')
                ->as('admin.')
                ->middleware('web')
                ->group(base_path('routes/admin.php'));

            // オーナーのルート情報
            Route::prefix('owner')
                ->as('owner.')
                ->middleware('web')
                ->group(base_path('routes/owner.php'));
            
            // ユーザーのルート情報
            // Laravelでビュー側を表示して、レスポンスを返すパターン（現在使用している）
            Route::prefix('/')
                ->as('user.')
                ->middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
