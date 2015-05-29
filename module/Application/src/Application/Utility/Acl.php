<?php
namespace Application\Utility;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author jose.moreno
 *        
 */
class Acl extends ZendAcl implements ServiceLocatorAwareInterface
{
    const DEFAULT_ROLE = 'administrador';
    protected $_roleTableObject;
    protected $serviceLocator;
    protected $roles;
    protected $permissions;
    protected $resources;
    protected $rolePermission;
    protected $commonPermission;
    
public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        
        return $this;
    }
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    public function initAcl()
    {
        
        $this->roles = $this->_getAllRoles();
        $this->resources = $this->_getAllResources();
        $this->rolePermission = $this->_getRolePermissions();        
        $this->commonPermission = array(
            'Application\Controller\Index' => array(
                'logout',
                'index'                
            )
        );
        $this->_addRoles()
            ->_addResources()
            ->_addRoleResources();
    }
    public function isAccessAllowed($role, $resource, $permission)
    {   
        //$this->debugAcl($role, $resource, $permission);
               
        if (! $this->hasResource($resource)) {
            return false;
        }
        if ($this->isAllowed($role, $resource, $permission)) {
            return true;
        }
        return false;
    }
    protected function _addRoles()
    {
        $this->addRole(new Role(self::DEFAULT_ROLE));       
        if (! empty($this->roles)) {            
            foreach ($this->roles as $role) {
                $roleName = $role['nombre'];                
                if (! $this->hasRole($roleName)){                                        
                    $this->addRole(new Role($roleName), self::DEFAULT_ROLE);
                }
            }
        }
        return $this;
    }
    protected function _addResources()
    {
        //echo"<pre>"; print_r(($this->resources)); echo"</pre>";
        if (! empty($this->resources)) {            
            foreach ($this->resources as $resource) {
                if (! $this->hasResource($resource['nombreRecurso'])) {                    
                    $this->addResource(new Resource($resource['nombreRecurso']));
                }
            }
        }
         
        // add common resources
        if (! empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {                
                if (! $this->hasResource($resource)) {                    
                    $this->addResource(new Resource($resource));
                }
            }
        }
        
        return $this;
    }
    protected function _addRoleResources()
    {
        //echo"<pre>"; print_r($this->commonPermission); echo"</pre>";
        // allow common resource/permission to guest user
        if (! empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                //echo"<pre>"; print_r($permissions); echo"</pre>";
                foreach ($permissions as $permission) {                    
                    $this->allow(self::DEFAULT_ROLE, $resource, $permission);
                }
            }
        }        
        if (! empty($this->rolePermission)) {            
            foreach ($this->rolePermission as $rolePermissions) {
                $this->allow($rolePermissions['nombre'], $rolePermissions['nombreRecurso'], $rolePermissions['nombrePermiso']);                
            }
        }
        
        return $this;
    }
    protected function _getAllRoles()
    {
        $roleTable = $this->getServiceLocator()->get("RoleTable");
        return $roleTable->getUserRoles();
    }
    protected function _getAllResources()
    {
        $resourceTable = $this->getServiceLocator()->get("ResourceTable");
        return $resourceTable->getAllResources();
    }
    protected function _getRolePermissions()
    {
        $rolePermissionTable = $this->getServiceLocator()->get("RolePermissionTable");
        return $rolePermissionTable->getRolePermissions();
    }
    
    private function debugAcl($role, $resource, $permission)
    {
        echo 'Role: ' . $role . '==> ' . $resource. '\\' . $permission . '<br/>';
    }
   
}

?>