<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Empleadom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_empleados($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN sede s ON e.sede_ppal=s.id
                  JOIN pais pa ON e.pais=pa.id
                  JOIN provincia pro ON e.provincia=pro.id
                  JOIN ciudad ciu ON e.ciudad=ciu.id
                  JOIN t_domicilio tdom ON e.t_domicilio=tdom.id
                  JOIN est_empleado estem ON e.estado=estem.id
                  JOIN t_depto tdepto ON e.depto=tdepto.id
                  JOIN t_cargo tcargo ON e.cargo=tcargo.id
                  JOIN salario sl ON e.salario=sl.id
                  JOIN empleado e2 ON e.id_jefe=e2.id AND e.dni_jefe=e2.dni
                  WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['estado'])) ? "AND e.estado = '{$criterios['estado']}'" : "";
        $query.=(!empty($criterios['sede'])) ? "AND e.sede_ppal = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['depto'])) ? "AND e.depto = '{$criterios['depto']}'" : "";
        $query.=(!empty($criterios['cargo'])) ? "AND e.cargo = '{$criterios['cargo']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND e.fecha_nacimiento = '{$criterios['fecha_nacimiento']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_empleados($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT e.*,td.*,s.nombre sede,pa.nombre pais,pro.nombre provincia,ciu.nombre ciudad,
                  tdom.tipo tipo_domicilio,estem.estado estado_empleado,tdepto.tipo depto,
                  tcargo.cargo_masculino,tcargo.cargo_femenino,sl.nombre nombre_salario,
                  e2.nombre1 nombre1_jefe,e2.nombre2 nombre2_jefe,e2.apellido1 apellido1_jefe,e2.apellido2 apellido2_jefe
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN sede s ON e.sede_ppal=s.id
                  JOIN pais pa ON e.pais=pa.id
                  JOIN provincia pro ON e.provincia=pro.id
                  JOIN ciudad ciu ON e.ciudad=ciu.id
                  JOIN t_domicilio tdom ON e.t_domicilio=tdom.id
                  JOIN est_empleado estem ON e.estado=estem.id
                  JOIN t_depto tdepto ON e.depto=tdepto.id
                  JOIN t_cargo tcargo ON e.cargo=tcargo.id
                  JOIN salario sl ON e.salario=sl.id
                  JOIN empleado e2 ON e.id_jefe=e2.id AND e.dni_jefe=e2.dni
                  WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['estado'])) ? "AND e.estado = '{$criterios['estado']}'" : "";
        $query.=(!empty($criterios['sede'])) ? "AND e.sede_ppal = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['depto'])) ? "AND e.depto = '{$criterios['depto']}'" : "";
        $query.=(!empty($criterios['cargo'])) ? "AND e.cargo = '{$criterios['cargo']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND e.fecha_nacimiento = '{$criterios['fecha_nacimiento']}'" : "";
        $query.=" order by e.apellido1,e.apellido2 LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
