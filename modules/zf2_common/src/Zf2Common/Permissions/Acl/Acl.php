<?php

/**
 * zf2-common
 * https://github.com/hwillson/zf2-common
 *
 * @author     Hugh Willson, Octonary Inc.
 * @copyright  Copyright (c)2015 Hugh Willson, Octonary Inc.
 * @license    http://opensource.org/licenses/MIT
 */

namespace Zf2Common\Permissions\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * Access control list implementation handling roles and resource access.
 *
 * @package  Zf2Common
 */
class Acl extends ZendAcl {

  /** Guest role. */
  const GUEST_ROLE = 'guest';

  /** Default role. */
  const DEFAULT_ROLE = self::GUEST_ROLE;

  /** Member role. */
  const MEMBER_ROLE = 'member';

  /** Admin role. */
  const ADMIN_ROLE = 'admin';

  /** Role IDs. */
  protected $roleIds = array();

  /**
   * Default constructor.
   *
   * @param  array  $config  Config.
   */
  public function __construct($config)  {
    if (!isset($config['acl']['roles'])
        || !isset($config['acl']['resources'])) {
      throw new \Exception('Invalid ACL Config found');
    }
    $roles = $config['acl']['roles'];
    if (!isset($roles[self::DEFAULT_ROLE])) {
      $roles[self::DEFAULT_ROLE] = '';
    }
    $this->addRoles($roles)->addResources($config['acl']['resources']);
    $this->setRoleIds($config['acl']['role_ids']);
  }

  /**
   * Get $roleIds;
   *
   * @return  array  $roleIds.
   */
  public function getRoleIds() {
    return $this->roleIds;
  }

  /**
   * Set $roleIds.
   *
   * @param  array  $roleIds  Role IDs.
   */
  public function setRoleIds($roleIds) {
    $this->roleIds = $roleIds;
  }

  /**
   * Add roles.
   *
   * @param   array  $roles  Roles.
   * @return  Acl  This Acl object, for chaining.
   */
  protected function addRoles($roles) {
    foreach ($roles as $name => $parent) {
      if (!$this->hasRole($name)) {
        if (empty($parent)) {
          $parent = array();
        } else if (!is_array($parent)) {
          if (strpos($parent, ',') !== false) {
            $parent = array_map('trim', explode(',', $parent));
          } else {
            $parent = array($parent);
          }
        }
        $this->addRole(new GenericRole($name), $parent);
      }
    }
    return $this;
  }

  /**
   * Add resources.
   *
   * @param   array  $resources  Resources.
   * @return  Acl  This Acl object, for chaining.
   */
  protected function addResources($resources) {
    foreach ($resources as $permission => $controllers) {
      foreach ($controllers as $controller => $actions) {
        if ($controller == 'all') {
          $controller = null;
        } else {
          if (!$this->hasResource($controller)) {
            $this->addResource(new GenericResource($controller));
          }
        }
        foreach ($actions as $action => $role) {
          if ($action == 'all') {
            $action = null;
          }
          if ($permission == 'allow') {
            $this->allow($role, $controller, $action);
          } elseif ($permission == 'deny') {
            $this->deny($role, $controller, $action);
          } else {
            throw new \Exception('No valid permission defined: ' . $permission);
          }
        }
      }
    }
    return $this;
  }

}
