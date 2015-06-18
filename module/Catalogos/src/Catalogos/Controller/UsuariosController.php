<?php
namespace Catalogos\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Catalogos\Model\Perfiles;
//use Application\Utility\Util;
use Zend\Json\Json;
use Catalogos\Form\UsuariosForm;
use Catalogos\Form\ValidaFormUsuarios;
use Catalogos\Model\Usuarios;
use Zend\View\Model\JsonModel;
use Inputfilter\InputFilter;

/**
 * UsuariosController
 *
 * @author: José Manuel Moreno Plaza
 *
 * @version
 *
 */
class UsuariosController extends AbstractActionController
{
    private $dbAdapter;

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated UsuariosController::indexAction() default action
        $this->dbAdapter = $this->getServiceLocator()->get('\Zend\Db\Adapter');
        
        $modeloPerfiles = new Perfiles($this->dbAdapter);       
        
        $comboPerfiles = $modeloPerfiles->listaCombo();
        
        $request = $this->getRequest();
        $usuariosForm = new UsuariosForm('formularioUsuario',$this->dbAdapter);
        $validaForUsuarios = new ValidaFormUsuarios();
        
        $usuariosForm->setInputFilter($validaForUsuarios->getInputFilter());
        
        if ($request->isPost()){
            $data = $request->getPost();
            $usuariosForm->setData($data);
            if ($usuariosForm->isValid()) {
                $data = $usuariosForm->getData();
            }else{
                $errores = $usuariosForm->getMessages();
            }
            
        }
        
        return new ViewModel(array(
                'filterComboPerfiles' => Json::encode($comboPerfiles),
                'form' => $usuariosForm  
        ));
    }
    
    public function listadoAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('\Zend\Db\Adapter');
        
        $modelo = new Usuarios($this->dbAdapter);
        
        $page = intval($this->params()->fromPost('page'));
        $rows = intval($this->params()->fromPost('rows'));
        $offset = ($page-1)*$rows;
        $filterRules = Json::decode($this->params()->fromPost('filterRules'), true);
        
        $json = new JsonModel($modelo->obtenerUsuarios($rows, $offset, $filterRules));
        return $json;
        
    }
    
    public function guardaAction(){
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $usuariosForm = new UsuariosForm("formularioUsuario",$this->dbAdapter);        
        $viewmodel = new ViewModel();
        
        $viewmodel->setTerminal($request->isXmlHttpRequest());
        
        $messages = array();
        
        if ($request->isPost()){
            $usuariosForm->setData($request->getPost());
            if (! $usuariosForm->isValid()) {
                $errors = $usuariosForm->getMessages();
                 
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
            $modelo = new Usuarios($this->dbAdapter);
            $guarda = $modelo->guardar($usuariosForm->getData());
            $response->setContent(\Zend\Json\Json::encode($guarda));
        } 

        return $response;
    }

    public function combogridAction() {
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        $modelo = new Usuarios($this->dbAdapter);
        $filer = new InputFilter();
        if ($this->request->isXmlHttpRequest()) {
            $q = $filer->process($this->params()->fromPost('q'));      
            
            $json = new JsonModel($modelo->comboGrid($q,10,0));
            return $json;
            
        }
    
    }
    
}