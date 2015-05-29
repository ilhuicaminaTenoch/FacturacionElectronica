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
        $sqlBuscar .= " codigospostales.tipo";
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
            $resultado = array("ErrorInternio" => "$line ERROR #: $code ERROR: $msg");             
        }catch (\Exception $e) {
            $resultado = array("ErrorInternio" =>$e->getMessage());
        }
        return $resultado;
            

    }
    

   
    
}

?>