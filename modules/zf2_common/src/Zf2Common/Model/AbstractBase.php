<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Model;

use Zend\Log\LoggerInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Di\ServiceLocator;
use Zend\Stdlib\ArrayObject;

/**
 * Common model class. Holds common functionality that be extended by any
 * application model.
 *
 * @package  Zf2Common
 */
abstract class AbstractBase extends ArrayObject
    implements LoggerAwareInterface {

  /** Logger. */
  protected $logger;

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

  /**
   * Create get/set methods for all model properties.
   *
   * @param  string  $name  Model property name.
   * @param  array  $arguments  Model property set arguments.
   * @return  mixed  Get property value.
   */
  public function __call($name, $arguments) {
    if (preg_match('/^get.*$/', $name)) {
      $attribute = lcfirst(substr($name, 3, strlen($name)));
      return $this->$attribute;
    } else if (preg_match('/^set.*$/', $name)) {
      $attribute = lcfirst(substr($name, 3, strlen($name)));
      if (count($arguments) > 0) {
        $this->$attribute = $arguments[0];
      }
    } else {
      throw new \Exception("Unknown method \"$name\".");
    }
  }

  /**
   * Return an array with all model properties/values.
   *
   * @return  array  All model properties/values.
   */
  public function toArray() {
    $allProperties = array();
    $properties = get_object_vars($this);
    foreach ($properties as $key => $value) {
      $getter = "get$key";
      $allProperties[$key] = $this->$getter();
    }
    return $allProperties;
  }

  /**
   * If the $data array contains the $value key, return the value stored in
   * the array at the corresponding location.
   *
   * @param  array  $data  Data array.
   * @param  string  $value  Array key to check for.
   * @param  boolean  $escape  If true HTML escape returned value.
   * @return  string  Data value stored at key $value.
   */
  protected function getDataValue($data, $value, $escape = true) {
    $dataValue = null;
    if (isset($data[$value])) {
      if ($escape) {
        $dataValue = htmlspecialchars($data[$value]);
      } else {
        $dataValue = $data[$value];
      }
    }
    return trim($dataValue);
  }

}
