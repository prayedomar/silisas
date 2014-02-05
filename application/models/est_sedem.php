<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Est_sedem extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_estatus() {
        $query = "SELECT * FROM est_sede";
        return $this->db->query($query)->result();
    }

}
