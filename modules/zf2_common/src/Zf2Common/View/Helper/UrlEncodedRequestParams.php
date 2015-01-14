<?php

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
