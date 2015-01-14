<?php

namespace FCCommonUnitTest\View\Helper;

use Zend\Stdlib\Exception\InvalidArgumentException;
use FCCommon\View\Helper\TrimUrl;

class TrimUrlTest extends \PHPUnit_Framework_TestCase {

  public function testTrimUrl_InvalidSize_Exception() {
    $trimUrl = new TrimUrl();
    try {
      $trimUrl('asdfasd', -100);
      $this->fail('Should have thrown an exception.');
    } catch (InvalidArgumentException $iae) {
      /* Worked */
    }
  }

  public function testTrimUrl_ValidParams_TrimUrl() {
    $url = 'http://google.com/blah/';
    $trimUrl = new TrimUrl();
    $this->assertEquals(
      'google.com...', $trimUrl($url, 10), 'URL should be trimmed.');
  }

}