<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Abono_adelantom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function abono_adelanto_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT a_a.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable, CONCAT(eb.nombre1, ' ', eb.apellido1) beneficiario, ad.total total_adelanto "
                . "FROM abono_adelanto a_a "
                . "LEFT JOIN sede s ON (a_a.sede_caja_destino = s.id) "
                . "LEFT JOIN t_caja t_ca ON (a_a.t_caja_destino = t_ca.id) "
                . "JOIN adelanto ad ON ((a_a.id_adelanto = ad.id)AND(a_a.prefijo_adelanto = ad.prefijo)) "
                . "JOIN empleado eb ON ((ad.id_empleado = eb.id) and (ad.dni_empleado = eb.dni)) "
                . "JOIN empleado em ON ((a_a.id_responsable = em.id) and (a_a.dni_responsable = em.dni)) "
                . "where ((a_a.prefijo='" . $prefijo . "')AND(a_a.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function abono_adelanto_vigente_id_adelanto($prefijo_adelanto, $id_adelanto) {
        $query = "SELECT a_a.* "
                . "FROM abono_adelanto a_a "
                . "where ((a_a.prefijo_adelanto='" . $prefijo_adelanto . "')AND(a_a.id_adelanto='" . $id_adelanto . "')AND(a_a.vigente='1'))";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }

}
