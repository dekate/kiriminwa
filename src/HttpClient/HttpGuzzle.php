<?php

namespace KiriminWa\HttpClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class HttpGuzzle implements HttpClient
{
  private $client;

  public function __construct()
  {
    $this->client = new Client();
  }

  public function get(string $url, array $headers, array $queries = null)
  {
    $params = ['headers' => $headers];
    if ($queries) {
      $params['query'] = $queries;
    }
    $response = $this->client->request('GET', $url, $params);

    return $this->toCustomResponse($response);
  }

  public function post(string $url, array $headers, array $body, string $formType = 'json')
  {
    return $this->sendData('POST', $url, $headers, $body, $formType);
  }

  public function put(string $url, array $headers, array $body, string $formType = 'json')
  {
    return $this->sendData('PUT', $url, $headers, $body, $formType);
  }

  public function patch(string $url, array $headers, array $body, string $formType = 'json')
  {
    return $this->sendData('PATCH', $url, $headers, $body, $formType);
  }

  public function delete(string $url, array $headers)
  {
    return $this->client->request('DELETE', $url, ['headers' => $headers]);
  }

  private function sendData($method, $url, $headers, $body, $formType)
  {
    $options = ['headers' => $headers];
    switch ($formType) {
      case 'multipart':
        $options['multipart'] = $body;
        break;
      case 'form':
        $options['form_params'] = $body;
        break;
      default:
        $options['json'] = $body;
        break;
    }
    $response = $this->client->request($method, $url, $options);

    return $this->toCustomResponse($response);
  }

  private function toCustomResponse(GuzzleResponse $response)
  {
    $body = '';
    try {
      $body = json_decode($response->getBody()->getContents());
    } catch (Exception $e) {
      $body = $response->getBody()->getContents();
    }
    return new Response(
      $response->getStatusCode(),
      $response->getHeaders(),
      $body,
    );
  }
}
