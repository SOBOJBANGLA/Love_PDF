<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfMergeController;
use App\Http\Controllers\PdfSplitController;
use App\Http\Controllers\PdfCompressController;
use App\Http\Controllers\PdfConvertController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // PDF Tools Routes
            Route::prefix('tools')->group(function () {
                Route::get('/merge', [PdfMergeController::class, 'show'])->name('tools.merge');
                Route::post('/merge/process', [PdfMergeController::class, 'process'])->name('tools.merge.process');
                
                Route::get('/split', [PdfSplitController::class, 'show'])->name('tools.split');
                Route::post('/split/process', [PdfSplitController::class, 'process'])->name('tools.split.process');
                
                Route::get('/compress', [PdfCompressController::class, 'show'])->name('tools.compress');
                Route::post('/compress/process', [PdfCompressController::class, 'process'])->name('tools.compress.process');
                
                Route::get('/convert', [PdfConvertController::class, 'show'])->name('tools.convert');
                Route::post('/convert/process', [PdfConvertController::class, 'process'])->name('tools.convert.process');
            });
        });
    }
}
