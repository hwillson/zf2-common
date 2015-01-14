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

abstract class AbstractBaseHelper
    extends AbstractHelper
    implements LoggerAwareInterface, ServiceLocatorAwareInterface {

  protected $logger;
  protected $serviceLocator;

  public function getLogger() {
    return $this->logger;
  }

  public function setLogger(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  public function getServiceLocator() {
    return $this->serviceLocator;
  }

  public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
    $this->serviceLocator = $serviceLocator;
  }

}
