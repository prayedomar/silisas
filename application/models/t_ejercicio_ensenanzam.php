<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_ejercicio_ensenanzam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function t_ejercicio_t_cursoAlumno_t_habilidad($id_alumno, $dni_alumno, $id_habilidad) {
        $query = "SELECT DISTINCT t_e.* "
                . "FROM t_ejercicio_ensenanza t_e "
                . "JOIN curso_x_habilidad_x_ejercicio c_h_e ON c_h_e.t_ejercicio=t_e.id "
                . "JOIN alumno a "
                . "WHERE ((a.id='" . $id_alumno . "') AND (a.dni='" . $dni_alumno . "') AND (c_h_e.t_curso=a.t_curso) AND (c_h_e.t_habilidad='" . $id_habilidad . "')) order by t_e.tipo";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }

}
