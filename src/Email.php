<?php

namespace KiriminWa;

use KiriminWa\HttpClient\HttpClient;
use KiriminWa\HttpClient\HttpCurl;
use KiriminWa\HttpClient\HttpGuzzle;
use KiriminWa\HttpClient\HttpLaravel;
use KiriminWa\HttpClient\Response;

class Email
{
  private HttpClient $httpClient;
  private string $baseUrl = "https://sender.dekacare.id/api";
  private string $apiKey = "";

  /**
   * KiriminWa Email
   *
   * @param string $apiKey
   * @param string $baseUrl
   * @param boolean $forceHttpClient OPTIONAL valid values are ['guzzle', 'curl]. otherwise default to auto detection
   */
  public function __construct(string $apiKey, string $baseUrl = "https://sender.dekacare.id/api", $forceHttpClient = false)
  {
    $this->baseUrl = $baseUrl . '/email';
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
   * Send Email
   *
   * @param string $receiver
   * @param string $message
   * @return Response
   */
  public function sendEmail(string $receiver, string $subject, string $message, string $sender, string $replyTo): Response
  {
    $content = [
      "subject" => $subject,
      "email" => $receiver,
      "message" => $message,
      "sender_name" => $sender,
      "reply_to_email" => $replyTo
    ];
    return $this->post($content);
  }

  public function log($uid)
  {
    $url = str_replace('/email', '', $this->baseUrl);
    $data = $this->httpClient->get(
      "$url/get/email/$uid",
      ['Api-key' => $this->apiKey]
    );
    return $data;
  }
}
