<?php

namespace Async;

use Async\Cookie;
use Async\Headers;
use Async\Proxy;
use Async\StringUtil;


/**
 * Create curl resquest
 * @author Mateodioev
 * @link https://github.com/Mateodioev
 */
class Req
{
  
  private static $default = [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => false,
    CURLINFO_HEADER_OUT    => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_AUTOREFERER    => true,
    CURLOPT_CONNECTTIMEOUT => 30,
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0
  ];

  public static $mh;
  public static $ch;

  public function __construct() {
    self::$mh = curl_multi_init();
  }

  public static function SetOpt($ch, array $options) : void {
    curl_setopt_array($ch, $options);
  }

  /**
   * Init curl and add default config
   */
  private static function Init(string $url) : void {
    if (self::$ch != null) self::$ch = null;
    self::$ch = curl_init($url);
    self::SetOpt(self::$ch, self::$default);
  }

  /**
   * Send a GET request method with headers, cookies and server tunnel
   * @return CurlHandler
   */
  public static function Get(string $url, ?array $headers=NULL, ?array $server=NULL, ?string $cookie=NULL) {
    self::Init($url);
    self::CheckParam($headers, $cookie, $server);
    self::SetOpt(self::$ch, [CURLOPT_USERAGENT => StringUtil::UserAgent()]);
    return self::$ch;
  }

  /**
   * Send a POST request method with custom post data, headers, cookies and server tunnel
   * @return CurlHandler
   */
  public static function Post(string $url, $data=NULL, ?array $headers=NULL, ?array $server=NULL, ?string $cookie=NULL) {
    self::Init($url);
    self::CheckParam($headers, $cookie, $server);
    self::SetOpt(self::$ch, [
      CURLOPT_USERAGENT => StringUtil::UserAgent(),
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => StringUtil::DataType($data)
    ]);
    return self::$ch;
  }

  public static function __callStatic($name, $args)
  {
    self::Init($args[0]);
    self::CheckParam(@$args[2], @$args[4], @$args[3]);
    self::SetOpt(self::$ch, [
      CURLOPT_CUSTOMREQUEST => strtoupper($name),
      CURLOPT_USERAGENT => StringUtil::UserAgent(),
      CURLOPT_POSTFIELDS => StringUtil::DataType(@$args[1])
    ]);
    return self::$ch;
  }

  /**
   * Add headers, cookie, and proxy
   */
  private static function CheckParam($header, $cookie, $server) : void{
    
    if ($header != null && is_array($header)) {
      Headers::Add($header);
    }
    if ($cookie != null && is_string($cookie)) {
      Cookie::Set($cookie);
    }
    if ($server != null && is_array($server)) {
      Proxy::AutoRouter($server);
    }
  }

}
