<?php

namespace Zf2Common\Authentication\Adapter;

use Zend\Authentication\Adapter\DbTable;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Operator as SqlOp;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Exception\RuntimeException;

class BcryptDbTable extends DbTable {

  protected $disableBcrypt;
  protected $unencryptedCredentialColumn;

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

  public function setDisableBcrypt($disableBcrypt) {
    $this->disableBcrypt = $disableBcrypt;
  }

  public function getDisableBcrypt() {
    return $this->disableBcrypt;
  }

  public function setUnencryptedCredentialColumn($unencryptedCredentialColumn) {
    $this->unencryptedCredentialColumn = $unencryptedCredentialColumn;
  }

  public function getUnencryptedCredentialColumn() {
    return $this->unencryptedCredentialColumn;
  }

}
