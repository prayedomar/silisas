<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Comisiones_matriculam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function comision_directa($plan, $cargo) {
        $SqlInfo = "SELECT * "
                . "FROM comision_matricula "
                . "WHERE ((plan='" . $plan . "') AND (cargo='" . $cargo . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function comision_escala($plan, $cargo) {
        $SqlInfo = "SELECT * "
                . "FROM comision_escala "
                . "WHERE ((plan='" . $plan . "') AND (cargo='" . $cargo . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }
    
    public function insertar_comision_directa($plan, $cargo, $comision) {
        $query = "INSERT INTO comision_matricula (plan, cargo, comision) VALUES('" . $plan . "', '" . $cargo . "', '" . $comision . "') ON DUPLICATE KEY UPDATE plan='" . $plan . "', cargo='" . $cargo . "', comision='" . $comision . "'";
        $this->db->query($query);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }    
    
    public function insertar_comision_escala($plan, $cargo, $comision, $comision_ecargado) {
        $query = "INSERT INTO comision_escala (plan, cargo, comision, comision_escala_encargado) VALUES('" . $plan . "', '" . $cargo . "', '" . $comision . "', '" . $comision_ecargado . "') ON DUPLICATE KEY UPDATE plan='" . $plan . "', cargo='" . $cargo . "', comision='" . $comision . "', comision_escala_encargado='" . $comision_ecargado . "'";
        $this->db->query($query);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }
    
}
