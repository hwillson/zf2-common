<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\I18n;

/**
 * Application language helper class.
 *
 * @package  Zf2Common
 */
class Language {

  /** English language. */
  public static $ENGLISH = 'en';

  /** Spanish language. */
  public static $SPANISH = 'es';

  /** English language name. */
  public static $ENGLISH_NAME = 'english';

  /** Spanish language name. */
  public static $SPANISH_NAME = 'spanish';

  /**
   * Get a language descriptive english label.
   *
   * @param   string  $language  Language to get English label for.
   * @return  string  Language full name.
   */
  public static function getLanguageFullName($language) {
    $fullName = null;
    if ($language == self::$ENGLISH) {
      $fullName = self::$ENGLISH_NAME;
    } else if ($language == self::$SPANISH) {
      $fullName = self::$SPANISH_NAME;
    }
    return $fullName;
  }

  /**
   * Return a full language name based on the passed in locale.
   *
   * @param   string  $locale  Locale string.
   * @return  string  Full language name.
   */
  public static function getLanguageFullNameForLocale($locale) {
    $fullName = null;
    if (strpos($locale, self::$ENGLISH) === 0) {
      $fullName = self::$ENGLISH_NAME;
    } else if (strpos($locale, self::$SPANISH) === 0) {
      $fullName = self::$SPANISH_NAME;
    }
    return $fullName;
  }

}


?>
