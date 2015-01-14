<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Db\TableGateway;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;

/**
 * Read only ready TableGateway.  If the adapter is in ready only mode, make
 * sure insert, updates and deletes are disabled.
 */
class ReadOnlyReadyTableGateway extends TableGateway {

  protected function executeInsert(Insert $insert) {
    $adapter = $this->getAdapter();
    if (property_exists($adapter, 'readOnly') && $adapter->getReadOnly()) {
      return null;
    } else {
      return parent::executeInsert($insert);
    }
  }

  protected function executeUpdate(Update $update) {
    $adapter = $this->getAdapter();
    if (property_exists($adapter, 'readOnly') && $adapter->getReadOnly()) {
      return null;
    } else {
      return parent::executeUpdate($update);
    }
  }

  protected function executeDelete(Delete $delete) {
    $adapter = $this->getAdapter();
    if (property_exists($adapter, 'readOnly') && $adapter->getReadOnly()) {
      return null;
    } else {
      return parent::executeDelete($delete);
    }
  }

}
