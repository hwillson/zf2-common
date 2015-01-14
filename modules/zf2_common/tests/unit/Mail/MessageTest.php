<?php

namespace FCCommonUnitTest\Mail;

use FCCommon\Mail\Message;

class MessageTest extends \PHPUnit_Framework_TestCase {

  public function testConstruct_NoCategory_CreateMessageWithoutCategoryHeader() {
    $message = new Message();
    $headers = $message->getHeaders();
    $sendGridHeader = $headers->get('X-SMTPAPI');
    $this->assertFalse(
      $sendGridHeader, 'Custom SendGrid header should not exist.');
  }

  public function testConstruct_FdoCategory_AddSendGridCategoryHeader() {
    $message = new Message('fdo');
    $headers = $message->getHeaders();
    $sendGridHeader = $headers->get('X-SMTPAPI');
    $this->assertNotNull(
      $sendGridHeader, 'Custom SendGrid header should exist.');
    $this->assertEquals(
      'X-SMTPAPI', $sendGridHeader->getFieldName(),
      'SendGrid category should be set.');
    $this->assertEquals(
      '{"category": "fdo"}', $sendGridHeader->getFieldValue(),
      'fdo category should be set.');
  }

}