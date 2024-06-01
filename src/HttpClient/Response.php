<?php
namespace KiriminWa\HttpClient;

class Response
{
  public $statusCode;
  public $headers;
  public $body;

  public function __construct(int $statusCode, array $headers, $body)
  {
    $this->statusCode = $statusCode;
    $this->headers = $headers;
    $this->body = $body;
  }

  public function ok(): bool
  {
    return $this->statusCode < 400;
  }
}
