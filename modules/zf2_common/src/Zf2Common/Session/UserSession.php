<?php

namespace Zf2Common\Session;

use Zf2Common\Model\AbstractBase;

/**
 * User session model.
 */
class UserSession extends AbstractBase {

  protected $sessionId;
  protected $sessionName;
  protected $modified;
  protected $lifetime;
  protected $sessionData;
  protected $username;
  protected $namedUserId;

  public function setModified($modified) {
    $this->modified = (int)$modified;
  }

  public function setLifetime($lifetime) {
    $this->lifetime = (int)$lifetime;
  }

  public function exchangeArray($data) {
    $this->setSessionId($this->getDataValue($data, 'session_id'));
    $this->setSessionName($this->getDataValue($data, 'session_name'));
    $this->setModified($this->getDataValue($data, 'modified'));
    $this->setLifetime($this->getDataValue($data, 'lifetime'));
    $this->setSessionData($this->getDataValue($data, 'session_data'));
    $this->setUsername($this->getDataValue($data, 'username'));
    $this->setNamedUserId($this->getDataValue($data, 'named_user_id'));
  }

  public function __toString() {
    return 'Session ID: ' . $this->getSessionId();
  }

}
