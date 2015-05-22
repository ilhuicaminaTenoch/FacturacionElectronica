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
class Role extends AbstractTableGateway
{
    public $table = 'perfiles';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    
    public function getUserRoles($where = array(), $columns = array())
    {
        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array('perfiles' => $this->table));
            $select->columns(array('idPerfil','nombre'));
            
            if (count($columns) > 0) {
                    $select->columns($columns);
            }
            
            $statement = $sql->prepareStatementForSqlObject($select);
            $roles = $this->resultSetPrototype->initialize($statement->execute())->toArray();
        	return $roles;
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage);
        }
    }
}

?>