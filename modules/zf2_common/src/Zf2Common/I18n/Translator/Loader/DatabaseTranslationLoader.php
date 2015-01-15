<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\I18n\Translator\Loader;

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\TableIdentifier;
use Zend\I18n\Translator\Loader\RemoteLoaderInterface;
use Zend\I18n\Translator\Plural\Rule as PluralRule;
use Zend\I18n\Translator\TextDomain;
use Zend\Log\LoggerInterface;
use Zend\Log\LoggerAwareInterface;
use Zf2Common\I18n\Language;

/**
 * Load translations from database.
 *
 * @package  Zf2Common
 */
class DatabaseTranslationLoader
    implements RemoteLoaderInterface, LoggerAwareInterface {

  /** Database adapter. */
  protected $dbAdapter;

  /** Database schema. */
  protected $dbSchema;

  /** Logger. */
  protected $logger;

  /**
   * Default constructor.
   *
   * @param  DbAdapter  $dbAdapter  Database adapter.
   */
  public function __construct(DbAdapter $dbAdapter) {
    $this->dbAdapter = $dbAdapter;
  }

  /**
   * Set $dbAdapter.
   *
   * @param  DbAdapter  $dbAdapter  Database adapter.
   */
  public function setDbAdapter($dbAdapter) {
    $this->dbAdapter = $dbAdapter;
  }

  /**
   * Get $dbAdapter.
   *
   * @return  DbAdapter  $dbAdapter.
   */
  public function getDbAdapter() {
    return $this->dbAdapter;
  }

  /**
   * Set $dbSchema.
   *
   * @param  string  $dbSchema  Database schema.
   */
  public function setDbSchema($dbSchema) {
    $this->dbSchema = $dbSchema;
  }

  /**
   * Get $dbSchema.
   *
   * @return  string  $dbSchema.
   */
  public function getDbSchema() {
    return $this->dbSchema;
  }

  /**
   * Get $logger.
   *
   * @return  LoggerInterface  $logger.
   */
  public function getLogger() {
    return $this->logger;
  }

  /**
   * Set $logger.
   *
   * @param  LoggerInterface  $logger  Logger.
   */
  public function setLogger(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * Load translations from the "app_dictionary" table, storing them in a
   * Zend\I18n\Translator\TextDomain object.
   *
   * @param   string  $locale  Locale string.
   * @param   TextDomain  $textDomain  Default TextDomain (overwritten).
   * @return  TextDomain  TextDomain loaded from database.
   */
  public function load($locale, $textDomain) {

    $this->getLogger()->debug("Locale: $locale");
    $localeColumn = Language::getLanguageFullNameForLocale($locale);
    $this->getLogger()->debug("Locale column: $localeColumn");

    $sql = new Sql($this->getDbAdapter());
    $select = $sql->select();
    $schema = $this->getDbSchema();
    if (isset($schema))
      $select->from(new TableIdentifier('app_dictionary', $schema));
    else
      $select->from('app_dictionary');
    $select->columns(array('code_desc', "$localeColumn"), false);
    $messages =
      $this->dbAdapter->query(
        $sql->getSqlStringForSqlObject($select),
        DbAdapter::QUERY_MODE_EXECUTE);

    $textDomain = new TextDomain();
    foreach ($messages as $message) {
      $textDomain[$message['code_desc']] = $message["$localeColumn"];
    }

    return $textDomain;

  }

}
