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
