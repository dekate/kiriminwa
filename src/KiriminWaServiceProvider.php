<?php

namespace KiriminWa;

use Illuminate\Support\ServiceProvider;

class KiriminWaServiceProvider extends ServiceProvider
{
  /**
   * Publishes configuration file.
   *
   * @return  void
   */
  public function boot()
  {
    $this->publishes([
      __DIR__ . '/config/kiriminwa.php' => config_path('kiriminwa.php'),
    ], ['kiriminwa']);
  }
  /**
   * Make config publishment optional by merging the config from the package.
   *
   * @return  void
   */
  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__ . '/config/kiriminwa.php',
      'kiriminwa'
    );
    $this->app->bind('kiriminwa.whatsapp', function () {
      return new Whatsapp(config('kiriminwa.api-key'), config('kiriminwa.base-url'));
    });
    $this->app->bind('kiriminwa.sms', function () {
      return new Sms(config('kiriminwa.api-key'), config('kiriminwa.base-url'));
    });
    $this->app->bind('kiriminwa.email', function () {
      return new Email(config('kiriminwa.api-key'), config('kiriminwa.base-url'));
    });
  }
}
