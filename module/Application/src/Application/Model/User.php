<?php
namespace Application\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
/**
 *
 * @author jose.moreno
 *        
 */
class User extends AbstractTableGateway
{
    public $table = 'usuarios';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    
    public function getUsers($where = array(), $columns = array())
    {
        try {
        	$sql = new Sql($this->getAdapter());
        	$select = $sql->select()->from(array('u' => $this->table));
        	             
        	if (count($where) > 0) {
        		$select->where($where);
        	}
        	
        	if (count($columns) > 0) {
        		$select->columns($columns);
        	}
        	
        	$select->join(array('p' => 'perfiles'),'p.idPerfil = u.idPerfil',array('nombre','idPerfil'))
        	       ->join(array('persona' => 'persona'), 'persona.idPersona = u.idPersona',array('nombreCompleto'));       	
            $statement = $sql->prepareStatementForSqlObject($select);            
            $users = $this->resultSetPrototype->initialize($statement->execute())->toArray();
            return $users;        	
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
        
    }
}

?>