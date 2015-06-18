<?php
namespace Catalogos\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Catalogos\Model\Categorias;
use Zend\Json\Json;
use Inputfilter\InputFilter;


/**
 * CategoriasController
 *
 * @author: José Manuel Moreno Plaza
 *
 * @version
 *
 */
class CategoriasController extends AbstractActionController
{
    private $dbAdapter;

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        
        return new ViewModel();
    }
    
    public function listadoAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        if ($this->request->isXmlHttpRequest()) {
        	$modelo = new Categorias($this->dbAdapter);
        	$page = $this->params()->fromPost('page');
        	$rows = $this->params()->fromPost('rows');        	
        	
        	$filterRules = Json::decode($this->params()->fromPost('filterRules'), true);
        	$offset = ($page-1)*$rows;
        	$listado = $modelo->listado($offset,$rows,$filterRules);
        	$json = new JsonModel($listado);
        	return $json;
        }
    }
    
    public function updateAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        if ($this->request->isXmlHttpRequest()) {
            $idCategoria = $this->params()->fromPost('idCategoria');
            $nombreCategoria = $this->params()->fromPost('nombreCategoria');
            
            $arregloDatos = array('idCategoria' => $idCategoria,'nombreCategoria'=>$nombreCategoria);
            $modelo = new Categorias($this->dbAdapter);
            
            $json = new JsonModel($modelo->actualizar($arregloDatos));
            return $json;
            
        }
    }
    
    public function guardaAction(){
    	$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
    	if ($this->request->isXmlHttpRequest()) {
    	    $filter = new InputFilter();  
    	    
    		$idCategoria = $filter->process($this->params()->fromPost('idCategoria'));
    		$nombreCategoria = $filter->process($this->params()->fromPost('nombreCategoria'));
    
    		$arregloDatos = array('idCategoria' => $idCategoria,'nombreCategoria'=>$nombreCategoria);
    		$modelo = new Categorias($this->dbAdapter);
    
    		$json = new JsonModel($modelo->guardar($arregloDatos));
    		return $json;
    
    	}
    }
    
    public function eliminaAction() {
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        if ($this->request->isXmlHttpRequest()) {
            $filter = new InputFilter();
            $idCategoria = $filter->process($this->params()->fromPost('id'));
            $modelo = new Categorias($this->dbAdapter);
            $json = new JsonModel($modelo->elimina($idCategoria));
            return $json;            
        }
    }
}