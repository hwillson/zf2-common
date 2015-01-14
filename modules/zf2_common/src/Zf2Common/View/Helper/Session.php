<?php

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
