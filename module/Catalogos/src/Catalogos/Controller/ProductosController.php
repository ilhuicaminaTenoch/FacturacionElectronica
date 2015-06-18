<?php
namespace Catalogos\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Catalogos\Model\Productos;
use Application\Utility\Util;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;
use Catalogos\Form\ProductosForm;
use Catalogos\Form\ValidaFormProductos;

/**
 * ProductosController
 *
 * @author:José Manuel Moreno Plaza
 *
 * @version
 *
 */
class ProductosController extends AbstractActionController
{
    private $dbAdapter;

    /**
     * The default action - show the home page
     */   
    public function indexAction ()
    {
        
        $this->dbAdapter = $this->getServiceLocator()->get('\Zend\Db\Adapter');
        $modelo = new Productos($this->dbAdapter);
        $modeloUtil = new Util($this->dbAdapter);        
        
        /*
         * Llena el combo del filtrado del grid 
         */
        $consultaCategorias = $modelo->listadoCategorias();
        $comboCategorias = $modeloUtil->filterCombo($consultaCategorias);
        $filterComboCategorias = Json::encode($comboCategorias);

        /*
         * Procesa formulario
         */
        $request = $this->getRequest();
        $productoForm = new ProductosForm('fmProductos',$this->dbAdapter);
        $validaFormProducto = new ValidaFormProductos();
        $productoForm->setInputFilter($validaFormProducto->getInputFilter());
        if ($request->isPost()) {
        	$data = $request->getPost();
        	$productoForm->setData($data);
        }else{
            $errores = $productoForm->getMessages();
        }        
        
        return new ViewModel(array(
                'filterComboCategorias'=>$filterComboCategorias,
                'formulario' => $productoForm,
                //'listaCategorias' => $consultaCategorias
        ));
    }
    
    public function listadoAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('\Zend\Db\Adapter');
        $modelo = new Productos($this->dbAdapter);
        
        $page = $this->params()->fromQuery('page');
        $rows = $this->params()->fromQuery('rows');
        
        $filterRules = Json::decode($this->params()->fromQuery('filterRules'), true);
        $offset = ($page-1)*$rows;
        $consulta = $modelo->todosLosProductos($offset, $rows, $filterRules);
        $json = new JsonModel($consulta);
        return $json;        
    }
    
    public function guardaAction()
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
    
    	$viewmodel = new ViewModel();
    	$productoForm = new ProductosForm('fmProducto',$this->dbAdapter);
    	$validaForm = new ValidaFormProductos();
       
        $productoForm->setInputFilter($validaForm->getInputFilter());
    	
    
    	$viewmodel->setTerminal($request->isXmlHttpRequest());
    	
    	$messages = array();
    	if ($request->isPost()){
    		$productoForm->setData($request->getPost());   		
    		if (! $productoForm->isValid()) {
    			$errors = $productoForm->getMessages();
    			 
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
    		$modelo = new Productos($this->dbAdapter);
    		$guarda = $modelo->guarda($productoForm->getData());
    		$response->setContent(\Zend\Json\Json::encode($guarda));
    	}
    	return $response;
    }
    
    public function updateAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    
    	$viewmodel = new ViewModel();
    	$clienteForm = new ProductosForm('fmProductos',$this->dbAdapter);    
    
    
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
    		$modelo = new Productos($this->dbAdapter);
    		$guarda = $modelo->updateClientes($clienteForm->getData());
    		$response->setContent(\Zend\Json\Json::encode($guarda));
    	}
    	return $response;
    }
    
    public function eliminaAction(){
    	$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	$viewmodel = new ViewModel();
    
    	$viewmodel->setTerminal($request->isXmlHttpRequest());
    
    
    	$messages = array();
    	$idProducto = $this->params()->fromPost('idProducto');
    	if ($request->isPost()){
    		$modelo = new Productos($this->dbAdapter);
    		$elimina = $modelo->remove($idProducto);
    		$response->setContent(\Zend\Json\Json::encode($elimina));
    		return $response;
    	}
    }
}