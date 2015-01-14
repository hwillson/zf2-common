<?php

namespace Zf2Common\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Config extends AbstractHelper {

  protected $config;

  public function __invoke() {
    return $this->config;
  }

  public function getConfig() {
    return $this->config;
  }

  public function setConfig($config) {
    $this->config = $config;
  }

}
