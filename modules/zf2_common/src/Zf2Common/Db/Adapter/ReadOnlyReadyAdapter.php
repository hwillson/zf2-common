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
 * @package Zf2Common
 */
class ReadOnlyReadyAdapter extends Adapter {

  /** Is read only mode enabled? */
  protected $readOnly = false;

  /**
   * Default constructor.
   *
   * @param  Driver\DriverInterface|array  $driver
   * @param  Platform\PlatformInterface  $platform
   * @param  ResultSet\ResultSetInterface  $queryResultPrototype
   * @param  Profiler\ProfilerInterface  $profiler
   * @param  boolean  $readOnly  Is read only mode enabled?
   */
  public function __construct(
      $driver, PlatformInterface $platform = null,
      ResultSetInterface $queryResultPrototype = null,
      ProfilerInterface $profiler = null,
      $readOnly = false) {

    $this->readOnly = $readOnly;
    parent::__construct($driver, $platform, $queryResultPrototype, $profiler);

  }

  /**
   * If read only mode is enabled and the query is an insert or update, skip
   * the operation and return an empty result set.
   *
   * @param   string  $sql
   * @param   string|array|ParameterContainer  $parametersOrQueryMode
   * @return  Driver\StatementInterface|ResultSet\ResultSet
   */
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

  /**
   * Set $driver.
   *
   * @param  DriverInterface  $driver  Driver interface.
   */
  public function setDriver(DriverInterface $driver) {
    $this->driver = $driver;
  }

  /**
   * Get $readOnly.
   *
   * @return  boolean  Is read only mode enabled?
   */
  public function getReadOnly() {
    return $this->readOnly;
  }

}

?>
