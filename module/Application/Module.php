<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Application\Utility\Acl;
use Application\Model\User;
use Application\Model\Role;
use Application\Model\UserRole;
use Application\Model\ResourceTable;
use Application\Model\PermissionTable;
use Application\Model\RolePermissionTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
        		$this,
        		'beforeDispatch'
        ), 100);
        
    }
    
    function beforeDispatch(MvcEvent $event){
        $request = $event->getRequest();
        $response = $event->getResponse();
        $target = $event->getTarget();
        
        $whiteList = array(
        		'Application\Controller\Index-index'
        		//'ZF2AuthAcl\Controller\Index-logout'
        );
        
        $requestUri = $request->getRequestUri();
        $controller = $event->getRouteMatch()->getParam('controller');        
        $action = $event->getRouteMatch()->getParam('action');
        $requestedResourse = $controller . "-" . $action;
        
        //echo "log: ".$requestedResourse.'<br>';
        
        $session = new Container('User');
        
        if ($session->offsetExists('email')) {
        	if ($requestedResourse == 'Application\Controller\Index-index' || in_array($requestedResourse, $whiteList)) {
        		$url = 'sistema';
        		$response->setHeaders($response->getHeaders()
        				->addHeaderLine('Location', $url));
        		$response->setStatusCode(302);        	   
        	} else {        
        		$serviceManager = $event->getApplication()->getServiceManager();
        		$userRole = $session->offsetGet('perfil');       
        		$acl = $serviceManager->get('Acl');
        		
        		$acl->initAcl();        		       		     		
        		$status = $acl->isAccessAllowed($userRole, $controller, $action);
        		
        		if (! $status) {
        			die('<br>Permiso denegado');
        		}
            }
        } else {
            
            if ($requestedResourse != 'Application\Controller\Index-index' && ! in_array($requestedResourse, $whiteList)) {
                $url = 'FacturacionElectronica/public/Application/login';
                $response->setHeaders($response->getHeaders()
                    ->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            }
            $response->sendHeaders();
        }
    }
    
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'AuthService' => function ($serviceManager)
    					{
    						$adapter = $serviceManager->get('Zend\Db\Adapter');
    						$dbAuthAdapter = new DbAuthAdapter($adapter,'usuarios','email','contrasena');
    						$auth = new  AuthenticationService();
    						$auth->setAdapter($dbAuthAdapter);
    						return $auth;
    					},
    					'Acl' => function ($serviceManager)
    					{
    						return new Acl();
    					},
    					'UserTable' => function ($serviceManager){    					    		  
    						return new User($serviceManager->get('Zend\Db\Adapter'));
    					},
    					'RoleTable' => function ($serviceManager)
    					{
    						return new Role($serviceManager->get('Zend\Db\Adapter'));
    					},
    					'UserRoleTable' => function ($serviceManager)
    					{
    						return new UserRole($serviceManager->get('Zend\Db\Adapter'));
    					},
    					'PermissionTable' => function ($serviceManager)
    					{
    						return new PermissionTable($serviceManager->get('Zend\Db\Adapter'));
    					},
    					'ResourceTable' => function ($serviceManager)
    					{
    						return new ResourceTable($serviceManager->get('Zend\Db\Adapter'));
    					},
    					'RolePermissionTable' => function ($serviceManager)
    					{
    						return new RolePermissionTable($serviceManager->get('Zend\Db\Adapter'));
    					}
    			)
    	);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' .__NAMESPACE__
                ),
            ),
        );
    }
    

    

}
