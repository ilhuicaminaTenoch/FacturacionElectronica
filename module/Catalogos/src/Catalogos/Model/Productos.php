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
class Productos extends TableGateway
{

     private $dbAdapter;

    public function __construct (Adapter $adapter = null, $databaseSchema = null, 
            ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter = $adapter;
        return parent::__construct('producto', $this->dbAdapter, $databaseSchema, 
                $selectResultPrototype);
    }
    
    public function todosLosProductos($empieza,$termina,$filterRules){
        $resultado = array();        
        try {
            /*$sql = new Sql($this->dbAdapter);
        	$select = $sql->select();
        	$select->from(array('c'=>'categorias'),array('nombreCategoria'));        	
        	$select->join(array('p' => 'producto'),'p.idCategoria=c.idCategoria',array('nombreProducto','precio','stock','idProducto'));
        	$statement = $sql->prepareStatementForSqlObject($select);
        	$result = $statement->execute();*/ 
            $sqlTotal = "";
            $sqlBuscar = "";
            $sqlBuscar .= "SELECT";
            $sqlBuscar .= " producto.idProducto,";
            $sqlBuscar .= " producto.idCategoria,";
            $sqlBuscar .= " producto.nombreProducto,";
            $sqlBuscar .= " producto.precio,";
            $sqlBuscar .= " producto.stock,";
            $sqlBuscar .= " categorias.nombreCategoria";
            $sqlBuscar .= " FROM producto";
            $sqlBuscar .= " INNER JOIN categorias";
            $sqlBuscar .= " ON categorias.idCategoria = producto.idCategoria";            
            
            $sqlTotal .= "SELECT COUNT(*) as total";
            $sqlTotal .= " FROM producto";
            $sqlTotal .= " INNER JOIN categorias";
            $sqlTotal .= " ON categorias.idCategoria = producto.idCategoria";
           
            if (count($filterRules) > 0) {
                foreach ($filterRules as $criteriosBusqueda) {                    
                    switch ($criteriosBusqueda['field']) {
                    	case "nombreCategoria":
                    	   $sqlBuscar .= " AND producto.idCategoria = {$criteriosBusqueda['value']}";
                    	   $sqlTotal .= " AND producto.idCategoria = {$criteriosBusqueda['value']}";
                    	break;
                    	
                    	case "nombreProducto":
                    	    $sqlBuscar .= " AND producto.nombreProducto like '{$criteriosBusqueda['value']}%'";
                    	    $sqlTotal .= " AND producto.nombreProducto like '{$criteriosBusqueda['value']}%'";
                    	break;
                    	
                    	case "precio":
                    	    $sqlBuscar .= " AND producto.precio = {$criteriosBusqueda['value']}";
                    	    $sqlTotal .= " AND producto.precio = {$criteriosBusqueda['value']}";
                        break;
                        
                        case "stock";
                            $sqlBuscar .= " AND producto.stock = {$criteriosBusqueda['value']}";
                            $sqlTotal .= " AND producto.stock = {$criteriosBusqueda['value']}";
                        break;           	
                    }                    
                }            	
            }
            $sqlBuscar .= " ORDER BY nombreProducto";            
            $sqlBuscar .= " LIMIT $empieza,$termina";    
            
            $ejecutaQueryBuscar = $this->dbAdapter->query($sqlBuscar,Adapter::QUERY_MODE_EXECUTE);
            $ejecutaQueryTotal = $this->dbAdapter->query($sqlTotal,Adapter::QUERY_MODE_EXECUTE);
            $arregloBucar = $ejecutaQueryBuscar->toArray();
            $arregloTotal = $ejecutaQueryTotal->toArray();
            $resultado = array("total" => $arregloTotal[0]['total'], "rows" =>$arregloBucar);            
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
    
    /*
     * Lista las categorias y las ordena de manera asendente
     */
    public function listadoCategorias(){
        $resultado = array();
        try {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->columns(array("value"=>"idCategoria","text"=>"nombreCategoria"));
            $select->from(array('c'=>'categorias'));
            $select->order('nombreCategoria ASC');
            $sqlString = $select->getSqlString($this->dbAdapter->getPlatform());           
            $result = $this->dbAdapter->query($sqlString,Adapter::QUERY_MODE_EXECUTE);
            $resultado = $result->toArray();            
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
    
    public function guarda($datos)
    {
    	$resultado = array();    
    	try {
    	    $verificaProducto = $this->verificaProducto($datos['nombreProducto'], $datos['idCategoria']);    	    
    	    if (intval($verificaProducto->total) == 0) {
    	        $sql = new Sql($this->dbAdapter);
    	        $insert = $sql->insert('producto');
    	        $arrayDatos = array(
    	                'idCategoria' => $datos['idCategoria'],
    	                'nombreProducto' => $datos['nombreProducto'],
    	                'precio' => $datos['precio'],
    	                'stock' => $datos['stock']
    	        );
    	        $insert->values($arrayDatos);
    	        $statement = $sql->prepareStatementForSqlObject($insert);
    	        $result = $statement->execute();
    	        $resultado = array('success'=>1);
    	    }else{
    	        $resultado = array("ErrorInterno" => "El producto: <b>".$datos['nombreProducto'].'</b> ya se encuntra registardo en la categoria seleccionada');
    	    }		
    
    	} catch (\Zend\Db\Adapter\Exception\InvalidQueryException $e) {
    		$code = $e->getCode();
    		$msg = $e->getMessage();
    		$file = $e->getFile();
    		$line = $e->getFile();
    		$resultado = array("ErrorInterno" => "$line ERROR #: $code ERROR: $msg");
    	}catch (\Exception $e) {
    		$resultado = array("ErrorInterno" =>$e->getMessage());
    	}
    	
    	$this->dbAdapter->getDriver()->getConnection()->disconnect();
    	return $resultado;
    }
    
    public function updateClientes($datos){
    	$resultado = array();
    	try {
    		$sql = new  Sql($this->dbAdapter);
    		$update = $sql->update();
    		$update->table('producto');    		
    		$update->set($datos);
    		$update->where(array("idProducto"=>$datos['idProducto']));
    		$statement = $sql->prepareStatementForSqlObject($update);
    		$result = $statement->execute();
    		$resultado = array('success'=>1);
    		 
    	} catch (\Zend\Db\Adapter\Exception\InvalidQueryException $e) {
    		$code = $e->getCode();
    		$msg = $e->getMessage();
    		$file = $e->getFile();
    		$line = $e->getFile();
    		$resultado = array("ErrorInterno" => "$line ERROR #: $code ERROR: $msg");
    	}catch (\Exception $e){
    		$resultado = array("ErrorInterno" =>$e->getMessage());
    	}
    	
    	$this->dbAdapter->getDriver()->getConnection()->disconnect();
    	return $resultado;
    }
    
    public function remove($idProducto){
    	$resultado = array();
    	try {
    		$sql = new Sql($this->dbAdapter);
    		$elimina = $sql->delete('producto')->where("idProducto = $idProducto");
    		$statement = $sql->prepareStatementForSqlObject($elimina);
    		$result = $statement->execute();
    		$resultado = array('success' => 1);
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
    
    
    public function verificaProducto($producto,$categoria){
        $resultado = array();
        try {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->columns(array("total"=> new \Zend\Db\Sql\Expression('COUNT(*)')));
            $select->from('producto');
            $select->where(array("nombreProducto" => $producto, "idCategoria"=>$categoria));
            $statement = $sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();            
            $resulSet = new ResultSet();
            $resulSet->initialize($result);           
            //$resultado = $resulSet->toArray();
            $resultado = $resulSet->current();
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
    
    
}

?>