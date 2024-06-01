<?php

namespace KiriminWa\HttpClient;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response as LaravelResponse;

class HttpLaravel implements HttpClient
{
  public function get(string $url, array $headers, array $queries = null)
  {
    $response = Http::withHeaders($headers)->get($url, $queries);
    return $this->toCustomResponse($response);
  }

  public function post(string $url, array $headers, array $body, string $formType = 'json')
  {
    return $this->sendData('post', $url, $headers, $body, $formType);
  }

  public function put(string $url, array $headers, array $body, string $formType = 'json')
  {
    return $this->sendData('put', $url, $headers, $body, $formType);
  }

  public function patch(string $url, array $headers, array $body, string $formType = 'json')
  {
    return $this->sendData('patch', $url, $headers, $body, $formType);
  }

  public function delete(string $url, array $headers)
  {
    return Http::withHeaders($headers)->delete($url);
  }

  private function sendData($method, $url, $headers, $body, $formType)
  {
    $http = Http::withHeaders($headers);
    switch ($formType) {
      case "multipart":
        $http->asMultipart();
        break;
      case "form":
        $http->asForm();
        break;
      default:
        $http->asJson();
    }

    $response = $http->$method($url, $body);

    return $this->toCustomResponse($response);
  }

  private function toCustomResponse(LaravelResponse $response)
  {
    $body = '';
    try {
      $body = json_decode($response->body());
    } catch (Exception $e) {
      $body = $response->body();
    }
    return new Response(
      $response->status(),
      $response->headers(),
      $body,
    );
  }
}
