<?php

namespace Zf2CommonUnitTest\Framework;

use PHPUnit_Framework_TestCase;

abstract class AbstractBasePHPUnitTestCase extends PHPUnit_Framework_TestCase {

  public static $locator;

  public static function setLocator($locator) {
    self::$locator = $locator;
  }

  public function getLocator() {
    return self::$locator;
  }

}
