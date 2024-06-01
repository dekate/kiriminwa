<?php

namespace KiriminWa\HttpClient;

class HttpCurl implements HttpClient
{
  public function get(string $url, array $headers, array $params = null)
  {
    return $this->sendData('GET', $url, $headers, $params);
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

  public function delete(string $url, array $headers, string $formType = 'json')
  {
    return $this->sendData('DELETE', $url, $headers, $formType);
  }

  private function sendData($method, $url, $headers, $body = null, $formType = null)
  {
    $curl = curl_init();
    if ($method !== 'GET') {
      if ($formType === 'json') {
        $body = json_encode($body);
        $headers['Content-Type'] = 'application/json';
        $headers['Content-Length'] = strlen($body);
      } elseif ($formType === 'multipart') {
        // DO NOTHING
      } else {
        $body = http_build_query($body);
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $headers['Content-Length'] = strlen($body);
      }
    }
    $tHeaders = [];
    foreach ($headers as $key => $value) {
      $tHeaders[] = sprintf("%s: %s", $key, $value);
    }

    switch ($method) {
      case "POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($body)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        break;
      case "PUT":
      case "PATCH":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($body)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        break;
      case "GET":
        if ($body)
          $url = sprintf("%s?%s", $url, http_build_query($body));
        break;
      default:
        if ($body)
          $url = sprintf("%s?%s", $url, http_build_query($body));
    }

    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $tHeaders);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);

    $result = curl_exec($curl);

    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $responseHeaders = substr($result, 0, $headerSize);
    $body = substr($result, $headerSize);

    curl_close($curl);

    $headerArray = $this->getHeadersAsArray($responseHeaders);

    return new Response($statusCode, $headerArray, $body);
  }

  private function getHeadersAsArray($rawHeaders)
  {
    $headers = array();

    // Split the string on every "double" new line
    $arrRequests = explode("\r\n\r\n", $rawHeaders);

    // Loop of response headers. The "count() -1" is to avoid an empty row for the extra line break before the body of the response.
    for ($index = 0; $index < count($arrRequests) - 1; $index++) {

      foreach (explode("\r\n", $arrRequests[$index]) as $i => $line) {
        if ($i === 0)
          $headers[$index]['http_code'] = $line;
        else {
          list($key, $value) = explode(': ', $line);
          $headers[$index][$key] = $value;
        }
      }
    }

    return $headers;
  }
}
