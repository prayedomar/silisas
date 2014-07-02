<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Recibo_cajam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function recibo_caja_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT r_c.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable "
                . "FROM recibo_caja r_c "
                . "LEFT JOIN sede s ON (r_c.sede_caja_destino = s.id) "
                . "LEFT JOIN t_caja t_ca ON (r_c.t_caja_destino = t_ca.id) "
                . "JOIN empleado em ON ((r_c.id_responsable = em.id) and (r_c.dni_responsable = em.dni)) "
                . "where ((r_c.prefijo='" . $prefijo . "')AND(r_c.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

}
