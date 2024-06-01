<?php

namespace KiriminWa;

use KiriminWa\HttpClient\HttpClient;
use KiriminWa\HttpClient\HttpCurl;
use KiriminWa\HttpClient\HttpGuzzle;
use KiriminWa\HttpClient\HttpLaravel;
use KiriminWa\HttpClient\Response;

class Sms
{
  private HttpClient $httpClient;
  private string $baseUrl = "https://sender.dekacare.id/api";
  private string $apiKey = "";

  /**
   * KiriminWa SMS
   *
   * @param string $apiKey
   * @param string $baseUrl
   * @param boolean $forceHttpClient OPTIONAL valid values are ['guzzle', 'curl]. otherwise default to auto detection
   */
  public function __construct(string $apiKey, string $baseUrl = "https://sender.dekacare.id/api", $forceHttpClient = false)
  {
    $this->baseUrl = $baseUrl . '/sms';
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
        'contact' => $data
      ]
    );
  }

  /**
   * Send SMS
   *
   * @param string $receiver
   * @param string $message
   * @return Response
   */
  public function sendSms(string $receiver, string $message, bool $isUnicode): Response
  {
    if (str_starts_with($receiver, '0') || !preg_match("/^\d+$/", $receiver))
      throw new \Exception('Receiver should not start with 0 or has non numeric characters (including + at the beginning)');
    $content = [
      "number" => $receiver,
      "body" => $message,
      "sms_type" => $isUnicode ? 'unicode' : 'plain',
    ];
    return $this->post($content);
  }

  public function log($uid)
  {
    $url = str_replace('/sms', '', $this->baseUrl);
    $data = $this->httpClient->get(
      "$url/get/sms/$uid",
      ['Api-key' => $this->apiKey]
    );
    return $data;
  }
}
