<?php

namespace App\Providers;

use App\Services\KafkaProducer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->singleton(KafkaProducer::class, function ($app) {
      return new KafkaProducer();
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
