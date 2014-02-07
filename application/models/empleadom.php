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
                  JOIN t_cuenta tc ON e.cuenta=tc.id
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
        return $this->db->query($query)->result();
    }

    public function listar_empleados($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT e.*,td.*,s.nombre sede,pa.nombre pais,pro.nombre provincia,ciu.nombre ciudad,
                  tdom.tipo tipo_domicilio,tc.tipo cuenta,estem.estado estado_empleado,tdepto.tipo depto,
                  tcargo.cargo_masculino,tcargo.cargo_femenino,sl.nombre nombre_salario,
                  e2.nombre1 nombre1_jefe,e2.nombre2 nombre2_jefe,e2.apellido1 apellido1_jefe,e2.apellido2 apellido2_jefe
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN sede s ON e.sede_ppal=s.id
                  JOIN pais pa ON e.pais=pa.id
                  JOIN provincia pro ON e.provincia=pro.id
                  JOIN ciudad ciu ON e.ciudad=ciu.id
                  JOIN t_domicilio tdom ON e.t_domicilio=tdom.id
                  JOIN t_cuenta tc ON e.cuenta=tc.id
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
        $query.=" order by e.apellido1,e.apellido2 LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

    public function cantidad_empleados_ausencias($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN ausencia_laboral al ON e.dni=al.dni_empleado AND e.id=al.id_empleado
                  JOIN t_ausencia ta ON al.t_ausencia=ta.id
                  JOIN empleado e2 ON e2.dni=al.dni_responsable AND e2.id=al.id_responsable
                  WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['desde'])) ? "AND al.fecha_inicio >= '{$criterios['desde']} 00:00:00'" : "";
        $query.=(!empty($criterios['hasta'])) ? "AND al.fecha_fin <= '{$criterios['hasta']} 23:59:59'" : "";
        $query.=(!empty($criterios['tipo_ausencia'])) ? "AND ta.id = '{$criterios['tipo_ausencia']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND al.vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_empleados_ausencias($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT e.*,td.*,al.*,ta.tipo ausencia,
                 e2.nombre1 nom1_resposable,e2.nombre2 nom2_resposable,e2.apellido1 apell1_resposable,e2.apellido2 apell2_resposable
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN ausencia_laboral al ON e.dni=al.dni_empleado AND e.id=al.id_empleado
                  JOIN t_ausencia ta ON al.t_ausencia=ta.id
                  JOIN empleado e2 ON e2.dni=al.dni_responsable AND e2.id=al.id_responsable
                  WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['desde'])) ? "AND al.fecha_inicio >= '{$criterios['desde']} 00:00:00'" : "";
        $query.=(!empty($criterios['hasta'])) ? "AND al.fecha_fin <= '{$criterios['hasta']} 23:59:59'" : "";
        $query.=(!empty($criterios['tipo_ausencia'])) ? "AND ta.id = '{$criterios['tipo_ausencia']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND al.vigente = '{$criterios['vigente']}'" : "";
        $query.=" order by e.apellido1,e.apellido2 LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
