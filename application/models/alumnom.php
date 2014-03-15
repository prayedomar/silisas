<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Alumnom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_alumnos($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM alumno a
                  JOIN t_dni td ON a.dni=td.id
                  LEFT JOIN  pais pa ON a.pais=pa.id
                  LEFT JOIN  provincia pro ON a.provincia=pro.id
                  LEFT JOIN  ciudad ciu ON a.ciudad=ciu.id
                  LEFT JOIN  t_domicilio tdom ON a.t_domicilio=tdom.id
                  JOIN est_alumno ealum ON a.estado=ealum.id
                  JOIN sede s ON a.sede_ppal=s.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND a.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND a.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(a.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(a.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(a.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(a.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(a.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(a.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        $query.=(isset($criterios['matricula'])) ? "AND a.matricula = '{$criterios['matricula']}'" : "";
        $query.=(isset($criterios['curso'])) ? "AND a.t_curso = '{$criterios['curso']}'" : "";
        $query.=(isset($criterios['sede_ppal'])) ? "AND a.sede_ppal = '{$criterios['sede_ppal']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_alumnos($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT a.*,a.id documento,td.*,pa.nombre pais,pro.nombre provincia,ciu.nombre ciudad,
                  tdom.tipo tipo_domicilio,ealum.estado estado_alumno,s.nombre sede,tc.tipo nombre_curso,gr.fecha_grados
                  FROM alumno a
                  JOIN t_dni td ON a.dni=td.id
                 LEFT JOIN pais pa ON a.pais=pa.id
                 LEFT JOIN provincia pro ON a.provincia=pro.id
                 LEFT JOIN ciudad ciu ON a.ciudad=ciu.id
                 LEFT JOIN t_domicilio tdom ON a.t_domicilio=tdom.id
                LEFT  JOIN est_alumno ealum ON a.estado=ealum.id
                  JOIN sede s ON a.sede_ppal=s.id
                 LEFT JOIN t_curso tc ON a.t_curso=tc.id
                  LEFT JOIN grados gr ON a.grados=gr.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND a.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND a.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(a.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(a.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(a.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(a.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(a.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(a.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        $query.=(isset($criterios['matricula'])) ? "AND a.matricula = '{$criterios['matricula']}'" : "";
        $query.=(isset($criterios['curso'])) ? "AND a.t_curso = '{$criterios['curso']}'" : "";
        $query.=(isset($criterios['sede_ppal'])) ? "AND a.sede_ppal = '{$criterios['sede_ppal']}'" : "";
        $query.=" order by a.apellido1,a.apellido2 LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

    public function listar_alumnos_excel($criterios) {
        $query = "SELECT a.*,a.id documento,td.*,pa.nombre pais,pro.nombre provincia,ciu.nombre ciudad,
                  tdom.tipo tipo_domicilio,ealum.estado estado_alumno,s.nombre sede,tc.tipo nombre_curso,gr.fecha_grados
                  FROM alumno a
                  JOIN t_dni td ON a.dni=td.id
                LEFT  JOIN pais pa ON a.pais=pa.id
                 LEFT JOIN provincia pro ON a.provincia=pro.id
                 LEFT JOIN ciudad ciu ON a.ciudad=ciu.id
                  LEFT JOIN t_domicilio tdom ON a.t_domicilio=tdom.id
                  JOIN est_alumno ealum ON a.estado=ealum.id
                  JOIN sede s ON a.sede_ppal=s.id
                 LEFT JOIN t_curso tc ON a.t_curso=tc.id
                  LEFT JOIN grados gr ON a.grados=gr.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND a.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND a.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(a.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(a.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(a.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(a.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(a.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(a.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        $query.=(isset($criterios['matricula'])) ? "AND a.matricula = '{$criterios['matricula']}'" : "";
        $query.=(isset($criterios['curso'])) ? "AND a.t_curso = '{$criterios['curso']}'" : "";
        $query.=(isset($criterios['sede_ppal'])) ? "AND a.sede_ppal = '{$criterios['sede_ppal']}'" : "";
        $query.=" order by a.apellido1,a.apellido2";
        return $this->db->query($query)->result();
    }

}
