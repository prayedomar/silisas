<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Ejercicio_ensenanzam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function ejercicio_reporte_ensenanza($id_reporte_alumno) {
        $query = "SELECT t_h.tipo habilidad, t_e.tipo ejercicio 
                  FROM ejercicio_ensenanza e_e 
                  JOIN t_habilidad_ensenanza t_h ON (t_h.id = e_e.t_habilidad) 
                  JOIN t_ejercicio_ensenanza t_e ON (t_e.id = e_e.t_ejercicio) 
                  WHERE (e_e.id_reporte='$id_reporte_alumno')";
        return $this->db->query($query)->result();
    }

}
