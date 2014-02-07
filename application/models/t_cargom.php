<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_cargom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_cargos() {
        $query = "SELECT * FROM t_cargo order by cargo_masculino";
        return $this->db->query($query)->result();
    }
  public function listar_todas_los_cargos_por_depto($idDpto) {
        $query = "SELECT * FROM t_cargo where depto='$idDpto' order by cargo_masculino";
        return $this->db->query($query)->result();
    }
}
