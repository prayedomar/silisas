<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_cajam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_tipos_de_caja() {
        $query = "SELECT * FROM t_caja order by tipo";
        return $this->db->query($query)->result();
    }

}
