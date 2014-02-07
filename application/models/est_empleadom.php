<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Est_empleadom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_estados_de_empleado() {
        $query = "SELECT * FROM est_empleado order by estado";
        return $this->db->query($query)->result();
    }

}
