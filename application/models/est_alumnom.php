<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Est_alumnom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_estados_de_alumno() {
        $query = "SELECT * FROM est_alumno order by estado";
        return $this->db->query($query)->result();
    }

}
