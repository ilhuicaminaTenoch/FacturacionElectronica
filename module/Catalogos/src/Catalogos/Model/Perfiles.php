<?php
namespace Catalogos\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;


/**
 *
 * @author jose.moreno
 *        
 */
class Perfiles extends TableGateway
{
    private $dbAdapter;

    public function __construct (Adapter $adapter = null, $databaseSchema = null, 
            ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter = $adapter;
        return parent::__construct('perfiles', $this->dbAdapter, $databaseSchema, 
                $selectResultPrototype);
    }
    
    public function listado($limit,$offset,$filterRules){       
        $sql = new Sql($this->dbAdapter);
        $resulSet = new ResultSet();
        
        
        $select = $sql->select();
        $select->from(array('P'=>'perfiles'));
        $select->limit($limit);
        $select->offset($offset);               
        
        $selectCount = $sql->select();
        $selectCount->from(array('P'=>'perfiles'));
        $selectCount->columns(array('total' => new \Zend\Db\Sql\Expression('COUNT(*)')));        
        
        if (count($filterRules) != 0) {
            foreach ($filterRules as $value) {                
                $select->where->like("nombre","%{$value['value']}%"); 
                $selectCount->where->like("nombre","%{$value['value']}%");                          
            }        	
        }
        
        
        //echo $sql->getSqlStringForSqlObject($selectCount);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();        
        $datos = $resulSet->initialize($result)->toArray();
        
        $statementCuenta = $sql->prepareStatementForSqlObject($selectCount);
        $resultCount = $statementCuenta->execute();
        $datoscCuenta = $resulSet->initialize($resultCount)->toArray();
        
        

        $une =array('total' =>$datoscCuenta[0]['total'], 'rows' =>$datos);
        
        $this->dbAdapter->getDriver()->getConnection()->disconnect();
        return $une;
    }
    
    public function actualizar($datos){
        $resultado = array();
        try {
        	$sql = new Sql($this->dbAdapter);
        	$update = $sql->update();
        	$update->table('perfiles');
        	$arrayDatos = array(        	        
        	        'nombre' => $datos['nombre']
        	);
        	$update->set($arrayDatos);
        	$update->where(array('idPerfil' => $datos['idPerfil']));
        	$statement = $sql->prepareStatementForSqlObject($update);
        	$result = $statement->execute();
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getFile();
            $resultado = array("ErrorInterno" => "$line ERROR #: $code ERROR: $msg");
        }

        $this->dbAdapter->getDriver()->getConnection()->disconnect();
    }
    
    public function guardar($datos){
    	$resultado = array();
    	try {
    		$sql = new Sql($this->dbAdapter);
    		$insert = $sql->insert('perfiles');    		
    		$arrayDatos = array(
    				'nombre' => $datos['nombre']
    		);   		
    		
    		$insert->values($arrayDatos);
    		
    		$statement = $sql->prepareStatementForSqlObject($insert);
    		$resultado = $statement->execute();
    	} catch (\Exception $e) {
    		$code = $e->getCode();
    		$msg = $e->getMessage();
    		$file = $e->getFile();
    		$line = $e->getFile();
    		$resultado = array("isError" => true, "msg" => "$line ERROR #: $code ERROR: $msg");    		
    	}
    	
    	$this->dbAdapter->getDriver()->getConnection()->disconnect();
    
    }
    
    public function elimina($idPerfil){
        $resultado = array();
        try {
            $sql = new Sql($this->dbAdapter);
            $elimina = $sql->delete('perfiles')->where("idPerfil = $idPerfil");
            $statement = $sql->prepareStatementForSqlObject($elimina);
            $resul = $statement->execute();  
            $resultado = array('success'=>true);
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
    
    public function listaCombo(){
        $sql = new Sql($this->dbAdapter);
        $resulSet = new ResultSet();
        
        $select = $sql->select();
        $select->columns(array("value"=>"idPerfil","text"=>"nombre"));
        $select->from(array('P'=>'perfiles'));
        $select->order('nombre ASC');
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $datos = $resulSet->initialize($result)->toArray();
        
        $this->dbAdapter->getDriver()->getConnection()->disconnect();
        return $datos;
        
    }
}

?>