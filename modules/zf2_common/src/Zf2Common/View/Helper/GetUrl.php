<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\View\Helper;

use Zend\Http\Request;
use Zend\View\Helper\Url as UrlHelper;
use Zf2Common\View\Helper\AbstractBaseHelper;

/**
 * Get the current URL, replacing keys/values as needed in the request string.
 */
class GetUrl extends AbstractBaseHelper {

  /** URL view helper. */
  protected $urlHelper;

  /**
   * Build and return a URL replacing request keys/values as needed.
   *
   * @param   $controller     Controller.
   * @param   $action         Action.
   * @param   $queryParams    Query parameters.
   * @param   $replaceParams  Query parameters/values to replace.
   * @return  string          Built URL.
   */
  public function __invoke(
      $controller, $action, array $queryParams = null,
      array $replaceParams = null) {
    $url = null;
  	$urlHelper = $this->getUrlHelper();
  	if ($urlHelper != null) {
  	  if (!empty($replaceParams)) {
        foreach ($replaceParams as $replaceKey => $replaceValue) {
          $queryParams[$replaceKey] = $replaceValue;
        }
  	  }

      $url =
        $urlHelper(
          'application',
          array('controller' => $controller, 'action' => $action),
          array('query' => $queryParams));

      /*
       * If an array is used in the parameter list, like it is for search
       * within, the Zend url helper includes array key numbers in the generated
       * URL (so things look like param[0]=X&param[1]=X).  We don't need
       * these keys, so we'll cut them out to make things look normal (like
       * param[]=X&param[]=X).
       */
      $url = preg_replace('/%5B(\d+)%5D/', '%5B%5D', $url);

    }
    return $url;
  }

  /**
   * Get the URL view helper.
   *
   * @return  UrlHelper  URL view helper.
   */
  public function getUrlHelper() {
    return $this->urlHelper;
  }

  /**
   * Set the URL view helper.
   *
   * @param  $urlHelper  URL view helper.
   */
  public function setUrlHelper(UrlHelper $urlHelper) {
    $this->urlHelper = $urlHelper;
  }

}

?>
