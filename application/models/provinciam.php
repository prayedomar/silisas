<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Provinciam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listarProvinciasPorPais($idPais) {
        $query = "SELECT * FROM provincia WHERE pais='$idPais' ORDER BY nombre";
        return $this->db->query($query)->result();
    }

}
