<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Adelantom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function adelanto_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT ad.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable, CONCAT(eb.nombre1, ' ', eb.apellido1) beneficiario "
                . "FROM adelanto ad "
                . "LEFT JOIN sede s ON (ad.sede_caja_origen = s.id) "
                . "LEFT JOIN t_caja t_ca ON (ad.t_caja_origen = t_ca.id) "
                . "JOIN empleado eb ON ((ad.id_empleado = eb.id) and (ad.dni_empleado = eb.dni)) "
                . "JOIN empleado em ON ((ad.id_responsable = em.id) and (ad.dni_responsable = em.dni)) "
                . "where ((ad.prefijo='" . $prefijo . "')AND(ad.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

}
