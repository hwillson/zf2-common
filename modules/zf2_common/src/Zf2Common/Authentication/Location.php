<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Authentication;

/**
 * Request location helper.
 *
 * @package  Zf2Common
 */
class Location {

  /**
   * Get the current users public facing IP.
   *
   * @return  string  Users public facing IP.
   */
  public static function getPublicIp() {

    $publicIp = null;
    if (isset($_SERVER["REMOTE_ADDR"])) {
      $publicIp = $_SERVER["REMOTE_ADDR"];
    } else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
      $publicIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
      $publicIp = $_SERVER["HTTP_CLIENT_IP"];
    }
    return $publicIp;

  }

}
