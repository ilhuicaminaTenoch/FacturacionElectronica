<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Form\ValidaFormularioLogin;
use Application\Utility\UserPassword;
use Zend\Session\Container;





class IndexController extends AbstractActionController
{
    public $dbAdapter;
    public function indexAction()
    {
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $request = $this->getRequest();
        
        $view = new ViewModel();
        $loginForm = new LoginForm('loginForm');
        $formValidation = new ValidaFormularioLogin();
        $loginForm->setInputFilter($formValidation->getInputFilter());
        
        if ($request->isPost()) {          
        	$data = $request->getPost();
        	$loginForm->setData($data);
        
        	if ($loginForm->isValid()) {        	     	    
        		$data = $loginForm->getData();
        
        		$userPassword = new UserPassword('sha1');
        		$encyptPass = $userPassword->create($data['password']);
        
        		$authService = $this->getServiceLocator()->get('AuthService');
        		             
        		$authService->getAdapter()
        		->setIdentity($data['email'])
        		->setCredential($encyptPass);
        		
        		$result = $authService->authenticate();        		    		
        		if ($result->isValid()) {                   
        			$userDetails = $this->_getUserDetails(
        			        array('email' => $data['email']), 
        			        array('idUsuario'));        			
        			$session = new Container('User');
        			$session->offsetSet('email', $data['email']);
        			$session->offsetSet('idUsuario', $userDetails[0]['idUsuario']);
        			$session->offsetSet('idPerfil', $userDetails[0]['idPerfil']);
        			$session->offsetSet('perfil', $userDetails[0]['nombre']);
        			$session->offsetSet('nombreCompleto',$userDetails[0]['nombreCompleto']);		
        			//echo"<pre>"; print_r($session->perfil); echo"</pre>";
        			$this->flashMessenger()->addMessage(array(
        					'success' => 'Login Success.'
        			));        			
        		} else {
        			$this->flashMessenger()->addMessage(array(
        					'error' => 'Credenciales invalidas.'
        			));
        		
        		}
        		
        	} else {
        		$errors = $loginForm->getMessages();
        		
        	}
        }
        
        $view->setVariable('form', $loginForm);
        return $view;       
    }
    
    public function logoutAction(){
    	$authService = $this->getServiceLocator()->get('AuthService');
    
    	$session = new Container('User');
    	$session->getManager()->destroy();
    
    	$authService->clearIdentity();
    	return $this->redirect()->toUrl('login');
    }
    
    private function _getUserDetails($where, $columns)
    {
        $userTable = $this->getServiceLocator()->get("UserTable");
        $users = $userTable->getUsers($where, $columns);
        return $users;
    }
     
   
}
