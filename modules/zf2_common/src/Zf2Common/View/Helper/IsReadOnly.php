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

class IsReadOnly extends AbstractHelper {

  protected $config;

  public function __construct($config) {
    $this->config = $config;
  }

  public function __invoke() {
    $config = $this->getConfig();
    $isReadOnly = false;
    if (isset($config['read_only'])) {
      $isReadOnly = $config['read_only'];
    }
    return $isReadOnly;
  }

  public function getConfig() {
    return $this->config;
  }

  public function setConfig($config) {
    $this->config = $config;
  }

}

?>
