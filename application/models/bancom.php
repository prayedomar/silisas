<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Bancom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_bancos() {
        $query = "SELECT * FROM banco order by nombre";
        return $this->db->query($query)->result();
    }


}
