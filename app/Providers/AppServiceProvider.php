<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tanggal Carbon otomatis bahasa Indonesia (Senin, 20 Januari...)
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        // Mencegah error key too long pada MySQL versi lama
        Schema::defaultStringLength(191);

        // Mode Strict (Local environment)
        Model::shouldBeStrict(!app()->isProduction());
    }
}