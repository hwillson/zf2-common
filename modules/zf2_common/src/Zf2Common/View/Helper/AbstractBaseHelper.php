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
use Zend\Log\LoggerInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Base view helper.
 *
 * @package  Zf2Common
 */
abstract class AbstractBaseHelper
    extends AbstractHelper
    implements LoggerAwareInterface, ServiceLocatorAwareInterface {

  /** Logger. */
  protected $logger;

  /** Service locator. */
  protected $serviceLocator;

  /**
   * Get $logger.
   *
   * @return  LoggerInterface  Logger.
   */
  public function getLogger() {
    return $this->logger;
  }

  /**
   * Set $logger.
   *
   * @param  LoggerInterface  $logger  Logger.
   */
  public function setLogger(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * Get $serviceLocator.
   *
   * @return  ServiceLocatorInterface  $serviceLocator.
   */
  public function getServiceLocator() {
    return $this->serviceLocator;
  }

  /**
   * Set $serviceLocator.
   *
   * @param  ServiceLocatorInterface  $serviceLocator  Service locator.
   */
  public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
    $this->serviceLocator = $serviceLocator;
  }

}
