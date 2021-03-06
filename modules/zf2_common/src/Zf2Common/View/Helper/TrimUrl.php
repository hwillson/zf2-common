<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\View\Helper;

use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\View\Helper\AbstractHelper;

/**
 * Trim a URL for display.
 *
 * @package  Zf2Common
 */
class TrimUrl extends AbstractHelper {

  /**
   * Trim a URL for display up to the specified size, removing any http/https
   * scheme identifiers. Will suffix with "...".
   *
   * @param   string  $url  URL.
   * @param   int  $size  Trim up to this size.
   * @return  string  Trimmed URL.
   */
  public function __invoke($url, $size) {

    if ($size < 0) {
      throw new InvalidArgumentException('Invalid size parameter.');
    }

    $trimmedUrl = null;
    if ($url) {
      $trimmedUrl = str_replace('http://', '', $url);
      $trimmedUrl = str_replace('https://', '', $trimmedUrl);
      if (substr($trimmedUrl, -1) == '/') {
        $trimmedUrl = substr($trimmedUrl, 0, -1);
      }
      if (strlen($trimmedUrl) > $size) {
        $trimmedUrl = substr($trimmedUrl, 0, $size) . '...';
      }
    }

    return $trimmedUrl;

  }

}

?>
