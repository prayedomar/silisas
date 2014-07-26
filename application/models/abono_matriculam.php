<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Abono_matriculam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function abono_matricula_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT a_m.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable "
                . "FROM abono_matricula a_m "
                . "LEFT JOIN sede s ON (a_m.sede_caja_destino = s.id) "
                . "LEFT JOIN t_caja t_ca ON (a_m.t_caja_destino = t_ca.id) "
                . "JOIN empleado em ON ((a_m.id_responsable = em.id) and (a_m.dni_responsable = em.dni)) "
                . "where ((a_m.prefijo='" . $prefijo . "')AND(a_m.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }
    
    public function todos_los_abonos() {
        $query = "SELECT a_m.*, s.nombre sede_caja, t_ca.tipo tipo_caja, CONCAT(em.nombre1, ' ', em.apellido1) responsable "
                . "FROM abono_matricula a_m "
                . "LEFT JOIN sede s ON (a_m.sede_caja_destino = s.id) "
                . "LEFT JOIN t_caja t_ca ON (a_m.t_caja_destino = t_ca.id) "
                . "JOIN empleado em ON ((a_m.id_responsable = em.id) and (a_m.dni_responsable = em.dni)) ";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }    

}
