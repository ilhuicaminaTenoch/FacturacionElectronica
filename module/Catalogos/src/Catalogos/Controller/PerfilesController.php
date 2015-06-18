<?php
namespace Catalogos\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Catalogos\Model\Perfiles;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;
use Inputfilter\InputFilter;

/**
 * PerfilesController
 *
 * @author:José Manuel Moreno Plaza
 *
 * @version
 *
 */
class PerfilesController extends AbstractActionController
{
    private $dbAdapter;

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated PerfilesController::indexAction() default action
        return new ViewModel();
    }
    
    public function listadoAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        if ($this->request->isXmlHttpRequest()) {
            $modelo = new Perfiles($this->dbAdapter);
            $page = intval($this->params()->fromPost('page'));
            $rows = intval($this->params()->fromPost('rows'));
            $offset = ($page-1)*$rows;
            $filterRules = Json::decode($this->params()->fromPost('filterRules'), true);
            $listado = $modelo->listado($rows, intval($offset), $filterRules);
            $json = new JsonModel($listado);
            return $json;
        }
    }
    
    public function guardaAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        if ($this->request->isXmlHttpRequest()) {
            $filter = new InputFilter();
            $nombre = $filter->process($this->params()->fromPost('nombre'));
            $idPerfil = $filter->process($this->params()->fromPost('idPerfil'));
            $modelo = new Perfiles($this->dbAdapter);
            $arregloDatos = array('idPerfil' => $idPerfil,'nombre'=>$nombre);
            
            $json = new JsonModel($modelo->guardar($arregloDatos));
            return $json;
        }
        
    }
    
    public function eliminaAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        if ($this->request->isXmlHttpRequest()) {
            $filter = new InputFilter();
            $idPerfil = $filter->process($this->params()->fromPost('id'));
            $modelo = new Perfiles($this->dbAdapter);
            
            $json = new JsonModel($modelo->elimina($idPerfil));
            return $json;
        }
        
    }
    
    public function actualizaAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        if ($this->request->isXmlHttpRequest()) {
            $filter = new InputFilter();
            $nombre = $filter->process($this->params()->fromPost('nombre'));
            $idPerfil = $filter->process($this->params()->fromPost('idPerfil'));
            $modelo = new Perfiles($this->dbAdapter);
            $arregloDatos = array('idPerfil' => $idPerfil,'nombre'=>$nombre);
            $json = new JsonModel($modelo->actualizar($arregloDatos));
            return $json;
        }
        
    }
}