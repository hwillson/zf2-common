<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Db;

use Zend\Db\TableGateway\TableGateway;
use Zend\Log\LoggerInterface;
use Zend\Log\LoggerAwareInterface;

/**
 * TableGateway based parent table class.
 *
 * @package  Zf2Common
 */
abstract class AbstractTable implements LoggerAwareInterface {

  /** Table gateway. */
  protected $tableGateway;

  /** Logger. */
  protected $logger;

  /**
   * Default constructor.
   *
   * @param  TableGateway  $tableGateway  Table gateway.
   */
  public function __construct(TableGateway $tableGateway) {
    $this->tableGateway = $tableGateway;
  }

  /**
   * Get $tableGateway.
   *
   * @return  TableGateway  $tableGateway.
   */
  public function getTableGateway() {
    return $this->tableGateway;
  }

  /**
   * Set $tableGateway.
   *
   * @param  TableGateway  $tableGateway  Table gateway.
   */
  public function setTableGateway(TableGateway $tableGateway) {
    $this->tableGateway = $tableGateway;
  }

  /**
   * Get $logger.
   *
   * @return  LoggerInterface  $logger.
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

}
