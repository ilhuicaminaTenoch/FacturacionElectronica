<?php
namespace Application\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
/**
 *
 * @author jose.moreno
 *        
 */
class UserRole extends AbstractTableGateway
{
    public $table = 'usuarioperfil';
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    
    public function getUserRoles($where = array(), $columns = array(), $orderBy = '', $paging = false)
    {
        try {
        	$sql = new Sql($this->getAdapter());
        	$select = $sql->select()->from(array(
        	        'sa' => $this->table
        	));
        	if(count($where) > 0){
        	    $select->where($where);
        	}
        	$select->where($where);
        	
        	if (count($columns) > 0) {
        		$select->columns($columns);
        	}
        	
        	if (! empty($orderBy)) {
        		$select->order($orderBy);
        	}
        	
        	if ($paging) {
        		$dbAdapter = new DbSelect($select, $this->getAdapter());
        		$paginator = new Paginator($dbAdapter);
        		return $paginator;
        	}else{
        	    $statement = $sql->prepareStatementForSqlObject($select);
        	    $clients = $this->resultSetPrototype->initialize($statement->execute())->toArray();
        	    return $clients;
        	}
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }
}

?>