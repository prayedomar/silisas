<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_sancionm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_tiopos_de_sancion() {
        $query = "SELECT * FROM t_sancion order by tipo";
        return $this->db->query($query)->result();
    }

}
