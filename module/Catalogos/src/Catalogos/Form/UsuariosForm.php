<?php
namespace Catalogos\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
use Catalogos\Model\Perfiles;

/**
 *
 * @author jose.moreno
 *        
 */
class UsuariosForm extends Form
{
    protected $dbAdapter;

    /**
     */
    public function __construct ($name = null,AdapterInterface $dbAdapter){
        $this->dbAdapter = $dbAdapter;
        parent::__construct($name);
        
        /*$this->add(array(
        		'name' => 'idProducto',
        		'type' => 'Zend\Form\Element\Hidden',
        		'attributes' => array('name' => 'idProducto')
        ));*/
        

        
       $this->add(array(
        		'name' => 'idPersona',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'idPersona',        				
        				'required' => 'true',
        		        'id' => 'idPersona',
        		        'style' => 'width:162px'
        		),
        		'options' => array(
        				'label' => 'Persona'				
        		)
        ));
        
        $this->add(array(
        		'name' => 'contrasena',
        		'type' => 'Zend\Form\Element\Password',
        		'attributes' => array(
        				'name' => 'contrasena',
        				'class' => 'easyui-textbox',
        				'required' => 'true'        				
        		),
        		'options' => array(        		        
        				'label' => 'Password:',
        				'label_attributes' => array('class'  => 'fitem')        
        		)
        ));
        
        $this->add(array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'attributes' => array(
                		'name' => 'email',
                		'class' => 'easyui-textbox',
                		'required' => 'true'                        
                ),
                'options' => array(
                		'label' => 'Email:',
                		'label_attributes' => array('class'  => 'fitem')
                )
        ));        

    }  
    

}

?>