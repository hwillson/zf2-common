<?php

namespace Zf2CommonUnitTest\Mail;

use Zf2Common\Mail\Message;

class MessageTest extends \PHPUnit_Framework_TestCase {

  public function testConstruct_NoCategory_CreateMessageWithoutCategoryHeader() {
    $message = new Message();
    $headers = $message->getHeaders();
    $sendGridHeader = $headers->get('X-SMTPAPI');
    $this->assertFalse(
      $sendGridHeader, 'Custom SendGrid header should not exist.');
  }

  public function testConstruct_Category_AddSendGridCategoryHeader() {
    $message = new Message('test_category');
    $headers = $message->getHeaders();
    $sendGridHeader = $headers->get('X-SMTPAPI');
    $this->assertNotNull(
      $sendGridHeader, 'Custom SendGrid header should exist.');
    $this->assertEquals(
      'X-SMTPAPI', $sendGridHeader->getFieldName(),
      'SendGrid category should be set.');
    $this->assertEquals(
      '{"category": "test_category"}', $sendGridHeader->getFieldValue(),
      'test_category category should be set.');
  }

}
