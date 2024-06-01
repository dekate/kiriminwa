<?php

namespace KiriminWa\HttpClient;

interface HttpClient
{
  public function get(
    string $url,
    array $headers,
    array $params = null
  );

  public function post(
    string $url,
    array $headers,
    array $body,
    string $formType = 'json'
  );

  public function put(
    string $url,
    array $headers,
    array $body,
    string $formType = 'json'
  );

  public function patch(
    string $url,
    array $headers,
    array $body,
    string $formType = 'json'
  );

  public function delete(
    string $url,
    array $headers
  );
}
