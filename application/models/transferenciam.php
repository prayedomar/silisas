<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Transferenciam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
 
    public function transferencia_pdte_responsable_rapida() {
        $query = "SELECT tr.* "
                . "FROM transferencia tr "
                . "LEFT JOIN cuenta cu ON tr.cuenta_destino = cu.id "
                . "LEFT JOIN caja ca ON ((tr.sede_caja_destino=ca.sede) AND (tr.t_caja_destino=ca.t_caja)) "
                . "where ((((ca.id_encargado='{$_SESSION["idResponsable"]}')  AND (ca.dni_encargado='{$_SESSION["dniResponsable"]}') AND (ca.vigente=1)) OR ((cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='{$_SESSION["idResponsable"]}') AND (dni_encargado='{$_SESSION["dniResponsable"]}') AND (permiso_consultar=1)))))) AND (tr.est_traslado=2)) ORDER BY tr.fecha_trans";
        return $this->db->query($query)->result();
    }   
    
    public function cantidad_transferencia_pdte_responsable_rapida() {
        $query = "SELECT count(*) cantidad "
                . "FROM transferencia tr "
                . "LEFT JOIN cuenta cu ON tr.cuenta_destino = cu.id "
                . "LEFT JOIN caja ca ON ((tr.sede_caja_destino=ca.sede) AND (tr.t_caja_destino=ca.t_caja)) "
                . "where ((((ca.id_encargado='{$_SESSION["idResponsable"]}')  AND (ca.dni_encargado='{$_SESSION["dniResponsable"]}') AND (ca.vigente=1)) OR ((cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='{$_SESSION["idResponsable"]}') AND (dni_encargado='{$_SESSION["dniResponsable"]}') AND (permiso_consultar=1)))))) AND (tr.est_traslado=2))";
        return $this->db->query($query)->result();
    } 

}
