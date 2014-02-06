<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Empleadom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_empleados($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM empleado e
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(s.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['pais'])) ? "AND s.pais = '{$criterios['pais']}'" : "";
        $query.=(!empty($criterios['departamento'])) ? "AND s.provincia = '{$criterios['departamento']}'" : "";
        $query.=(!empty($criterios['ciudad'])) ? "AND s.ciudad = '{$criterios['ciudad']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND s.estado = '{$criterios['estado']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_empleados($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT e.*
                  FROM empleado e
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(s.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['pais'])) ? "AND s.pais = '{$criterios['pais']}'" : "";
        $query.=(!empty($criterios['departamento'])) ? "AND s.provincia = '{$criterios['departamento']}'" : "";
        $query.=(!empty($criterios['ciudad'])) ? "AND s.ciudad = '{$criterios['ciudad']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND s.estado = '{$criterios['estado']}'" : "";
        return $this->db->query($query)->result();
        $query.=" order by s.nombre LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
