<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_cargom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function t_cargo_id($id_cargo) {
        $SqlInfo = "SELECT * FROM t_cargo where (id='" . $id_cargo . "')";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function listar_todas_los_cargos() {
        $query = "SELECT * FROM t_cargo order by cargo_masculino";
        return $this->db->query($query)->result();
    }

    public function cargo_depto($depto) {
        $query = "SELECT t_c.* "
                . "FROM t_cargo t_c "
                . "where (t_c.depto='" . $depto . "')";
        return $this->db->query($query)->result();
    }

    public function listar_todas_los_cargos_relaciones_publicas() {
        $query = "SELECT * FROM t_cargo where depto=3 order by cargo_masculino";
        return $this->db->query($query)->result();
    }

    public function listar_todas_los_cargos_por_depto($idDpto) {
        $query = "SELECT * FROM t_cargo where depto='$idDpto' order by cargo_masculino";
        return $this->db->query($query)->result();
    }

}
