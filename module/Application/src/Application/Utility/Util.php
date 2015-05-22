<?php
namespace Application\Utility;

/**
 *
 * @author jose.moreno
 *        
 */
class Util
{
    public function fechaLarga($fecha) {
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        return $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " de ".date('Y') ;
    }
}

?>