<?php

namespace Async;

use Async\Req;

/**
 * Add proxy to request
 * @author Mateodioev
 * @link https://github.com/Mateodioev
 */
class Proxy {
  
  /**
   * Detect the tunnel configuration
   */
  public static function AutoRouter(?array $arg) : void {
    if ($arg == null) return;
    switch ($arg['METHOD']) {
      case 'TUNNEL': self::Tunel($arg); break;
      case 'CUSTOM': self::Auth($arg); break;
    }
  }


  /**
   * Set a proxy tunnel configuration to current curl structure, support: HTTP/S, SOCKS4, SOCKS5
   */
  private static function Tunel(?array $args) : void {
    if ($args == null) return;
    Req::SetOpt(Req::$ch, [
      CURLOPT_PROXY => $args['SERVER'],
      CURLOPT_HTTPPROXYTUNNEL => true
    ]);
  }

  /**
   * Proxy auth
   */
  private static function Auth (?array $args) : void {
    if ($args == null) return;
    Req::SetOpt(Req::$ch, [
      CURLOPT_PROXY => $args['SERVER'],
      CURLOPT_PROXYUSERPWD => $args['AUTH']
    ]);
  }
}
