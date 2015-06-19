<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Catalogos for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Catalogos\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Catalogos\Model\Clientes;
use Zend\Json\Json;
use Catalogos\Form\ClientesForm;
use Catalogos\Form\ValidaFormClientes;
use Application\Utility\Util;


class CatalogosController extends AbstractActionController
{
    private $dbAdapter;
    public function indexAction()
    {
        $view = new ViewModel();
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $request = $this->getRequest();
        $clienteForm = new ClientesForm('fmCliente');
        $validaForm = new ValidaFormClientes();
        
        $clienteForm->setInputFilter($validaForm->getInputFilter());
        if ($request->isPost()) {
            $data = $request->getPost();
            $clienteForm->setData($data);
            if ($clienteForm->isValid()) {
                $data = $clienteForm->getData();
            }            
            
        }else {
            $errors = $clienteForm->getMessages();        		
        }
        $view->setVariable('form', $clienteForm);
        
        return $view;
    }
    
    
    public function listadoAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');        
        if ($this->request->isXmlHttpRequest()) {        	
            $modeloClientes = new Clientes($this->dbAdapter);
            $page = $this->params()->fromQuery('page');
            $rows = $this->params()->fromQuery('rows');
            $filterRules = Json::decode($this->params()->fromQuery('filterRules'), true);
            $offset = ($page-1)*$rows;            
            $listado = $modeloClientes->listaClientes($offset,$rows,$filterRules);        	
            $json = new JsonModel($listado);           
            return $json;
        }       
    }
    
    public function guardaclienteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $viewmodel = new ViewModel();        
        $clienteForm = new ClientesForm('fmCliente');        
        $validaForm = new ValidaFormClientes();
        
        $clienteForm->setInputFilter($validaForm->getInputFilter());
        
        
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        
        $viewmodel->setTerminal($request->isXmlHttpRequest());       
       
        $messages = array();
        if ($request->isPost()){                
            $clienteForm->setData($request->getPost());
            if (! $clienteForm->isValid()) {
            	$errors = $clienteForm->getMessages(); 	
            	
            	foreach($errors as $key=>$row){
            	    if (!empty($row) && $key != 'submit') {
            	        foreach($row as $keyer => $rower){            	        	
            	            $messages[$key][] = $rower;
            	        }
            	    }
            	}
            }
        }
        
        if (!empty($messages)){            
        	$response->setContent(\Zend\Json\Json::encode($messages));            
        } else {       	
        	$modelo = new Clientes($this->dbAdapter);
        	$guarda = $modelo->guardaClientes($clienteForm->getData());
        	$response->setContent(\Zend\Json\Json::encode($guarda));        	
        }
        return $response;            
    }
    
    public function consultacpAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        $viewModel = new ViewModel();
        if ($this->request->isXmlHttpRequest())
        {
            $modeloCp = new Util($this->dbAdapter);
            $codigoPostal = $this->params()->fromPost('codigoPostal');
            $bandera = $this->params()->fromPost('bandera');
            $consultaCodigoPostal = $modeloCp->consultaCodigoPostal($codigoPostal);
           
            $viewModel->setVariables(array('datos'=>$consultaCodigoPostal,"bandera"=>$bandera))
            ->setTerminal(true);
            
            return $viewModel;
        }
    }
    
    public function updateclienteAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    
    	$viewmodel = new ViewModel();
    	$clienteForm = new ClientesForm('fmCliente');
    	$validaForm = new ValidaFormClientes();
    	
    	$clienteForm->setInputFilter($validaForm->getInputFilter());
    
    
    	$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
    
    	$viewmodel->setTerminal($request->isXmlHttpRequest());
    	 
    	$messages = array();
    	if ($request->isPost()){
    		$clienteForm->setData($request->getPost());
    		if (! $clienteForm->isValid()) {
    			$errors = $clienteForm->getMessages();
    			 
    			foreach($errors as $key=>$row){
    				if (!empty($row) && $key != 'submit') {
    					foreach($row as $keyer => $rower){
    						$messages[$key][] = $rower;
    					}
    				}
    			}
    		}
    	}
    
    	if (!empty($messages)){
    		$response->setContent(\Zend\Json\Json::encode($messages));
    	} else {
    		$modelo = new Clientes($this->dbAdapter);
    		$guarda = $modelo->updateClientes($clienteForm->getData());
    		$response->setContent(\Zend\Json\Json::encode($guarda));
    	}
    	return $response;
    }
    
    public function eliminaclienteAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        $request = $this->getRequest();
        $response = $this->getResponse();
        $viewmodel = new ViewModel();
        
        $viewmodel->setTerminal($request->isXmlHttpRequest());
        
        
        $messages = array();
        $idCliente = $this->params()->fromPost('idCliente');
        if ($request->isPost()){         
            $modelo = new Clientes($this->dbAdapter);
            $elimina = $modelo->removeCliente($idCliente);
            $response->setContent(\Zend\Json\Json::encode($elimina));
            return $response;
        }     
    }
     
    
}
