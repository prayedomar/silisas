<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Concepto_base_nominam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_por_salario($idSalario) {
        $query = "SELECT * FROM concepto_base_nomina cbn
                  JOIN t_concepto_nomina tcn ON cbn.t_concepto_nomina=tcn.id
                  WHERE cbn.salario='$idSalario'";
        return $this->db->query($query)->result();
    }

}
