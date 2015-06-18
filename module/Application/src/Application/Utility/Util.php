<?php
namespace Application\Utility;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

/**
 *
 * @author jose.moreno
 *        
 */
class Util extends TableGateway
{

    private $dbAdapter;

    public function __construct (Adapter $adapter = null, $databaseSchema = null, 
            ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter = $adapter;
        return parent::__construct('persona', $this->dbAdapter, $databaseSchema, 
                $selectResultPrototype);
    }

    public function fechaLarga ($fecha)
    {
        $dias = array(
                "Domingo",
                "Lunes",
                "Martes",
                "Miercoles",
                "Jueves",
                "Viernes",
                "Sábado"
        );
        $meses = array(
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
        );
        
        return $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] .
                 " de " . date('Y');
    }

    public function consultaCodigoPostal ($codigoPostal)
    {
        $sql = "SELECT
        codigospostales.idCodigoPostal,
        codigospostales.codigo,
        codigospostales.asentamiento,
        codigospostales.municipio,
        codigospostales.ciudad,
        codigospostales.estado,
        codigospostales.tipo
        FROM
        codigospostales
        WHERE codigospostales.codigo = '$codigoPostal' ";
        
        $ejecutaQuery = $this->dbAdapter->query($sql,Adapter::QUERY_MODE_EXECUTE);
        $datos = $ejecutaQuery->toArray();
        //$total = count($datos);
        return  $datos;
    }
    
    public function filterCombo($datos){
        $resultado = array();
        if (count($datos) > 0) {
        	array_unshift($datos, array("value"=>"0","text"=>"Todos"));
        	return $datos;
        }
    }
}

?>