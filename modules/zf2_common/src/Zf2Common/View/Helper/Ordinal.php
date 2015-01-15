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

use Zend\View\Helper\AbstractHelper;

/**
 * Return the ordinal representation of a number.  Note - the same thing can be
 * achieved in newer versions of PHP, using the NumberFormatter class and
 * NumberFormatter::ORDINAL.  Unfortunately the resulting ordinal characters
 * are not serializable, and can't be stored in the session properly.
 *
 * @package  Zf2Common
 */
class Ordinal extends AbstractHelper {

  /**
   * Return the ordinal representation of the passed in number, when invoked.
   *
   * @param   int  $num  Number to show as ordinal.
   * @return  string  Ordinal number representation.
   */
  public function __invoke($num) {
    if ( ($num / 10) % 10 != 1 ) {
      switch( $num % 10 ) {
        case 1: return $num . 'st';
        case 2: return $num . 'nd';
        case 3: return $num . 'rd';
      }
    }
    return $num . 'th';
  }

}
