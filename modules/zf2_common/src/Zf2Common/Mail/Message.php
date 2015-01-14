<?php

namespace Zf2Common\Mail;

use Zend\Mail\Message as MailMessage;
use Zend\Mail\Header\GenericHeader;

/**
 * Mail message class used to add custom functionality to the default
 * ZF2 Message class.
 */
class Message extends MailMessage {

  /**
   * Build a mail message with the specified $category stored in the
   * 'X-SMTPAPI' header value.
   *
   * @param  string  $category  Mail category.
   */
  public function __construct($category = null) {

    // If present, add category header
    if ($category) {
      $categoryJson = json_encode(array('category' => $category));
      $categoryJson =
        preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $categoryJson);
      $this->getHeaders()->addHeader(
        new GenericHeader('X-SMTPAPI', $categoryJson));
    }

  }

}
