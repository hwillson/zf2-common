<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Session;

use Zf2Common\Model\AbstractBase;

/**
 * User session model.
 *
 * @package  Zf2Common
 */
class UserSession extends AbstractBase {

  /** Session ID. */
  protected $sessionId;

  /** Session name. */
  protected $sessionName;

  /** Last modified timestamp. */
  protected $modified;

  /** Session lifetime. */
  protected $lifetime;

  /** Session data. */
  protected $sessionData;

  /**
   * Set $modified.
   *
   * @param  string  $modified  Modified time.
   */
  public function setModified($modified) {
    $this->modified = (int)$modified;
  }

  /**
   * Set $lifetime.
   *
   * @param  string  $lifetime  Session lifetime.
   */
  public function setLifetime($lifetime) {
    $this->lifetime = (int)$lifetime;
  }

  /**
   * Set a UserSession's properties from an array.
   *
   * @param  array  $data  Properties to set.
   */
  public function exchangeArray($data) {
    $this->setSessionId($this->getDataValue($data, 'session_id'));
    $this->setSessionName($this->getDataValue($data, 'session_name'));
    $this->setModified($this->getDataValue($data, 'modified'));
    $this->setLifetime($this->getDataValue($data, 'lifetime'));
    $this->setSessionData($this->getDataValue($data, 'session_data'));
  }

  /**
   * String representation of a UserSession.
   *
   * @return  string  UserSession string representation.
   */
  public function __toString() {
    return 'Session ID: ' . $this->getSessionId();
  }

}
