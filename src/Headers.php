<?php

namespace Async;

use Async\Req;

/**
 * Add headers to request
 * @author Mateodioev
 * @link https://github.com/Mateodioev
 */
class Headers {

  /**
   * Añadir el array de headers al actual CurlHandle
   */
  public static function Add(array $header) : void {
    if ($header == null) return;
    Req::SetOpt(Req::$ch, [CURLOPT_HTTPHEADER => $header]);
  }
  
}