<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Log\LoggerInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Session\Container as Session;
use Zf2Common\I18n\Language;

/**
 * Custom plugin used to extract language selection from the request, setting
 * the appropriate locale and translate object. If no language is specified
 * in the request, will fallback on the locale of the users browser.
 *
 * @package  Zf2Common
 */
class LanguageSelectorPlugin extends AbstractPlugin
    implements LoggerAwareInterface {

  /** Logger. */
  protected $logger;

  /** Session container. */
  protected $session;

  /** Default language. */
  protected $defaultLanguage;

  /**
   * Set the current locale and translate object based on the language
   * parameter setting in the request - fallback on browser locale if no
   * language request parameter is present.
   *
   * @param  MvcEvent  $mvcEvent  Event.
   */
  public function setLanguage($event) {

    $language = $event->getRequest()->getQuery()->get('language');
    $this->getLogger()->debug(
      'Language parameter in request: ' . $language, array(__METHOD__));

    $session = $this->getSession();
    if (isset($language) && !empty($language)) {
      $session->language = $language;
    }

    $storedLanguage =
      (isset($session->language)
        ? $session->language : $this->getDefaultLanguage());
    $this->getLogger()->debug(
      "Stored language: $storedLanguage", array(__METHOD__));
    $session->language = $storedLanguage;

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
   * Get $session.
   *
   * @return  Session  $session.
   */
  public function getSession() {
    return $this->session;
  }

  /**
   * Set $session.
   *
   * @param  Session  $session  Session.
   */
  public function setSession(Session $session) {
    $this->session = $session;
  }

  /**
   * Get $defaultLanguage.
   *
   * @return  string  $defaultLanguage, falling back on English if not set.
   */
  public function getDefaultLanguage() {
    $defaultLanguage = $this->defaultLanguage;
    if (empty($defaultLanguage)) {
      $defaultLanguage = Language::$ENGLISH;
    }
    return $defaultLanguage;
  }

  /**
   * Set $defaultLanguage.
   *
   * @param  string  $defaultLanguage  Default language.
   */
  public function setDefaultLanguage($defaultLanguage) {
    $this->defaultLanguage = $defaultLanguage;
  }

}
