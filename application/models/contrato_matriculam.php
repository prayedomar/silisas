<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Contrato_matriculam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function contrato_matricula_id($id) {
        $SqlInfo = "SELECT c_m.*, se.nombre sede, e_c.estado estado_contrato, CONCAT(re.nombre1, ' ', re.apellido1) responsable "
                . "FROM contrato_matricula c_m "
                . "JOIN est_contrato_matricula e_c ON c_m.estado=e_c.id "
                . "JOIN sede se ON c_m.sede_actual=se.id "
                . "JOIN empleado re ON ((c_m.id_responsable = re.id) and (c_m.dni_responsable = re.dni)) "
                . "where (c_m.id='" . $id . "')";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }
}