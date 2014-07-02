<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Egresom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function egreso_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT eg.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable, t_e.tipo "
                . "FROM egreso eg "
                . "LEFT JOIN sede s ON (eg.sede_caja_origen = s.id) "
                . "LEFT JOIN t_caja t_ca ON (eg.t_caja_origen = t_ca.id) "
                . "JOIN t_egreso t_e ON (eg.t_egreso = t_e.id) "
                . "JOIN empleado em ON ((eg.id_responsable = em.id) and (eg.dni_responsable = em.dni)) "
                . "where ((eg.prefijo='" . $prefijo . "')AND(eg.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

}
