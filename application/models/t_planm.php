<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_planm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_planes() {
        $query = "SELECT * FROM t_plan order by nombre";
        return $this->db->query($query)->result();
    }
}
