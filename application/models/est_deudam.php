<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Est_deudam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_estados_deuda() {
        $query = "SELECT * FROM est_deuda";
        return $this->db->query($query)->result();
    }

}
