<?php
namespace Catalogos\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Predicate\NotIn;

/**
 *
 * @author jose.moreno
 *        
 */
class Usuarios extends TableGateway
{
    private $dbAdapter;
    
    /*
     * Contructor para la conexion a la base de datos
     */
    public function __construct (Adapter $adapter = null, $databaseSchema = null,
    		ResultSet $selectResultPrototype = null)
    {
    	$this->dbAdapter = $adapter;
    	return parent::__construct('producto', $this->dbAdapter, $databaseSchema,
    			$selectResultPrototype);
    }
    
    public function obtenerUsuarios($limit, $offset, $filterRules)
    {
        $sql = new Sql($this->dbAdapter);
        $resulSet = new ResultSet();
       
        
        $resultado = array();        
        try {
            $select = $sql->select();
            $select->columns(array("idUserRol", "idUsuario", "idPerfil"));
            $select->from("usuarioperfil");
            $select->join("usuarios", "usuarios.idUsuario = usuarioperfil.idUsuario", array("email"));
            $select->join("perfiles", "perfiles.idPerfil = usuarioperfil.idPerfil", array("perfil"=>"nombre"));            
            $select->join("persona", "persona.idPersona = usuarios.idPersona", array("idPersona","nombreCompleto"));

            $selectCount = $sql->select();
            $selectCount->columns(array("total"=> new \Zend\Db\Sql\Expression('COUNT(*)')));
            $selectCount->from("usuarioperfil");
            $selectCount->join("usuarios", "usuarios.idUsuario = usuarioperfil.idUsuario");
            $selectCount->join("perfiles", "perfiles.idPerfil = usuarioperfil.idPerfil");
            $selectCount->join("persona", "persona.idPersona = usuarios.idPersona");
            
            
            if (count($filterRules) != 0) {
                foreach ($filterRules as $campo) {                    
                    switch ($campo['field']) {
                        case "nombreCompleto":
                            $select->where->like("persona.nombreCompleto", "{$campo['value']}%");
                            $selectCount->where->like("persona.nombreCompleto", "{$campo['value']}%");
                            break;
                             
                        case "email":
                            $select->where->like("usuarios.email", "{$campo['value']}%");
                            $selectCount->where->like("usuarios.email", "{$campo['value']}%");
                            break;
                    
                        case "nombrePerfil":
                            $select->where->equalTo("perfiles.idPerfil", $campo['value']);
                            $selectCount->where->equalTo("perfiles.idPerfil", $campo['value']);
                    }                       
                    
                }
            }
            $select->limit($limit);
            $select->offset($offset);
            $select->order("persona.nombreCompleto ASC");
            
            $statement = $sql->prepareStatementForSqlObject($select)->execute();
            $datos = $resulSet->initialize($statement)->toArray();
            
            $statementCount = $sql->prepareStatementForSqlObject($selectCount)->execute();
            $resultadoCount = $resulSet->initialize($statementCount)->toArray();

            $resultado = array("total" => $resultadoCount[0]['total'], "rows" => $datos);
           
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getFile();
            $resultado = array("ErrorInterno" => "$line ERROR #: $code ERROR: $msg");
        }
        $this->dbAdapter->getDriver()->getConnection()->disconnect();
        return $resultado;
        
    }

    public function guardar($datos){
        $resultado = array();
        try {
            $sql = new Sql($this->dbAdapter);
            $insert = $sql->insert('usuarios');
            $arrayDatos = array(
                    'email' => $datos['email'],
                    'contrasena' => sha1($datos['contrasena']),                   
                    'idPersona' => $datos['idPersona']      
            );
    
            $insert->values($arrayDatos);                        
            $statement = $sql->prepareStatementForSqlObject($insert);
            $statement->execute();
            $resultado = array("success" => true);

        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getFile();
            $resultado = array("isError" => true, "msg" => "$line ERROR #: $code ERROR: $msg");
        }
        $this->dbAdapter->getDriver()->getConnection()->disconnect();
        return $resultado;
    }
    
    public function actualizar($datos){
        $resultado = array();
        try {
            $sql = new Sql($this->dbAdapter);
            $update = $sql->update();
            $update->table('usuarios');
            $arrayDatos = array(
                    'email' => $datos['email'],
                    'contrasena' => $datos['contrasena'],
                    'idPerfil' => $datos['idPerfil'],
                    'idPersona' => $datos['idPersona']      
            );
            $update->set($arrayDatos);
            $update->where(array('idUsuario' => $datos['idUsuario']));
            $statement = $sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
            $resultado = array("success" => true);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getFile();
            $resultado = array("ErrorInterno" => "$line ERROR #: $code ERROR: $msg");
        }
        $this->dbAdapter->getDriver()->getConnection()->disconnect();
    }
    
    public function elimina($idUsuario){
        $resultado = array();
        try {
            $sql = new Sql($this->dbAdapter);
            $elimina = $sql->delete('usuarios')->where("idUsuario = $idUsuario");
            $statement = $sql->prepareStatementForSqlObject($elimina);
            $resul = $statement->execute();
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getFile();
            $resultado = array("isError" => true, "msg" => "$line ERROR #: $code ERROR: $msg");
        }
        $this->dbAdapter->getDriver()->getConnection()->disconnect();
        return $resultado;
    }
    
    public function comboGrid($q,$limit,$offset) {
        $resultado = array();
        $resulSet = new  ResultSet();
        $sql = new Sql($this->dbAdapter);
        try {
            $mainSelect = $sql->select();
            $mainSelect->from("persona");
            $mainSelect->columns(array("idPersona","nombreCompleto"));
            
            $select = $sql->select();
            $select->columns(array("idPersona"));
            $select->from("usuarios");
               
            $mainSelect->where(new NotIn("persona.idPersona",$select));
            $mainSelect->where->like("persona.nombreCompleto", "$q%");
            $mainSelect->limit($limit);
            $mainSelect->offset($offset);
            $mainSelect->order("persona.nombreCompleto ASC");
            
            
            $statement = $sql->prepareStatementForSqlObject($mainSelect)->execute();
            $resultado = $resulSet->initialize($statement)->toArray();
            
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getFile();
            $resultado = array("isError" => true, "msg" => "$line ERROR #: $code ERROR: $msg");
        }
        
        $this->dbAdapter->getDriver()->getConnection()->disconnect();
        return $resultado;
    }
    
   
   
}

?>