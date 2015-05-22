<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Sistema for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sistema\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Sistema\Model\Tree;


class SistemaController extends AbstractActionController
{
    private $dbAdapter;
    
    public function indexAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        $userSession = new Container('User');
        $usuario = $userSession->offsetGet('perfil');
        $nombreUsuario = $userSession->offsetGet('nombreCompleto');
        $objetoMenu = new Tree($this->dbAdapter);
        $raiz= $objetoMenu->raiz();        
        $arrelo = $objetoMenu->renderTree($raiz);
       return array(
            'tipoPerfil' => $usuario,
            'nombreCompleto'    => $nombreUsuario,
            'menu' => $arrelo
        );
    }

    public function menuAction()
    {
        
        return array();
    }
}
