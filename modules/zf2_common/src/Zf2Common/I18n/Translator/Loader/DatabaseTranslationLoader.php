<?php

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
 */
class DatabaseTranslationLoader
    implements RemoteLoaderInterface, LoggerAwareInterface {

  protected $dbAdapter;
  protected $dbSchema;
  protected $logger;

  public function setDbAdapter($dbAdapter) {
    $this->dbAdapter = $dbAdapter;
  }

  public function getDbAdapter() {
    return $this->dbAdapter;
  }

  public function setDbSchema($dbSchema) {
    $this->dbSchema = $dbSchema;
  }

  public function getDbSchema() {
    return $this->dbSchema;
  }

  public function getLogger() {
    return $this->logger;
  }

  public function setLogger(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  public function __construct(DbAdapter $dbAdapter) {
    $this->dbAdapter = $dbAdapter;
  }

  public function load($locale, $textDomain) {

    $this->getLogger()->debug("Locale: $locale");
    $localeColumn = Language::getLanguageFullNameForLocale($locale);
    $this->getLogger()->debug("Locale column: $localeColumn");

    $sql = new Sql($this->getDbAdapter());
    $select = $sql->select();
    $schema = $this->getDbSchema();
    if (isset($schema))
      $select->from(new TableIdentifier('mex_dictionary', $schema));
    else
      $select->from('mex_dictionary');
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
