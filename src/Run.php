<?php

namespace Async;
use Async\Req;

/**
 * Run curl resquest
 * @author Mateodioev
 * @link https://github.com/Mateodioev
 */
class Run {
  
  private static array $headersCallBack;
  private static $ms = 10;
  private static $Xch;
  public static $total = 0;
  public static $response;
  public static $data;
  public static $info;
  public static $errorStr;
  public static $errorCode;

  /**
   * Run all resquest with async
   * @return array
   */
  final public static function Async(array $ch, $ms = 10) {
    self::$ms = $ms;
    self::$Xch = $ch;
    self::$total = count($ch);
    self::MultiMakeStdClass(self::$total);
    
    for ($i=0; $i < self::$total; $i++) { 
      Req::SetOpt($ch[$i], [CURLOPT_HEADERFUNCTION => createHeaderCallback(self::$headersCallBack[$i])]);
      curl_multi_add_handle(Req::$mh, $ch[$i]);
    }

    // Ejecuta los recursos
    do {
      $execReturnValue = curl_multi_exec(Req::$mh, $runningHandles);
    } while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);

    // Loop and continue processing the request
    while ($runningHandles && $execReturnValue == CURLM_OK) {
      // !!!!! changed this if and the next do-while !!!!!

      if (curl_multi_select(Req::$mh) != -1) usleep(self::$ms);

      do {
          $execReturnValue = curl_multi_exec(Req::$mh, $runningHandles);
      } while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);
    }

    // Check for any errors
    if ($execReturnValue != CURLM_OK) trigger_error("Curl multi read error $execReturnValue\n", E_USER_WARNING);

    for ($i=0; $i < self::$total; $i++) { 
      self::$info[$i]     = curl_getinfo($ch[$i]);
      self::$response[$i] = curl_multi_getcontent($ch[$i]);
    }
    self::Debug();
    curl_multi_close(Req::$mh);

    self::$data['took'] = self::GetTook();
    self::$data['req'] = self::$total;

    return self::$data;
  }

  private static function Debug() : void {
    
    for ($i=0; $i < self::$total; $i++) { 
      
      if (self::$response[$i] === false) {
        $error_code = curl_errno(self::$Xch[$i]);
        $error_string = curl_error(self::$Xch[$i]);

        self::$data['data'][$i] = [
          'ok'      => false,
          'code'    => self::$info[$i]['http_code'],
          'headers' => [
            'request'  => key_exists('request_header', self::$info[$i]) ? self::parseHeadersHandle(self::$info[$i]['request_header']) : [],
            'response' => self::parseHeadersHandle(self::$headersCallBack[$i]->rawResponseHeaders)
          ],
          'errno'   => $error_code,
          'error'   => $error_string,
          'body'    => 'Error: '.$error_string
        ];

      } else {
        self::$data['data'][$i] = [
          'ok'      => true,
          'code'    => self::$info[$i]['http_code'],
          'headers' => [
            'request'  => self::parseHeadersHandle(self::$info[$i]['request_header']),
            'response' => self::parseHeadersHandle(self::$headersCallBack[$i]->rawResponseHeaders)
          ],
          'body'    => self::$response[$i]
        ];

      }

      // Remove and close the handle
      curl_multi_remove_handle(Req::$mh, self::$Xch[$i]);
      curl_close(self::$Xch[$i]);
    }
  }

  /**
   * Get took script
   */
  private static function GetTook($round = 5) {
    $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
    return round($time, $round);
  }

  /**
   * Create a placeholder to temporarily store the header callback data.
   */
  private static function MultiMakeStdClass(int $total) : void {
    for ($i=0; $i < $total; $i++) { 
      $hcd = new \stdClass();
      $hcd->rawResponseHeaders = '';
      self::$headersCallBack[$i] = $hcd;
    }
  }

  /**
   * Parse Headers
   */
  private static function parseHeaders(string $raw) : array {
    $raw = preg_split('/\r\n/', $raw, -1, PREG_SPLIT_NO_EMPTY);
    $http_headers = [];
    
    for($i = 1; $i < count($raw); $i++) {
      if (strpos($raw[$i], ':') !== false) {
        list($key, $value) = explode(':', $raw[$i], 2);
        $key = trim($key);
        $value = trim($value);
        isset($http_headers[$key]) ? $http_headers[$key] .= ',' . $value : $http_headers[$key] = $value;
      }
    }

    return [$raw['0'] ??= $raw['0'], $http_headers];
  }

  /**
   * Parse Array
   */
  private static function parseArray(array $raw) : array {
    if (array_key_exists('request_header', $raw)) {
      list($scheme, $headers) = self::parseHeaders($raw['request_header']);
      $nh['scheme'] = $scheme;
      $nh += $headers;
      $raw['request_header'] = $nh;
    }

    return $raw;
  }

  /**
   * Parse Headers Handle
   */
  private static function parseHeadersHandle($raw) : array {
    if (empty($raw))
      return [];

    list($scheme, $headers) = self::parseHeaders($raw);
    $request_headers['scheme'] = $scheme;
    unset($headers['request_header']);
    
    foreach ($headers as $key => $value) {
      $request_headers[$key] = $value;
    }

    return $request_headers;
  }
}


/**
 * Local createHeaderCallback 
 */
function createHeaderCallback($headersCallBack) {
  return function ($_, $header) use ($headersCallBack) {
      $headersCallBack->rawResponseHeaders .= $header;
      return strlen($header);
  };
}
