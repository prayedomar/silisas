<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Salariom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_salarios($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM salario sl
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(sl.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['tipo_salario'])) ? "AND sl.t_salario = '{$criterios['tipo_salario']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND sl.vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_salarios($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT sl.*,ts.tipo t_salarios,sl.id id_salario  from salario sl
                  JOIN t_salario ts ON sl.t_salario=ts.id ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(sl.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['tipo_salario'])) ? "AND sl.t_salario = '{$criterios['tipo_salario']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND sl.vigente = '{$criterios['vigente']}'" : "";
        $query.=" order by sl.nombre LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
