<?php

namespace App\Providers;

use App\Core\Storage\FileReaderInterface;
use App\Domain\Affiliates\AffiliateSourceInterface;
use App\Infrastructure\FileAffiliateSource;
use App\Infrastructure\Storage\LaravelFileReader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FileReaderInterface::class, LaravelFileReader::class);
        $this->app->bind(AffiliateSourceInterface::class, function ($app) {
            return new FileAffiliateSource(
                $app->make(FileReaderInterface::class),
                config('affiliates.file_path')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
