<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_habilidad_ensenanzam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function t_habilidad_t_curso_alumno($id, $dni) {
        $query = "SELECT DISTINCT t_h.*, a.t_curso "
                . "FROM t_habilidad_ensenanza t_h "
                . "JOIN curso_x_habilidad_x_ejercicio c_h_e ON c_h_e.t_habilidad=t_h.id "
                . "JOIN alumno a "
                . "WHERE ((a.id='" . $id . "') AND (a.dni='" . $dni . "') AND (c_h_e.t_curso=a.t_curso)) order by t_h.tipo";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }
    

}
