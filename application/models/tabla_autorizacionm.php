<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Tabla_autorizacionm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_las_tablas() {
        $query = "SELECT * FROM tabla_autorizacion order by nombre";
        return $this->db->query($query)->result();
    }

    public function tabla_autorizacion_perfil($t_perfil) {
        $query = "SELECT t_a.* 
                  FROM tabla_autorizacion t_a 
                  JOIN t_perfil_x_tabla_autorizacion p_x_t ON (p_x_t.tabla_autorizacion = t_a.id) 
                  WHERE (p_x_t.t_perfil='$t_perfil') ORDER BY t_a.nombre";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }

}
