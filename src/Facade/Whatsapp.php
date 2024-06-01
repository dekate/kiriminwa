<?php

namespace KiriminWa\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * KiriminWa Whatsapp Facade
 *
 * @method static \KiriminWa\HttpClient\Response sendMessage(string $receiver, string $message)
 * @method static \KiriminWa\HttpClient\Response sendFile(string $receiver, string $imageUrl, string $type, string $caption = "")
 * @method static \KiriminWa\HttpClient\Response sendImage(string $receiver, string $imageUrl, string $caption = "")
 * @method static \KiriminWa\HttpClient\Response sendAudio(string $receiver, string $audioUrl, string $caption = "")
 * @method static \KiriminWa\HttpClient\Response sendVideo(string $receiver, string $videoUrl, string $caption = "")
 * @method static \KiriminWa\HttpClient\Response sendDocument(string $receiver, string $documentUrl, string $caption = "")
 */
class Whatsapp extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'kiriminwa.whatsapp';
  }
}
