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
class ValidaFormProductos implements InputFilterAwareInterface
{
    protected $inputFilter;

    public function setInputFilter (InputFilterInterface $inputFilter)
    {
        throw new \Exception("No se usa");
    }

    public function getInputFilter ()
    {
        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        
        
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            $inputFilter->add(
                    $factory->createInput(array(
                            'name'     => 'idProducto',                             
                             'filters'  => array(
                                 array('name' => 'Int'),
                             ),
                    )));
            
            /*$inputFilter->add(
            		$factory->createInput(array(
            				'name'     => 'idCategoria',
            				'required' => true,
            				'filters'  => array(
            						array('name' => 'Int'),
            				),
            		)));*/
            
            $inputFilter->add(
                    $factory->createInput(
                            array(
                                    'name' => 'nombreProducto',
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
                                                                    $isEmpty => 'Producto no puede estar vacio.'
                                                            ),
                                                            'encoding' => 'UTF-8',
                                                            'min' => 5,
                                                            'max' => 255
                                                    ),
                                                    'break_chain_on_failure' => true
                                            )
                                    )
                            )));
            
            
            $inputFilter->add(
            		$factory->createInput(array(
            				'name'     => 'precio',
            				'required' => true,
            				'validators'  => array(
            						array('name' => 'Float'),
            				),
            		)));
            
            
           
            
            $inputFilter->add(
            		$factory->createInput(array(
            				'name'     => 'stock',
            				'required' => true,
            				'filters'  => array(
            						array('name' => 'Int'),
            				),
            		)));


        	
        	
        	$this->inputFilter = $inputFilter;        	
        }
        return $this->inputFilter;        
    }
 
}
?>