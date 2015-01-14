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

use Zend\Session\Container;
use Zend\View\Helper\AbstractHelper;

class Session extends AbstractHelper {

  protected $session;

  public function __invoke() {
    return $this->session;
  }

  public function getSession() {
    return $this->session;
  }

  public function setSession(Container $session) {
    $this->session = $session;
  }

}
