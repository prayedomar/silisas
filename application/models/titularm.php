<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Titularm extends CI_Model {

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

    public function cantidad_titulares($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM titular t
                  JOIN t_dni td ON td.id=t.dni
                  JOIN pais pa ON t.pais=pa.id
                  JOIN provincia pro ON t.provincia=pro.id
                  JOIN ciudad ciu ON t.ciudad=ciu.id
                  JOIN t_domicilio tdom ON t.t_domicilio=tdom.id
                      WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND t.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND t.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(t.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(t.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(t.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(t.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND t.fecha_nacimiento = '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND t.vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_titulares($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT t.*,t.id documento,td.tipo,pa.nombre,pa.nombre pais,pro.nombre provincia,ciu.nombre ciudad,tdom.tipo tipo_domicilio
                  FROM titular t
                  JOIN t_dni td ON td.id=t.dni
                  JOIN pais pa ON t.pais=pa.id
                  JOIN provincia pro ON t.provincia=pro.id
                  JOIN ciudad ciu ON t.ciudad=ciu.id
                  JOIN t_domicilio tdom ON t.t_domicilio=tdom.id
                      WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND t.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND t.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(t.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(t.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(t.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(t.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND t.fecha_nacimiento = '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND t.vigente = '{$criterios['vigente']}'" : "";
        $query.=" order by t.apellido1,t.apellido2 LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
