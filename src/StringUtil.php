<?php

namespace Async;


class StringUtil
{
  
    /**
   * Cambiar el tipo de dato segun el tipo
   * @param mixed $data
   */
  public static function DataType($data, bool $encode = true) {
    if (empty($data)) {
      return false;
    } elseif (is_array($data) || is_object($data)) {
      return ($encode) ? json_encode($data) : http_build_query($data);
    } else {
      return $data;
    }
  }

  /**
   * Obtener un elemento aleatorio de un array
   */
  public static function GetRandArr(array $input) : string {
    $input = $input[array_rand($input)];
    return trim($input);
  }

  /**
   * Get a rand value from specify file.txt
   * @return string
   */
  public static function GetRandVal(string $file) : string {
    $_ = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return self::GetRandArr($_);
  }

  /**
   * Duplica un string o un array
   * @param array|object|string|int $str A duplicar 
   * @param integer $cant Cantidad de veces a duplicar
   */
  public static function Duplicate($str, int $cant = 10) : array {
    $new = array();
    for ($i=0; $i < $cant; $i++) { 
      $new[] = $str;
    }
    return $new;
  }

  /**
   * Detect array of arrays
   */
  public static function ArrayOfArrays($input) : bool {
    if (is_string($input) || empty($input) || is_bool($input) || is_null($input)) return false;
    if (is_array($input)) {
      foreach ($input as $value) {
        if (!is_array($value)) return false;
      }
      return true;
    }
    return false;
  }

  public static function UserAgent() {
    $uas = [
      "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:83.0) Gecko/20100101 Firefox/83.0",
      "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:81.0) Gecko/20100101 Firefox/81.0",
      "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0",
      "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0",
      "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:81.0) Gecko/20100101 Firefox/81.0",
      "Mozilla/5.0 (X11; Linux x86_64; rv:80.0) Gecko/20100101 Firefox/80.0",
      "Mozilla/5.0 (X11; Linux x86_64; rv:75.0) Gecko/20100101 Firefox/75.0",
      "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:79.0) Gecko/20100101 Firefox/79.0",
      "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:77.0) Gecko/20100101 Firefox/77.0",
      "Mozilla/5.0 (X11; U; Linux i686; fr; rv:1.8) Gecko/20060110 Debian/1.5.dfsg-4 Firefox/1.5",
      "Mozilla/5.0 (Android 10; Mobile; rv:79.0) Gecko/79.0 Firefox/79.0",
      "Mozilla/5.0 (Android 9; Mobile; rv:68.6.0) Gecko/68.6.0 Firefox/68.6.0",
      "Mozilla/5.0 (Android 7.1.1; Mobile; rv:68.0) Gecko/68.0 Firefox/68.0",
      "Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_2 like Mac OS X) AppleWebKit/603.2.4 (KHTML, like Gecko) FxiOS/7.5b3349 Mobile/14F89 Safari/603.2.4",
      "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
      "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.1 Safari/605.1.15",
      "Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Snapchat/10.77.5.59 (like Safari/604.1)",
      "Mozilla/5.0 (iPhone; CPU iPhone OS 13_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/80.0.3987.95 Mobile/15E148 Safari/604.1"
    ];
    return self::GetRandArr($uas);
  }

}