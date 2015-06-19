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
class Clientes extends TableGateway
{

    private $dbAdapter;

    public function __construct (Adapter $adapter = null, $databaseSchema = null, 
            ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter = $adapter;
        return parent::__construct('persona', $this->dbAdapter, $databaseSchema, 
                $selectResultPrototype);
    }

    public function listaClientes($empieza,$termina,$filterRules)
    {
        //echo"<pre>"; print_r($filterRules); echo"</pre>";
        
        $sqlTotal = "";
        $sqlBuscar = "";       
        $sqlBuscar .= "SELECT";
        $sqlBuscar .= " persona.idPersona,";
        $sqlBuscar .= " persona.nombreCompleto,";        
        $sqlBuscar .= " persona.fechaDeNacimiento,";
        $sqlBuscar .= " persona.calle,";
        $sqlBuscar .= " persona.numeroInterior,";
        $sqlBuscar .= " persona.numeroExterior,";
        $sqlBuscar .= " persona.idCodigoPostal,";        
        $sqlBuscar .= " persona.telefonoMovil,";
        $sqlBuscar .= " codigospostales.codigo,";
        $sqlBuscar .= " codigospostales.asentamiento,";
        $sqlBuscar .= " codigospostales.tipo,";
        $sqlBuscar .= " codigospostales.municipio,";
        $sqlBuscar .= " codigospostales.ciudad,";
        $sqlBuscar .= " codigospostales.estado";
        $sqlBuscar .= " FROM";
        $sqlBuscar .= " persona ,";
        $sqlBuscar .= " codigospostales";
        $sqlBuscar .= " WHERE persona.idCodigoPostal = codigospostales.idCodigoPostal";        
        
        $sqlTotal .= "SELECT COUNT(*) AS total";
        $sqlTotal .= " FROM";
        $sqlTotal .= " persona ,";
        $sqlTotal .= " codigospostales";
        $sqlTotal .= " WHERE persona.idCodigoPostal = codigospostales.idCodigoPostal";
        if (count($filterRules) == 0) {
            $sqlBuscar .= "";
            $sqlTotal .= "";
        }else{
            foreach ($filterRules as $value) {
            	$sqlBuscar .= " AND nombreCompleto LIKE '{$value['value']}%'";
            	$sqlTotal .= " AND nombreCompleto LIKE '{$value['value']}%'";
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
    
    public function guardaClientes($datos)
    {
        $resultado = array();        
        
        try {
            $sql = new Sql($this->dbAdapter);
            $insert = $sql->insert('persona');
            $newData = array(
            		'nombreCompleto' => $datos['nombreCompleto'],
            		'fechaDeNacimiento' => $datos['fechaDeNacimiento'],
            		'calle' => $datos['calle'],
            		'numeroInterior' => $datos['numeroInterior'],
            		'numeroExterior' => $datos['numeroExterior'],
            		'idCodigoPostal' => $datos['idCodigoPostal'],
            		'telefonoMovil' => $datos['telefonoMovil']
            );
            $insert->values($newData);
            $statement = $sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();
            $resultado = array('success'=>1);
            
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
            $update->table('persona');
            $formaArreglo = array(
                    'nombreCompleto' => $datos['nombreCompleto'],
                    'fechaDeNacimiento' => $datos['fechaDeNacimiento'],
                    'calle' => $datos['calle'],
                    'numeroInterior' => $datos['numeroInterior'],
                    'numeroExterior' => $datos['numeroExterior'],
                    'idCodigoPostal' => $datos['idCodigoPostal'],
                    'telefonoMovil' => $datos['telefonoMovil']
            );
            $update->set($formaArreglo);
            $update->where(array("idPersona"=>$datos['idPersona']));
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
    
    public function removeCliente($idCliente){
        $resultado = array();
        try {
        	$sql = new Sql($this->dbAdapter);
        	$elimina = $sql->delete('persona')->where("idPersona = $idCliente");      	
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
    

   
    
}

?>