<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Salonm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_salones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM sede s
                  JOIN salon sa on s.id=sa.sede
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(sa.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['sede'])) ? "AND s.id = '{$criterios['sede']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND sa.vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_salones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT sa.*,s.nombre sede 
                  FROM sede s
                  JOIN salon sa on s.id=sa.sede
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(sa.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['sede'])) ? "AND s.id = '{$criterios['sede']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND sa.vigente = '{$criterios['vigente']}'" : "";
        $query.=" order by sa.nombre LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
