<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Transaccionesm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function total_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT COALESCE(SUM(mt.total), 0) total,COALESCE(SUM(mt.efectivo_caja), 0) efectivo_caja,COALESCE(SUM(mt.valor_cuenta), 0) valor_cuenta  ";
        $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
        $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
        $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1)";
        $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
        $query.="where true ";
        $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['tipo_documento'])) ? "AND mt.dni_responsable= '{$criterios['tipo_documento']}' " : "";
        $query.=(isset($criterios['documento'])) ? "AND mt.id_responsable= '{$criterios['documento']}' " : "";
        $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
        $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
        return $this->db->query($query)->result();
    }

    public function cantidad_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad ";
        $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
        $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
        $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1)";
        $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
        $query.="where true ";
        $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['tipo_documento'])) ? "AND mt.dni_responsable= '{$criterios['tipo_documento']}' " : "";
        $query.=(isset($criterios['documento'])) ? "AND mt.id_responsable= '{$criterios['documento']}' " : "";
        $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
        $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
        return $this->db->query($query)->result();
    }

    public function listar_transacciones($criterios, $inicio, $filasPorPagina) {


        $query = "SELECT mt.*,ts.nombre_tabla tipo_trans,tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2 ";
        $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
        $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
        $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1)";
        $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
        $query.="where true ";
        $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['tipo_documento'])) ? "AND mt.dni_responsable= '{$criterios['tipo_documento']}' " : "";
        $query.=(isset($criterios['documento'])) ? "AND mt.id_responsable= '{$criterios['documento']}' " : "";
        $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
        $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
        $query.=" ORDER BY mt.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

    public function listar_transacciones_excel($criterios) {
        $query = "SELECT mt.*,ts.nombre_tabla tipo_trans,tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2 ";
        $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
        $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
        $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1)";
        $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
        $query.="where true ";
        $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['tipo_documento'])) ? "AND mt.dni_responsable= '{$criterios['tipo_documento']}' " : "";
        $query.=(isset($criterios['documento'])) ? "AND mt.id_responsable= '{$criterios['documento']}' " : "";
        $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
        $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
        $query.=" ORDER BY mt.fecha_trans DESC";
        return $this->db->query($query)->result();
    }

}
