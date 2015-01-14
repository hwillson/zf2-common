<?php

namespace Zf2Common\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Return the ordinal representation of a number.  Note - the same thing can be
 * achieved in newer versions of PHP, using the NumberFormatter class and
 * NumberFormatter::ORDINAL.  Unfortunately the resulting ordinal characters
 * are not serializable, and can't be stored in the session properly.
 */
class Ordinal extends AbstractHelper {

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