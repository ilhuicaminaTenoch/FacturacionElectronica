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
                             'required' => true,
                             'filters'  => array(
                                 array('name' => 'Int'),
                             ),
                    )));
            
            $inputFilter->add(
                    $factory->createInput(
                            array(
                                    'name' => 'nombreCompleto',
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
                                                                    $isEmpty => 'Nombre no puede estar vacio.'
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
                    $factory->createInput(
                            array(
                                    'name' => 'fechaDeNacimiento',
                                    'validators' => array(
                                            array(
                                                    'name' => 'Between',
                                                    'options' => array(
                                                            'min' => '1970-01-01',
                                                            'max' => date('Y-m-d')
                                                    )
                                            )
                                    )
                            )));
            
            $inputFilter->add(
                    $factory->createInput(
                            array(
                                    'name' => 'calle',
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
                                                                    $isEmpty => 'Calle no puede estar vacio.'
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
                    $factory->createInput(
                            array(
                                    'name' => 'numeroInterior',
                                    'required' => false,
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
                                                                    $isEmpty => 'Numero interior no puede estar vacio.'
                                                            ),
                                                            'encoding' => 'UTF-8',
                                                            'min' => 5,
                                                            'max' => 30
                                                    ),
                                                    'break_chain_on_failure' => true
                                            )
                                    )
                            )));
            
            $inputFilter->add(
                    $factory->createInput(
                            array(
                                    'name' => 'numeroExterior',
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
                                                                    $isEmpty => 'Numero exterior no puede estar vacio.'
                                                            ),
                                                            'encoding' => 'UTF-8',
                                                            'min' => 5,
                                                            'max' => 30
                                                    ),
                                                    'break_chain_on_failure' => true
                                            )
                                    )
                            )));
            
            $inputFilter->add(
                    $factory->createInput(
                            array(
            						'name' => 'idCodigoPostal',
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
            														$isEmpty => 'Codigo postal no puede estar vacio.'
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
            		$factory->createInput(
            				array(
            						'name' => 'codigo',
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
            														$isEmpty => 'Codigo postal no puede estar vacio.'
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
            		$factory->createInput(
            				array(
            						'name' => 'telefonoMovil',
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
            														$isEmpty => 'Movil no puede estar vacio.'
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
            		$factory->createInput(
            				array(
            						'name' => 'asentamiento',
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
            														$isEmpty => 'Colonia no puede estar vacio.'
            												),
            												'encoding' => 'UTF-8',
            												'min' => 5,
            												'max' => 30
            										),
            										'break_chain_on_failure' => true
            								)
            						)
            				)));
            
            $inputFilter->add(
            		$factory->createInput(
            				array(
            						'name' => 'municipio',
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
            														$isEmpty => 'Municipio no puede estar vacio.'
            												),
            												'encoding' => 'UTF-8',
            												'min' => 5,
            												'max' => 30
            										),
            										'break_chain_on_failure' => true
            								)
            						)
            				)));
            
            $inputFilter->add(
            		$factory->createInput(
            				array(
            						'name' => 'ciudad',
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
            														$isEmpty => 'Ciudad no puede estar vacio.'
            												),
            												'encoding' => 'UTF-8',
            												'min' => 5,
            												'max' => 30
            										),
            										'break_chain_on_failure' => true
            								)
            						)
            				)));
            
            $inputFilter->add(
            		$factory->createInput(
            				array(
            						'name' => 'municipio',
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
            														$isEmpty => 'Municipio no puede estar vacio.'
            												),
            												'encoding' => 'UTF-8',
            												'min' => 5,
            												'max' => 30
            										),
            										'break_chain_on_failure' => true
            								)
            						)
            				)));
            
            $inputFilter->add(
            		$factory->createInput(
            				array(
            						'name' => 'estado',
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
            														$isEmpty => 'Estado no puede estar vacio.'
            												),
            												'encoding' => 'UTF-8',
            												'min' => 5,
            												'max' => 30
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