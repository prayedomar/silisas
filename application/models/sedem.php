<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Sedem extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidadSedes($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM sede s
                  JOIN pais p ON p.id=s.pais
                  JOIN provincia pr ON pr.id=s.provincia
                  JOIN ciudad c ON c.id=s.ciudad
                  JOIN est_sede es ON es.id=s.estado
                  JOIN empleado em ON em.id=s.id_responsable AND em.dni=s.dni_responsable
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(s.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['pais'])) ? "AND s.pais = '{$criterios['pais']}'" : "";
        $query.=(!empty($criterios['departamento'])) ? "AND s.provincia = '{$criterios['departamento']}'" : "";
        $query.=(!empty($criterios['ciudad'])) ? "AND s.ciudad = '{$criterios['ciudad']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND s.estado = '{$criterios['estado']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_sedes($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT s.*,p.nombre pais,pr.nombre departamento,c.nombre ciudad,es.estado,em.nombre1,em.nombre2,em.apellido1,em.apellido2
                  FROM sede s
                  JOIN pais p ON p.id=s.pais
                  JOIN provincia pr ON pr.id=s.provincia
                  JOIN ciudad c ON c.id=s.ciudad
                  JOIN est_sede es ON es.id=s.estado
                  JOIN empleado em ON em.id=s.id_responsable AND em.dni=s.dni_responsable
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(s.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['pais'])) ? "AND s.pais = '{$criterios['pais']}'" : "";
        $query.=(!empty($criterios['departamento'])) ? "AND s.provincia = '{$criterios['departamento']}'" : "";
        $query.=(!empty($criterios['ciudad'])) ? "AND s.ciudad = '{$criterios['ciudad']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND s.estado = '{$criterios['estado']}'" : "";
        $query.=" order by s.nombre LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
