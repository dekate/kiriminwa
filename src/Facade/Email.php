<?php

namespace KiriminWa\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * KiriminWa Email Facade
 *
 * @method static \KiriminWa\HttpClient\Response sendEmail(string $receiver, string $subject, string $message, string $sender, string $replyTo)
 */
class Email extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'kiriminwa.email';
  }
}
