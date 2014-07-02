<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Retefuente_ventasm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function retefuente_vigente_ventas_factura($prefijo_factura, $id_factura) {
        $SqlInfo = "SELECT r_v.* "
                . "FROM retefuente_ventas r_v "
                . "where ((r_v.prefijo_factura='" . $prefijo_factura . "')AND(r_v.id_factura='" . $id_factura . "')AND(r_v.vigente='1'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function retefuente_ventas_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT r_v.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable "
                . "FROM retefuente_ventas r_v "
                . "LEFT JOIN sede s ON (r_v.sede_caja_origen = s.id) "
                . "LEFT JOIN t_caja t_ca ON (r_v.t_caja_origen = t_ca.id) "
                . "JOIN empleado em ON ((r_v.id_responsable = em.id) and (r_v.dni_responsable = em.dni)) "
                . "where ((r_v.prefijo='" . $prefijo . "')AND(r_v.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

}
