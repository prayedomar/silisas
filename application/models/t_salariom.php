<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_salariom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todos_los_salarios() {
        $query = "SELECT * FROM t_salario";
        return $this->db->query($query)->result();
    }


}
