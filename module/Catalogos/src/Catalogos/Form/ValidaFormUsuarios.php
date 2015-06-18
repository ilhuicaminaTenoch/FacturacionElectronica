<?php
namespace Catalogos\Form;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 *
 * @author jose.moreno
 *        
 */
class ValidaFormUsuarios implements InputFilterAwareInterface
{
    protected $inputFilter;

    public function setInputFilter (InputFilterInterface $inputFilter)
    {
        throw new \Exception("No se usa");
    }

    public function getInputFilter ()
    {
        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;
        
        
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            
            
            $inputFilter->add(
            		$factory->createInput(array(
            				'name'     => 'idPersona',
            				'filters'  => array(
            						array('name' => 'Int'),
            				),
            		)));
            
            
            $inputFilter->add(
                    $factory->createInput(
                            array(
                                    'name' => 'contrasena',
                                    'required' => true,
                                    'filters' => array(
                                            array(
                                                    'name' => 'StripTags'
                                            ),
                                            array(
                                                    'name' => 'StringTrim'
                                            )
                                    ),
                                    'validators' => array(
                                            array(
                                                    'name' => 'NotEmpty',
                                                    'options' => array(
                                                            'messages' => array(
                                                                    $isEmpty => 'Contraseña no puede estar vacio.'
                                                            ),
                                                            'encoding' => 'UTF-8',
                                                            'min' => 8,
                                                            'max' => 16
                                                    ),
                                                    'break_chain_on_failure' => true
                                            )
                                    )
                            )));
            
            
           $inputFilter->add($factory->createInput(array(
        	        'name' => 'email',
        	        'required' => true,        	       
        	        'filters' => array(
        	        		array('name' => 'StripTags'),
        	                array('name' => 'StringTrim'),
        	        ),
        	        'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                            'options' => array(
                                'messages' => array(
                                    $isEmpty => 'Email no puede estar vacio.'
                                )
                            ),
                            'break_chain_on_failure' => true
                        ),
                        array(
                            'name' => 'EmailAddress',
                            'options' => array(
                                'messages' => array(
                                    $invalidEmail => 'Email indroducido no valido.'
                                )
                            )
                        )
                    )
        	)));
            
            
           
            
           


        	
        	
        	$this->inputFilter = $inputFilter;        	
        }
        return $this->inputFilter;        
    }
 
}
?>