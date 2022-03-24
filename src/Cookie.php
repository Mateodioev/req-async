<?php

namespace Async;

use Async\Req;

/**
 * Add a cookies to the request.
 * @author Mateodioev
 * @link https://github.com/Mateodioev
 */
class Cookie {
  
  public static $tmp_dir;

  public const dirSe = DIRECTORY_SEPARATOR;

  /**
   * Establecer el directorio para guardar las cookies
   */
  public static function SetDir() {
    self::$tmp_dir = dirname(__DIR__).self::dirSe.'Cache'.self::dirSe;

    if (!is_dir(self::$tmp_dir)) mkdir(self::$tmp_dir, 0755);
    return self::$tmp_dir;
  }

  /**
   * Establecer un archivo en cURL para ser usado como cookie
   */
  public static function Set(string $cookie_name) {
    $cookie_file = sprintf('%sCookie_%s.txt', self::$tmp_dir, $cookie_name);
    if (!is_writable(self::$tmp_dir)) {
      trigger_error("The current directory is not writable, please add permissions 0755 to Cache dir and 0644 to Cookie.php", E_WARNING);
      return;
    }

    Req::SetOpt(Req::$ch, [
      CURLOPT_COOKIEJAR  => $cookie_file,
      CURLOPT_COOKIEFILE => $cookie_file
    ]);
  }

  /**
   * Borra un archivo especifico si existe
   */
  public static function Delete(string $cookie_name, $sprinf = false)
  {
    $cookie_name = ($sprinf) ? sprintf('%sCookie_%s.txt', self::$tmp_dir, $cookie_name) : $cookie_name;
    if (is_file($cookie_name)) unlink($cookie_name);
  }

  /**
   * Elimina todas las cookies de un fichero
   */
  public static function DeleteAll(string $type = '*.txt') {
    if (is_dir(self::$tmp_dir)) {
      foreach (glob(self::$tmp_dir.$type) as $value) {
        self::Delete($value);
      }
    } else {
      trigger_error(self::$tmp_dir . ' no es un fichero valido', E_WARNING);
    }
  }
}


Cookie::SetDir();
