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
                        'label' => 'Nombre',
                )
        ));
    }
}

?>