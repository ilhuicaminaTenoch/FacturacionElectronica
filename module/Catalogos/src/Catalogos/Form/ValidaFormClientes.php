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
        $regex = \Zend\Validator\Regex::NOT_MATCH;
        
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
            
            $inputFilter->add(
                    $factory->createInput(array(
                            'name' => 'rfc',
                            'required' => true,
                            'filters' => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim')
                            ),
                            'validators' => array(
                                    array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                    'messages' => array(
                                                            $isEmpty => 'RFC no puede estar vacio.'
                                                    ),
                                                    'encoding' => 'UTF-8',
                                                    'min' => 5,
                                                    'max' => 15
                                            ),
                                            'break_chain_on_failure' => true,                                            
                                    ),
                                    array(
                                            'name' => 'Regex',
                                            'options' => array(
                                                    'pattern' => '/^([A-Z,Ñ,&]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z|\d]{3})$/',
                                                    'messages' => array(
                                                            $regex => 'RFC con formato invalido'
                                                    ),
                                            ),
                                    ),
                            )
                    )));
            
            $inputFilter->add(
                    $factory->createInput(array(
                            'name' => 'curp',
                            'required' => true,
                            'filters' => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim')
                            ),
                            'validators' => array(
                                    array(
                                            'name' => 'NotEmpty',
                                            'options' => array(
                                                    'messages' => array(
                                                            $isEmpty => 'CURP no puede estar vacio.'
                                                    ),
                                                    'encoding' => 'UTF-8',
                                                    'min' => 5,
                                                    'max' => 20
                                            ),
                                            'break_chain_on_failure' => true,
                                    ),
                                    array(
                                            'name' => 'Regex',
                                            'options' => array(
                                                    'pattern' => '/^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/',
                                                    'messages' => array(
                                                            $regex => 'CURP con formato invalido'
                                                    ),
                                            ),
                                    ),
                            )
                    )));
        	
        	
        	$this->inputFilter = $inputFilter;        	
        }
        return $this->inputFilter;        
    }
 
}
?>