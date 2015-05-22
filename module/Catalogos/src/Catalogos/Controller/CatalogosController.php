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
}
