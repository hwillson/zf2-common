<?php

namespace Zf2Common\Db;

use Zend\Db\TableGateway\TableGateway;
use Zend\Log\LoggerInterface;
use Zend\Log\LoggerAwareInterface;

/**
 * TableGateway based parent table class.
 *
 * @module Zf2Common
 */
abstract class AbstractTable implements LoggerAwareInterface {

  protected $tableGateway;
  protected $logger;

  public function __construct(TableGateway $tableGateway) {
    $this->tableGateway = $tableGateway;
  }

  public function getTableGateway() {
    return $this->tableGateway;
  }

  public function setTableGateway(TableGateway $tableGateway) {
    $this->tableGateway = $tableGateway;
  }

  public function getLogger() {
    return $this->logger;
  }

  public function setLogger(LoggerInterface $logger) {
    $this->logger = $logger;
  }

}
