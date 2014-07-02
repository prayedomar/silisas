<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Nota_creditom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function nota_credito_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT n_c.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable "
                . "FROM nota_credito n_c "
                . "LEFT JOIN sede s ON (n_c.sede_caja_origen = s.id) "
                . "LEFT JOIN t_caja t_ca ON (n_c.t_caja_origen = t_ca.id) "
                . "JOIN empleado em ON ((n_c.id_responsable = em.id) and (n_c.dni_responsable = em.dni)) "
                . "where ((n_c.prefijo='" . $prefijo . "')AND(n_c.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

}
