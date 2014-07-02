<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Ingresom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function ingreso_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT ing.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable, t_in.tipo "
                . "FROM ingreso ing "
                . "LEFT JOIN sede s ON (ing.sede_caja_destino = s.id) "
                . "LEFT JOIN t_caja t_ca ON (ing.t_caja_destino = t_ca.id) "
                . "JOIN t_ingreso t_in ON (ing.t_ingreso = t_in.id) "
                . "JOIN empleado em ON ((ing.id_responsable = em.id) and (ing.dni_responsable = em.dni)) "
                . "where ((ing.prefijo='" . $prefijo . "')AND(ing.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

}
