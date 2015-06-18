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
class Categorias extends TableGateway
{
    private $dbAdapter;

    public function __construct (Adapter $adapter = null, $databaseSchema = null, 
            ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter = $adapter;
        return parent::__construct('categorias', $this->dbAdapter, $databaseSchema, 
                $selectResultPrototype);
    }
    
    public function listado($empieza,$termina,$filterRules){
        
        $sqlTotal = "";
        $sqlBuscar = "";
        $sqlBuscar .= "SELECT";
        $sqlBuscar .= " categorias.idCategoria,";
        $sqlBuscar .= " categorias.nombreCategoria";        
        $sqlBuscar .= " FROM";       
        $sqlBuscar .= " categorias";        
        
        $sqlTotal .= "SELECT COUNT(*) AS total";
        $sqlTotal .= " FROM";
        $sqlTotal .= " categorias";
        
        if (count($filterRules) == 0) {
        	$sqlBuscar .= "";
        	$sqlTotal .= "";
        }else{
        	foreach ($filterRules as $value) {
        		$sqlBuscar .= " WHERE nombreCategoria LIKE '{$value['value']}%'";
        		$sqlTotal .= " WHERE nombreCategoria LIKE '{$value['value']}%'";
        	}
        }

        $sqlBuscar .= " LIMIT $empieza,$termina";
        
        
        $ejecutaQueryBuscar = $this->dbAdapter->query($sqlBuscar,Adapter::QUERY_MODE_EXECUTE);
        $ejecutaQueryTotal = $this->dbAdapter->query($sqlTotal,Adapter::QUERY_MODE_EXECUTE);
        
        $arregloBucar = $ejecutaQueryBuscar->toArray();
        $arregloTotal = $ejecutaQueryTotal->toArray();
        $arreglo = array("total" => $arregloTotal[0]['total'], "rows" =>$arregloBucar);
        
        $this->dbAdapter->getDriver()->getConnection()->disconnect();
        return $arreglo;      
    }
    
    public function actualizar($datos){
        $resultado = array();
        try {
        	$sql = new Sql($this->dbAdapter);
        	$update = $sql->update();
        	$update->table('categorias');
        	$arrayDatos = array(        	        
        	        'nombreCategoria' => $datos['nombreCategoria']
        	);
        	$update->set($arrayDatos);
        	$update->where(array('idCategoria' => $datos['idCategoria']));
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
    		$insert = $sql->insert('categorias');    		
    		$arrayDatos = array(
    				'nombreCategoria' => $datos['nombreCategoria']
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
        return $resultado;
    }
    
    public function elimina($idCategoria){
        $resultado = array();
        try {
            $sql = new Sql($this->dbAdapter);
            $elimina = $sql->delete('categorias')->where("idCategoria = $idCategoria");
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
    
    
}

?>