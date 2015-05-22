<?php
namespace Application\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
/**
 *
 * @author jose.moreno
 *        
 */
class ResourceTable extends AbstractTableGateway
{
    public $table = 'recurso';
    
    public function __construct(Adapter $adapter)
    {
    	$this->adapter = $adapter;
    	$this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
    	$this->initialize();
    }
    
    public function getAllResources()
    {
    	try {
    		$sql = new Sql($this->getAdapter());
    		$select = $sql->select()->from(array(
    				'rs' => $this->table
    		));
    		$select->columns(array(
    				'idRecurso',
    				'nombreRecurso'
    		));
    		$statement = $sql->prepareStatementForSqlObject($select);
    		$resources = $this->resultSetPrototype->initialize($statement->execute())->toArray();
    		return $resources;
    	} catch (\Exception $e) {
    		throw new \Exception($e->getPrevious()->getMessage());
    	}
    }
}

?>