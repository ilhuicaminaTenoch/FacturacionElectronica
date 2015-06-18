<?php
namespace Catalogos\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
use Catalogos\Model\Productos;
/**
 *
 * @author jose.moreno
 *        
 */
class ProductosForm extends Form
{
    protected $dbAdapter;

    /**
     */
    public function __construct ($name = null,AdapterInterface $dbAdapter){
        $this->dbAdapter = $dbAdapter;
        parent::__construct($name);
        
        $this->add(array(
        		'name' => 'idProducto',
        		'type' => 'Zend\Form\Element\Hidden',
        		'attributes' => array('name' => 'idProducto')
        ));
        
        $this->add(array(
        		'name' => 'idCategoria',
        		'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                        'name' => 'idCategoria',
        				'class' => 'easyui-combobox',
        				'required' => 'true',
                ),
        		'options' => array(
        		        'label' => 'Categoria',
        		        'value_options' => $this->getOptionsForSelect(),        		        
        		        'label_attributes' => array('class'  => 'fitem')
        		)         
        ));
        
        $this->add(array(
        		'name' => 'nombreProducto',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'nombreProducto',
        				'class' => 'easyui-textbox',
        				'required' => 'true'        				
        		),
        		'options' => array(        		        
        				'label' => 'Producto:',
        				'label_attributes' => array('class'  => 'fitem')        
        		)
        ));
        
        $this->add(array(
                'name' => 'precio',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                		'name' => 'precio',
                		'class' => 'easyui-numberbox',
                		'required' => 'true',
                        'data-options' => 'precision:2,groupSeparator:\',\',decimalSeparator:\'.\',prefix:\'$\''
                ),
                'options' => array(
                		'label' => 'precio:',
                		'label_attributes' => array('class'  => 'fitem')
                )
        ));
        
        $this->add(array(
        		'name' => 'stock',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'name' => 'stock',
        				'class' => 'easyui-numberbox',
        		        'data-options' => 'min:10,max:500,precision:0,required:true'
        		        
        		),
        		'options' => array(
        				'label' => 'Stock:',
        				'label_attributes' => array('class'  => 'fitem')
        		)
        ));
    }
    
    public function getOptionsForSelect()
    {
    	$modole = new Productos($this->dbAdapter);
    	$lista = $modole->listadoCategorias();
    	
    	$selectData = array();
    	
    	foreach ($lista as $res) {
    		$selectData[$res['value']] = $res['text'];
    	}
    	return $selectData;    	
    }
}

?>