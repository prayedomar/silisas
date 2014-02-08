<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_cuentam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_tipos_cuentas() {
        $query = "SELECT * FROM t_cuenta order by tipo";
        return $this->db->query($query)->result();
    }


}
