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
 * Determine if the applicatoin is in read only mode, in the view.
 *
 * @package  Zf2Common
 */
class IsReadOnly extends AbstractHelper {

  /** Config array. */
  protected $config;

  /**
   * Default constructor.
   *
   * @param  array  $config  Config array.
   */
  public function __construct($config) {
    $this->config = $config;
  }

  /**
   * Return the read only setting when invoked.
   *
   * @return  boolean  Is the application in read only mode?
   */
  public function __invoke() {
    $config = $this->getConfig();
    $isReadOnly = false;
    if (isset($config['read_only'])) {
      $isReadOnly = $config['read_only'];
    }
    return $isReadOnly;
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

?>
