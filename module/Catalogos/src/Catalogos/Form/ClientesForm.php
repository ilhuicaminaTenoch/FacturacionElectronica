<?php
namespace Catalogos\Form;

use Zend\Form\Form;
/**
 *
 * @author jose.moreno
 *        
 */
class ClientesForm extends Form
{
    public function __construct($name = null){
        parent::__construct($name);
        
        $this->add(array(
                'name' => 'nombreCompleto',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                        'name' => 'nombreCompleto',
                        'class' => 'easyui-textbox',                        
                        'required' => 'true'                                              
                ),
                'options' => array(
                        'label' => 'Nombre completo:',
                        'label_attributes' => array('class'  => 'fitem'),
                        
                )
        ));

        $this->add(array(
        		'name' => 'fechaDeNacimiento',
        		'type' => 'Zend\Form\Element\Date',                
                'options' => array(
                		'label' => 'Fecha de nacimiento',
                        'label_attributes' => array('class'  => 'fitem'),
                		'format' => 'Y-m-d'
                ),
                'attributes' => array(                		
                		'step' => '1',
                        'class' => 'easyui-textbox',
                        'required' => 'true',                        
                )
                        		
        ));
        
        $this->add(array(
        		'name' => 'calle',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'calle',
        				'class' => 'easyui-textbox',
        				'required' => 'true'
        		),
        		'options' => array(
        				'label' => 'Calle:',
        				'label_attributes' => array('class'  => 'fitem'),
        
        		)
        ));
        
        $this->add(array(
        		'name' => 'numeroInterior',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'numeroInterior',
        				'class' => 'easyui-textbox',        				
        		),
        		'options' => array(
        				'label' => 'Numero Interior:',
        				'label_attributes' => array('class'  => 'fitem'),
        
        		)
        ));
        
        $this->add(array(
        		'name' => 'numeroExterior',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'numeroExterior',
        				'class' => 'easyui-textbox',
        				'required' => 'true'
        		),
        		'options' => array(
        				'label' => 'Numero exterior:',
        				'label_attributes' => array('class'  => 'fitem'),
        
        		)
        ));
        
        $this->add(array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'idCodigoPostal',
                'attributes' => array(
                		'id'    => 'idCodigoPostal'
                )
        ));
        $this->add(array(
        		'type' => 'Zend\Form\Element\Text',
        		'name' => 'codigo',
        		'attributes' => array(
        				'id'    => 'codigo',
        				'class' => 'easyui-textbox',
        				'required' => 'true'        		        
        		),
        		'options' => array(
        				'label' => 'Codigo postal',
        		),
        
        ));
        
        $this->add(array(
        		'name' => 'telefonoMovil',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'telefonoMovil',
        				'class' => 'easyui-textbox',
        				'required' => 'true'
        		),
        		'options' => array(
        				'label' => 'Telefono movil:',
        				'label_attributes' => array('class'  => 'fitem'),
        
        		)
        ));
        
        $this->add(array(
        		'name' => 'asentamiento',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'asentamiento',
        				'class' => 'easyui-textbox',
        				'required' => 'true',
        		        'disabled' =>'disabled'
        		),
        		'options' => array(
        				'label' => 'Colonia:',
        				'label_attributes' => array('class'  => 'fitem'),
        
        		)
        ));
        
        $this->add(array(
        		'name' => 'municipio',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'municipio',
        				'class' => 'easyui-textbox',
        				'required' => 'true',
        				'disabled' =>'disabled'
        		),
        		'options' => array(
        				'label' => 'Municipio:',
        				'label_attributes' => array('class'  => 'fitem'),
        
        		)
        ));
        
        $this->add(array(
        		'name' => 'ciudad',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'ciudad',
        				'class' => 'easyui-textbox',
        				'required' => 'true',
        				'disabled' =>'disabled'
        		),
        		'options' => array(
        				'label' => 'Ciudad:',
        				'label_attributes' => array('class'  => 'fitem'),
        
        		)
        ));
        
        $this->add(array(
        		'name' => 'estado',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'estado',
        				'class' => 'easyui-textbox',
        				'required' => 'true',
        				'disabled' =>'disabled'
        		),
        		'options' => array(
        				'label' => 'Estado:',
        				'label_attributes' => array('class'  => 'fitem'),
        
        		)
        ));
       
    }    
    
}

?>