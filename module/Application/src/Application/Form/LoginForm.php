<?php
namespace Application\Form;

use Zend\Form\Form;

/**
 *
 * @author jose.moreno
 *        
 */
class LoginForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct($name);
              
        $this->add(array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                        'id' => 'email',
                        'class' => 'easyui-textbox',
                        'style' => 'width:100%;height:40px;padding:12px',
                        'data-options' => 'prompt:\'Username\',iconCls:\'icon-man\',iconWidth:38, required:true'
                ),
                'options' => array(
                        'label' => 'Email',
                )
        ));

        $this->add(array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => array(
                        'id' => 'contrasena',
                        'class' => 'easyui-textbox',
                        'style' => 'width:100%;height:40px;padding:12px',
                        'data-options' => 'prompt:\'Password\',iconCls:\'icon-lock\',iconWidth:38, required:true'
                ),
                'options' => array(
                        'label' => 'Contrase&ntilde;a',
                )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'loginCsrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 3600
                )
            )
        ));
        
        $this->add(array(
        		'name' => 'submit',
        		'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(                		
                		'id' => 'btn_submit',
                		'value'=>'Login',
                ),
        ));
        
    }
}

?>