<?php

namespace KiriminWa;

use KiriminWa\HttpClient\HttpClient;
use KiriminWa\HttpClient\HttpCurl;
use KiriminWa\HttpClient\HttpGuzzle;
use KiriminWa\HttpClient\HttpLaravel;
use KiriminWa\HttpClient\Response;

class Whatsapp
{
  private HttpClient $httpClient;
  private string $baseUrl = "https://sender.dekacare.id/api";
  private string $apiKey = "";

  /**
   * KiriminWa Whatsapp
   *
   * @param string $apiKey
   * @param string $baseUrl
   * @param boolean $forceHttpClient OPTIONAL valid values are ['guzzle', 'curl]. otherwise default to auto detection
   */
  public function __construct(string $apiKey, string $baseUrl = "https://sender.dekacare.id/api", $forceHttpClient = false)
  {
    $this->baseUrl = $baseUrl . '/whatsapp';
    $this->apiKey = $apiKey;
    $isLaravel = class_exists('\Illuminate\Support\Facades\Facade');
    $isGuzzle = class_exists('\GuzzleHttp\Client');
    if ($forceHttpClient === 'guzzle') {
      $isLaravel = false;
      $isGuzzle = true;
    }
    if ($forceHttpClient === 'curl') {
      $isLaravel = false;
      $isGuzzle = false;
    }
    if ($isLaravel) {
      $this->httpClient = new HttpLaravel();
    } elseif ($isGuzzle) {
      $this->httpClient = new HttpGuzzle();
    } else {
      $this->httpClient = new HttpCurl();
    }
  }

  private function post(array $data = [])
  {
    return $this->httpClient->post(
      "$this->baseUrl/send",
      ['Api-key' => $this->apiKey],
      [
        'contact' => [$data]
      ]
    );
  }

  /**
   * Send Text Message
   *
   * @param string $receiver
   * @param string $message
   * @return Response
   */
  public function sendMessage(string $receiver, string $message): Response
  {
    if (str_starts_with($receiver, '0') || !preg_match("/^\d+$/", $receiver))
      throw new \Exception('Receiver should not start with 0 or has non numeric characters (including + at the beginning)');
    $content = [
      "number" => $receiver,
      "message" => $message,
    ];
    return $this->post($content);
  }

  /**
   * Send File
   *
   * @param string $receiver
   * @param string $imageUrl
   * @param string $type valid types: "image", "audio", "video", "document"
   * @param string $caption
   * @return Response
   */
  public function sendFile(string $receiver, string $imageUrl, string $type, string $caption = ""): Response
  {
    if (str_starts_with($receiver, '0') || !preg_match("/^\d+$/", $receiver))
      throw new \Exception('Receiver should not start with 0 or has non numeric characters (including + at the beginning)');
    $content = [
      "number" => $receiver,
      "message" => $caption,
      "media" => $type,
      "url" => $imageUrl,
    ];
    return $this->post($content);
  }

  public function sendImage(string $receiver, string $imageUrl, string $caption = ""): Response
  {
    return $this->sendFile($receiver, $imageUrl, "image", $caption);
  }

  public function sendAudio(string $receiver, string $audioUrl, string $caption = ""): Response
  {
    return $this->sendFile($receiver, $audioUrl, "audio", $caption);
  }

  public function sendVideo(string $receiver, string $videoUrl, string $caption = ""): Response
  {
    return $this->sendFile($receiver, $videoUrl, "video", $caption);
  }

  public function sendDocument(string $receiver, string $documentUrl, string $caption = ""): Response
  {
    return $this->sendFile($receiver, $documentUrl, "document", $caption);
  }

  public function log($uid)
  {
    $url = str_replace('/whatsapp', '', $this->baseUrl);
    $data = $this->httpClient->get(
      "$url/get/whatsapp/$uid",
      ['Api-key' => $this->apiKey]
    );
    return $data;
  }
}
