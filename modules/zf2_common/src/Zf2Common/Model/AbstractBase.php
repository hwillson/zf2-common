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
 * Common model class.  Holds common functionality that be extended by any
 * application model.
 */
abstract class AbstractBase extends ArrayObject
    implements LoggerAwareInterface {

  protected $logger;

  public function getLogger() {
    return $this->logger;
  }

  public function setLogger(LoggerInterface $logger) {
    $this->logger = $logger;
  }

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

  public function toArray() {
    $allProperties = array();
    $properties = get_object_vars($this);
    foreach ($properties as $key => $value) {
      $getter = "get$key";
      $allProperties[$key] = $this->$getter();
    }
    return $allProperties;
  }

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
