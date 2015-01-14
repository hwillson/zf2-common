<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Db\Adapter;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Adapter\Profiler\ProfilerInterface;
use Zend\Db\Adapter\Driver\DriverInterface;
use Zend\Db\ResultSet\ResultSetInterface as RSInterface;

/**
 * Custom database adapter that will check for a "read_only" setting configured
 * for database adapters.  If found, will make sure update and insert queries
 * are not executed.
 *
 * @module Zf2Common
 */
class ReadOnlyReadyAdapter extends Adapter {

  protected $readOnly = false;

  public function __construct(
      $driver, PlatformInterface $platform = null,
      ResultSetInterface $queryResultPrototype = null,
      ProfilerInterface $profiler = null,
      $readOnly = false) {

    $this->readOnly = $readOnly;
    parent::__construct($driver, $platform, $queryResultPrototype, $profiler);

  }

  public function query(
      $sql, $parametersOrQueryMode = self::QUERY_MODE_PREPARE,
      RSInterface $resultPrototype = null) {
    if ($this->getReadOnly()
        && (strstr(strtolower($sql), 'insert')
          || strstr(strtolower($sql), 'update'))) {
      $resultSet = clone $this->queryResultSetPrototype;
      $resultSet->initialize(array());
      return $resultSet;
    } else {
      return parent::query($sql, $parametersOrQueryMode);
    }

  }

  public function setDriver(DriverInterface $driver) {
    $this->driver = $driver;
  }

  public function getReadOnly() {
    return $this->readOnly;
  }

}

?>
