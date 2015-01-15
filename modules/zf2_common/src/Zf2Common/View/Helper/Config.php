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
 * Access the config array in a view.
 *
 * @package  Zf2Common
 */
class Config extends AbstractHelper {

  /** Config array. */
  protected $config;

  /**
   * Return the config array when invoked.
   *
   * @return  array  Config array.
   */
  public function __invoke() {
    return $this->config;
  }

  /**
   * Get $config.
   *
   * @return  array  Config array.
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * Set $config.
   *
   * @param  array  $config  Config array.
   */
  public function setConfig($config) {
    $this->config = $config;
  }

}
