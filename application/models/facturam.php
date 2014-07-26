<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Facturam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function factura_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT f.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable "
                . "FROM factura f "
                . "LEFT JOIN sede s ON (f.sede_caja_destino = s.id) "
                . "LEFT JOIN t_caja t_ca ON (f.t_caja_destino = t_ca.id) "
                . "JOIN empleado em ON ((f.id_responsable = em.id) and (f.dni_responsable = em.dni)) "
                . "where ((f.prefijo='" . $prefijo . "')AND(f.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function todas_las_facturas() {
        $query = "SELECT f.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable "
                . "FROM factura f "
                . "LEFT JOIN sede s ON (f.sede_caja_destino = s.id) "
                . "LEFT JOIN t_caja t_ca ON (f.t_caja_destino = t_ca.id) "
                . "JOIN empleado em ON ((f.id_responsable = em.id) and (f.dni_responsable = em.dni)) ";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }

}
