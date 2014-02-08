<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_cursom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_tipos_curso() {
        $query = "SELECT * FROM t_curso order by tipo";
        return $this->db->query($query)->result();
    }


}
