<?php

namespace FCCommonUnitTest\Db\Adapter;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Db\Adapter\Driver\StatementInterface;
use Zend\Db\ResultSet\ResultSet;
use FCCommon\Db\Adapter\ReadOnlyReadyAdapter;

class ReadOnlyReadyAdapterTest extends TestCase {

  protected $mockDriver;
  protected $mockStatement;

  public function setUp() {
    $this->mockDriver =
      $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
    $this->mockStatement =
      $this->getMock('Zend\Db\Adapter\Driver\StatementInterface');
    $this->mockDriver
      ->expects($this->any())
      ->method('createStatement')
      ->will($this->returnValue($this->mockStatement));
  }


  public function testConstruct_ReadOnlyAdapterReadOnlyTrue_InsertUpdateReturnsEmptyResultSet() {

    $adapter =
      new ReadOnlyReadyAdapter(
        array('driver' => 'pdo'), null, null, null, true);
    $adapter->setDriver($this->mockDriver);

    $sql = 'select * from test_table';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof StatementInterface,
      'Select query result should be a StatementInterface based object.');

    $sql = 'insert into test_table ...';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof ResultSet,
      'Insert query result should be a ResultSet based object.');
    $result = $resultSet->current();
    $this->assertTrue(count($result) == 0, 'Insert query result is empty.');

    $sql = 'update test_table ...';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof ResultSet,
      'Update query result should be a ResultSet based object.');
    $result = $resultSet->current();
    $this->assertTrue(count($result) == 0, 'Update query result is empty.');

  }

  public function testConstruct_ReadOnlyAdapterReadOnlyFalse_InsertUpdateReturnsNull() {

    $adapter =
      new ReadOnlyReadyAdapter(
        array('driver' => 'pdo'), null, null, null, false);
    $adapter->setDriver($this->mockDriver);

    $sql = 'select * from test_table';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof StatementInterface,
      'Select query result should be a StatementInterface based object.');

    $sql = 'insert into test_table ...';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof StatementInterface,
      'Insert query result should be a StatementInterface based object.');

    $sql = 'update test_table ...';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof StatementInterface,
      'Update query result should be a StatementInterface based object.');

  }

  public function testConstruct_ReadOnlyAdapterReadOnlyNotSet_InsertUpdateReturnsNull() {

    $adapter =
      new ReadOnlyReadyAdapter(array('driver' => 'pdo'), null, null, null);
    $adapter->setDriver($this->mockDriver);

    $sql = 'select * from test_table';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof StatementInterface,
      'Select query result should be a StatementInterface based object.');

    $sql = 'insert into test_table ...';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof StatementInterface,
      'Insert query result should be a StatementInterface based object.');

    $sql = 'update test_table ...';
    $resultSet = $adapter->query($sql);
    $this->assertTrue(
      $resultSet instanceof StatementInterface,
      'Update query result should be a StatementInterface based object.');

  }

}

?>