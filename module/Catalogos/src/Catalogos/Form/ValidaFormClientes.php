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
class ValidaFormClientes implements InputFilterAwareInterface
{
    protected $inputFilter;
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("No se usa");
    }
    
    public function getInputFilter()
    {
        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;
        
        if (!$this->inputFilter) {
        	$inputFilter  = new InputFilter();
        	$factory = new InputFactory();
        	
        	$inputFilter->add($factory->createInput(array(
        	        'name' => 'nombre',
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
                                    $isEmpty => 'Nombre no puede estar vacio.'
                                )
                            ),
                            'break_chain_on_failure' => true
                        )
                    )
        	)));        	
        	$this->inputFilter = $inputFilter;        	
        }
        return $this->inputFilter;        
    }
 
}
?>