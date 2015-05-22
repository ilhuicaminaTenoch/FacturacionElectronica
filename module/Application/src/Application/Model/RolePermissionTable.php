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
class RolePermissionTable extends AbstractTableGateway
{
    public $table = 'perfilPermiso';
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    public function getRolePermissions()
    {
        $sql = new Sql($this->getAdapter());
        
        $select = $sql->select()
            ->from(array('t1' => 'perfiles'))->columns(array('nombre'))
            ->join(array('t2' => $this->table), 't1.idPerfil = t2.idPerfil', array(), 'left')
            ->join(array('t3' => 'permiso'), 't3.idPermiso = t2.idPermiso', array('nombrePermiso'), 'left')
            ->join(array('t4' => 'recurso'), 't4.idRecurso = t3.idRecurso', array('nombreRecurso'), 'left')
            ->where('t3.nombrePermiso is not null and t4.nombreRecurso is not null')
            ->order('t1.idPerfil');
        
        
        $statement = $sql->prepareStatementForSqlObject($select);
        //echo $select->getSqlString();
        $result = $this->resultSetPrototype->initialize($statement->execute())->toArray();
        return $result;
    }
}

?>