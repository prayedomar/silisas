<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Ciudadm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_ciudades() {
        $query = "SELECT * FROM ciudad";
        return $this->db->query($query)->result();
    }

    public function listarCiudadesPorProvicia($idProvincia) {
        $query = "SELECT * FROM ciudad WHERE provincia='$idProvincia' ORDER BY nombre";
        return $this->db->query($query)->result();
    }

}
