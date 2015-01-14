<?php

namespace Zf2Common\Permissions\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;

class Acl extends ZendAcl {

  const GUEST_ROLE = 'guest';
  const DEFAULT_ROLE = self::GUEST_ROLE;
  const MEMBER_ROLE = 'member';
  const ADMIN_ROLE = 'admin';

  protected $roleIds = array();

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

  public function getRoleIds() {
    return $this->roleIds;
  }

  public function setRoleIds($roleIds) {
    $this->roleIds = $roleIds;
  }

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
