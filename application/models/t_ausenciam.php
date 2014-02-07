<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_ausenciam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_tiopos_de_ausencia() {
        $query = "SELECT * FROM t_ausencia order by tipo";
        return $this->db->query($query)->result();
    }

}
