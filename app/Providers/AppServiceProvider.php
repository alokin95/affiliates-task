<?php

namespace App\Providers;

use App\Application\AffiliateDistanceService;
use App\Application\AffiliateDistanceServiceInterface;
use App\Core\Storage\FileReaderInterface;
use App\Domain\Affiliates\AffiliateSourceInterface;
use App\Infrastructure\FileAffiliateSource;
use App\Infrastructure\Storage\LaravelFileReader;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AffiliateDistanceServiceInterface::class, AffiliateDistanceService::class);
        $this->app->bind(FileReaderInterface::class, LaravelFileReader::class);
        $this->app->bind(AffiliateSourceInterface::class, function ($app) {
            $filePath = config('affiliates.file_path');
            if (!is_string($filePath)) {
                throw new \RuntimeException('Expected affiliates.file_path to be a string, got ' . gettype($filePath));
            }
            return new FileAffiliateSource(
                $app->make(FileReaderInterface::class),
                $filePath
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }
}
