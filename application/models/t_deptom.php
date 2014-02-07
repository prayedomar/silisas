<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_deptom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_deptos() {
        $query = "SELECT * FROM t_depto order by tipo";
        return $this->db->query($query)->result();
    }

}
