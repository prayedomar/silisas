<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_deptom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function t_depto_id($id_depto) {
        $SqlInfo = "SELECT * FROM t_depto where (id='" . $id_depto . "')";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }    

    public function listar_todas_los_deptos() {
        $query = "SELECT * FROM t_depto order by tipo";
        return $this->db->query($query)->result();
    }

}
