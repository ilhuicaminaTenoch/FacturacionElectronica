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
        	$select = $sql->select()->from('usuarioperfil');        	
        	             
        	if (count($where) > 0) {
        		$select->where($where);
        	}
        	
        	if (count($columns) > 0) {
        		$select->columns($columns);
        	}
        	
        	$select->join(array('usuarios' => 'usuarios'),'usuarios.idUsuario = usuarioperfil.idUsuario',array('email',))
        	       ->join(array('persona' => 'persona'), 'persona.idPersona = usuarios.idPersona',array('nombreCompleto'))
        	       ->join(array('perfiles' => 'perfiles'), 'perfiles.idPerfil = usuarioperfil.idPerfil', array('nombre')); 
        	      	
            $statement = $sql->prepareStatementForSqlObject($select);            
            $users = $this->resultSetPrototype->initialize($statement->execute())->toArray();
            return $users;        	
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
        
    }
}

?>