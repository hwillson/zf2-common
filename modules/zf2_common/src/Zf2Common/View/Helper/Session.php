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

/**
 * Access the session in a view.
 *
 * @package  Zf2Common
 */
class Session extends AbstractHelper {

  /** Session. */
  protected $session;

  /**
   * Return the session when invoked.
   *
   * @return  Container  Session.
   */
  public function __invoke() {
    return $this->session;
  }

  /**
   * Get $session.
   *
   * @return  Container  $session.
   */
  public function getSession() {
    return $this->session;
  }

  /**
   * Set $session.
   *
   * @param  Container  $session.
   */
  public function setSession(Container $session) {
    $this->session = $session;
  }

}
