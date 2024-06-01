<?php

namespace KiriminWa\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * KiriminWa Sms Facade
 *
 * @method static \KiriminWa\HttpClient\Response sendSms(string $receiver, string $message, bool $isUnicode)
 */
class Sms extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'kiriminwa.sms';
  }
}
