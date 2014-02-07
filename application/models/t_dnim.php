<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_dnim extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_tipos_de_documentos() {
        $query = "SELECT * FROM t_dni order by tipo";
        return $this->db->query($query)->result();
    }

}
