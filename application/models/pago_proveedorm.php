<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Pago_proveedorm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function pago_proveedor_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT p_p.*, s.nombre sede_caja, t_ca.tipo tipo_caja, pr.razon_social, CONCAT(em.nombre1, ' ', em.apellido1) responsable "
                . "FROM pago_proveedor p_p "
                . "LEFT JOIN sede s ON (p_p.sede_caja_origen = s.id) "
                . "LEFT JOIN t_caja t_ca ON (p_p.t_caja_origen = t_ca.id) "
                . "JOIN proveedor pr ON ((p_p.id_proveedor = pr.id) and (p_p.dni_proveedor = pr.dni)) "
                . "JOIN empleado em ON ((p_p.id_responsable = em.id) and (p_p.dni_responsable = em.dni)) "
                . "where ((p_p.prefijo='" . $prefijo . "')AND(p_p.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

}
