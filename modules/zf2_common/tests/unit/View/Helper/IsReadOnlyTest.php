<?php

namespace Zf2CommonUnitTest\View\Helper;

use PHPUnit_Framework_TestCase as TestCase;
use Zf2Common\View\Helper\IsReadOnly;

class IsReadOnlyTest extends TestCase {

  public function testIsReadOnly_ReadOnlyModeOff_ReturnsFalse() {
    $config = array('read_only' => false);
    $isReadOnly = new IsReadOnly($config);
    $this->assertFalse($isReadOnly(), 'Result should return false');
  }

  public function testIsReadOnly_ReadOnlyModeOn_ReturnsTrue() {
    $config = array('read_only' => true);
    $isReadOnly = new IsReadOnly($config);
    $this->assertTrue($isReadOnly(), 'Result should return true');
  }

  public function testIsReadOnly_ReadOnlyModeConfigSettingMissing_ReturnsFalse() {
    $config = array();
    $isReadOnly = new IsReadOnly($config);
    $this->assertFalse($isReadOnly(), 'Result should return false');
  }

}
