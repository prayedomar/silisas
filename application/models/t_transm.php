<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_transm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_tipos_de_transacciones() {
        $query = "SELECT * FROM t_trans order by nombre_tabla";
        return $this->db->query($query)->result();
    }

}
