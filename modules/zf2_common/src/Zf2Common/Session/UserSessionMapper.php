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

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Predicate\Like;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator;
use Zf2Common\Db\AbstractTable;
use Zf2Common\Session\UserSession;

/**
 * Maps UserSession objects to the "user_sessions" table.
 */
class UserSessionMapper extends AbstractTable {

  /** Default sort column. */
  private $sortByColumn;

  /** Default sort order. */
  private $sortOrder;

  /**
   * Find a user session by session ID.
   *
   * @param   string  $sessionId  User session ID.
   * @return  string  Session ID.
   */
  public function findBySessionId($sessionId) {
    $resultSet =
      $this->getTableGateway()->select(array('session_id' => $sessionId));
    $userSession = null;
    if ($resultSet->count()) {
      $userSession = $resultSet->current();
    }
    $resultSet = null;
    return $userSession;
  }

  /**
   * Load an array of session details. Will return a Paginator object.
   *
   * @param   $filterKeyValues  Column name/value pair (separated by "::")
   *          array used to refine search
   * @return  Paginator  Zend paginator object
   */
  public function fetchAll_Paginator($filterKeyValues = null) {

    /*
     * Since we're using Zend's default session table structure, certain
     * values that need to be sorted on aren't stored in their own
     * columns (member id, username, subplan, ip).  These values are
     * stored in a large string, in the "session_data" column.
     * Becuase of this, we're creating temporary columns with the values
     * that need to be sorted on, derived from portions of the
     * session_data column.  This sort columns aren't perfect, but they
     * will allow us to quickly sort on the portions of the session_data
     * column we need to work with.
     */
    $dbAdapter = $this->getTableGateway()->getAdapter();
    $sql = new Sql($dbAdapter);
    $select = $sql->select();
    $select
      ->from(array('s' => 'user_sessions'))
      ->columns(
        array(
          'session_id', 'session_name',
          'session_data' => new Expression("CAST(session_data AS text)"),
          'modified', 'lifetime',
          'expiration_date' =>
            new Expression('modified + lifetime'),
          'minutes_left' =>
            new Expression(
              "(ABS((modified + lifetime) - "
              . "(DATEDIFF(s, '1970-01-01 00:00:00', "
              . "GETUTCDATE()))) / 60)"),
          'member_id_sort' =>
            new Expression(
              'SUBSTRING(session_data, '
              . 'PATINDEX(\'%memberId%\', session_data), 23)'),
          'username_sort' =>
            new Expression(
              'SUBSTRING(session_data, '
              . 'PATINDEX(\'%username%\', session_data), 35)'),
          'subplan_sort' =>
            new Expression(
              'SUBSTRING(session_data, '
              . 'PATINDEX(\'%subplan%\', session_data), 18)'),
          'ip_sort' =>
            new Expression(
              'SUBSTRING(session_data, '
              . 'PATINDEX(\'%"ip"%\', session_data) + 10, 15)')))
      ->where(new Expression("session_data != ''"))
      ->where->like('session_data', '%Zend_Auth%');

    if (!empty($filterKeyValues)) {
      foreach ($filterKeyValues as $filterKeyValue) {
        if (!empty($filterKeyValue)) {
          $filter = split('::', $filterKeyValue);
          $filterKey = $filter[0];
          $filterValue = $filter[1];
          $select->where->like('session_data', '%$filterValue%');
        }
      }
    }

    $this->addDefaultSortToSelect($select);
    $rows =
      $dbAdapter->query(
        $sql->getSqlStringForSqlObject($select),
        DbAdapter::QUERY_MODE_EXECUTE);

    $data = array();
    foreach ($rows as $row) {
      $data[] = $row;
    }

    $arrayIterator = new \ArrayIterator($data);
    $iteratorAdapter = new Iterator($arrayIterator);
    $paginator = new Paginator($iteratorAdapter);
    return $paginator;

  }

  /**
   * Remove a session.
   *
   * @param  string  $sessionId  Session ID for session to remove
   */
  public function removeSession($sessionId) {
    if (!empty($sessionId)) {
      $dbAdapter = $this->getTableGateway()->getAdapter();
      $sql = new Sql($dbAdapter);
      $delete = $sql->delete();
      $delete->from('user_sessions')->where(array('session_id' => $sessionId));
      $dbAdapter->query(
        $sql->getSqlStringForSqlObject($delete), DbAdapter::QUERY_MODE_EXECUTE);
    }
  }

  /**
   * Remove sessions by sessions IDs.
   *
   * @param  array  $sessionIds  Array of session IDs to remove.
   */
  public function removeSessions(array $sessionIds) {
    foreach ($sessionIds as $sessionId) {
      $this->getTableGateway()->delete(array('session_id' => $sessionId));
    }
  }

  /**
   * Remove all sessions. If filter values are provided, use these to
   * restrict the records removed.
   *
   * @param  $filterKeyValues  Filter values used to restrict the records
   *                           removed.
   */
  public function removeAllSessions($filterKeyValues = null) {
    $where = array();
    if (!empty($filterKeyValues)) {
      foreach ($filterKeyValues as $filterKeyValue) {
        if (!empty($filterKeyValue)) {
          $filter = split('::', $filterKeyValue);
          $filterKey = $filter[0];
          $filterValue = $filter[1];
          $where[] = new Expression("session_data LIKE %$filterValue%");
        }
      }
    }
    $dbAdapter = $this->getTableGateway()->getAdapter();
    $sql = new Sql($dbAdapter);
    $delete = $sql->delete();
    $delete->from('user_sessions');
    if (!empty($where)) {
      $delete->where($where);
    }
    $dbAdapter->query(
      $sql->getSqlStringForSqlObject($delete), DbAdapter::QUERY_MODE_EXECUTE);
  }

  /**
   * Remove all PHP filesytem based sessions.
   */
  public function removeAllFileSystemSessions() {
    $sessionPath = session_save_path();
    if ($sessionPath) {
      $files = glob("$sessionPath/sess_*");
      foreach ($files as $file) {
        if (is_file($file)) {
          unlink($file);
        }
      }
    }
  }

  /**
   * Create a new UserSession entry in the database. This is handled
   * automatically by ZF2, but this method can be used for integration testing.
   *
   * @param  UserSession  $userSession  User session to save.
   */
  public function saveSession(UserSession $userSession) {
    $data = array(
      'session_id' => $userSession->getSessionId(),
      'session_name' => $userSession->getSessionName(),
      'modified' => $userSession->getModified(),
      'lifetime' => $userSession->getLifetime(),
      'session_data' => $userSession->getSessionData()
    );
    $this->getTableGateway()->insert($data);
  }

  /**
   * Add default sort column and order to select.
   *
   * @param  Select  $select  Select statement.
   */
  protected function addDefaultSortToSelect(&$select) {
    $sortByColumn = $this->getSortByColumn();
    $sortOrder = $this->getSortOrder();
    if (!empty($sortByColumn)) {
      $select = $select->order("$sortByColumn $sortOrder");
    }
  }

  /**
   * Get $sortByColumn.
   *
   * @return  string  $sortByColumn.
   */
  public function getSortByColumn() {
    return $this->sortByColumn;
  }

  /**
   * Set $sortByColumn.
   *
   * @param  string  $sortByColumn.
   */
  public function setSortByColumn($sortByColumn) {
    $this->sortByColumn = $sortByColumn;
  }

  /**
   * Get $sortOrder.
   *
   * @return  string  $sortOrder.
   */
  public function getSortOrder() {
    return $this->sortOrder;
  }

  /**
   * Set $sortOrder.
   *
   * @param  string  $sortOrder.
   */
  public function setSortOrder($sortOrder) {
    $this->sortOrder = $sortOrder;
  }

}
