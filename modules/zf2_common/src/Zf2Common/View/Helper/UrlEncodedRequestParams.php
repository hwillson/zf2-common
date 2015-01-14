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
 * Return an array with all existing requests parameters, URL encoded.
 */
class UrlEncodedRequestParams extends AbstractBaseHelper {

  /**
   * Return an array with all existing requests parameters, URL encoded.
   *
   * @param   $request  HTTP request.
   * @return  array     HTTP request parameters.
   */
  public function __invoke($request) {

    $params = $request->getQuery()->toArray();
    foreach ($params as &$param) {
      if (is_array($param)) {
        foreach ($param as &$subParam) {
          $subParam = urlencode($subParam);
        }
      } else {
        $param = urlencode($param);
      }
    }
    return $params;

  }

}

?>
