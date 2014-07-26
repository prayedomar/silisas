<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Nominam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function nominas_vigentes() {
        $SqlInfo = "SELECT * FROM nomina where vigente=1";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }
    
    public function todas_las_nominas() {
        $SqlInfo = "SELECT * FROM nomina";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }    

    public function nomina_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT no.*, s.nombre sede_caja, t_ca.tipo tipo_caja, t_d.tipo departamento, t_c.cargo_masculino cargo_masculino, t_c.cargo_femenino cargo_femenino, t_p.tipo tipo_periodicidad, CONCAT(em.nombre1, ' ', em.nombre2, ' ', em.apellido1, ' ', em.apellido2) empleado, em.genero genero_empleado, CONCAT(re.nombre1, ' ', re.apellido1) responsable "
                . "FROM nomina no "
                . "JOIN t_depto t_d ON (no.depto = t_d.id) "
                . "JOIN t_cargo t_c ON (no.cargo = t_c.id) "
                . "JOIN t_periodicidad_nomina t_p ON (no.t_periodicidad = t_p.id) "
                . "LEFT JOIN sede s ON (no.sede_caja_origen = s.id) "
                . "LEFT JOIN t_caja t_ca ON (no.t_caja_origen = t_ca.id) "
                . "JOIN empleado em ON ((no.id_empleado = em.id) and (no.dni_empleado = em.dni)) "
                . "JOIN empleado re ON ((no.id_responsable = re.id) and (no.dni_responsable = re.dni)) "
                . "where ((no.prefijo='" . $prefijo . "')AND(no.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function concepto_nomina_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT c_n.*, t_c.tipo, t_c.debito_credito "
                . "FROM concepto_nomina c_n "
                . "JOIN t_concepto_nomina t_c ON (c_n.t_concepto_nomina = t_c.id) "
                . "where ((c_n.prefijo_nomina='" . $prefijo . "')AND(c_n.id_nomina='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function concepto_nomina_group_matricula($prefijo, $id) {
        $SqlInfo = "SELECT c_n.*, t_c.tipo, t_c.debito_credito, sum(valor_unitario) total "
                . "FROM concepto_nomina c_n "
                . "JOIN t_concepto_nomina t_c ON (c_n.t_concepto_nomina = t_c.id) "
                . "where ((c_n.prefijo_nomina='" . $prefijo . "')AND(c_n.id_nomina='" . $id . "')) group by c_n.detalle order by c_n.id";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

}
