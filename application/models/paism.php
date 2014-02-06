<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Paism extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_paises() {
        $query = "SELECT * FROM pais";
        return $this->db->query($query)->result();
    }

}
