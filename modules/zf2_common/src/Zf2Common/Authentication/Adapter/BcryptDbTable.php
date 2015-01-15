<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Authentication\Adapter;

use Zend\Authentication\Adapter\DbTable;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Operator as SqlOp;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Exception\RuntimeException;

/**
 * A Bcrypt DbTable authentication adapter, used to bcrypt entered passwords,
 * comparing with stored bcrypted passwords.
 *
 * @package  Zf2Common
 */
class BcryptDbTable extends DbTable {

  /** Flag to disable bcrypt check. */
  protected $disableBcrypt;

  /** Unencrypted password table column. */
  protected $unencryptedCredentialColumn;

  /**
   * Creates a Zend\Db\Sql\Select object that is completely configured to be
   * queried against the database.
   *
   * @return  Zend\Db\Sql\Select  Select object.
   */
  protected function authenticateCreateSelect() {
    if ($this->getDisableBcrypt()) {
      return parent::authenticateCreateSelect();
    } else {
      $dbSelect = clone $this->getDbSelect();
      $dbSelect->from($this->tableName)
        ->columns(array('*'))
        ->where(new SqlOp($this->identityColumn, '=', $this->identity));
      return $dbSelect;
    }
  }

  /**
   * Will bcrypt and compare the passed in credential against a previously
   * stored bcrypted credential
   *
   * @param   Zend\Db\Sql\Select $dbSelect  Select object.
   * @return  array  Result identities.
   */
  protected function authenticateQuerySelect(Select $dbSelect) {

    if ($this->getDisableBcrypt()) {
      return parent::authenticateQuerySelect($dbSelect);
    } else {

      $resultIdentities = array();
      $sql = new Sql($this->zendDb);

      try {

        $encPasswordExists = false;

        // See if there are any encrypted password matches first
        $statement = $sql->prepareStatementForSqlObject($dbSelect);
        $result = $statement->execute();
        $bcrypt = new Bcrypt();
        foreach ($result as $row) {
          if ($row[$this->credentialColumn]) {
            $encPasswordExists = true;
            if ($bcrypt->verify(
              $this->credential, $row[$this->credentialColumn])) {
              $row['zend_auth_credential_match'] = 1;
              $resultIdentities[] = $row;
            }
          }
        }
        $result = null;

        /*
         * If an encrypted password doesn't exist, try falling back
         * on an un-encrypted credential column (if one exists).
         */
        if (empty($resultIdentities)
          && $this->getUnencryptedCredentialColumn()) {
          $statement = $sql->prepareStatementForSqlObject($dbSelect);
          $result = $statement->execute();
          foreach ($result as $row) {
            if (strtolower($this->credential) ==
              strtolower($row[$this->getUnencryptedCredentialColumn()])) {
              $row['zend_auth_credential_match'] = 1;
              $resultIdentities[] = $row;
            }
          }
        }

      } catch (\Exception $e) {
        throw new RuntimeException(
          'The supplied parameters to DbTable failed to '
          . 'produce a valid sql statement, please check table and column names '
          . 'for validity.', 0, $e
        );
      }

      return $resultIdentities;

    }

  }

  /**
   * Set $disableBcrypt.
   *
   * @param  boolean  $disableBcrypt  Enable/disable bcrypt.
   */
  public function setDisableBcrypt($disableBcrypt) {
    $this->disableBcrypt = $disableBcrypt;
  }

  /**
   * Get $disableBcrypt.
   *
   * @return  boolean  True if enabled, false if disabled.
   */
  public function getDisableBcrypt() {
    return $this->disableBcrypt;
  }

  /**
   * Set $unencryptedCredentialColumn.
   *
   * @param  string  $unencryptedCredentialColumn  Unencrypted credential
   *         column name.
   */
  public function setUnencryptedCredentialColumn($unencryptedCredentialColumn) {
    $this->unencryptedCredentialColumn = $unencryptedCredentialColumn;
  }

  /**
   * Get $unencryptedCredentialColumn.
   *
   * @return  string  Unencrypted credential column name.
   */
  public function getUnencryptedCredentialColumn() {
    return $this->unencryptedCredentialColumn;
  }

}
