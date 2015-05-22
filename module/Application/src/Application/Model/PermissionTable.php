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
class PermissionTable extends AbstractTableGateway
{
    public $table = 'permiso';
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    public function getResourcePermissions($roleId)
    {
        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'p' => $this->table
            ));
            $select->columns(array('idRecurso'));
            
            $select->join(array("r" => "recurso"), "p.idRecurso = r.idRecurso", array("name","route"));
            $select->where(array('p.idRecurso' => $roleId
            ));
            //$select->order(array('menu_order'));
            
            $statement = $sql->prepareStatementForSqlObject($select);
            echo $select->getSqlString();
            $resources = $this->resultSetPrototype->initialize($statement->execute())->toArray();
            return $resources;
        } catch (\Exception $err) {
            throw $err;
        }
    }
}

?>