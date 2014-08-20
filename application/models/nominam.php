<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Nominam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function nominas_vigentes() {
        $SqlInfo = "SELECT * FROM nomina where vigente=1";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function todas_las_nominas() {
        $SqlInfo = "SELECT * FROM nomina";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function nomina_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT no.*, s.nombre sede_caja, t_ca.tipo tipo_caja, t_d.tipo departamento, t_c.cargo_masculino cargo_masculino, t_c.cargo_femenino cargo_femenino, t_p.tipo tipo_periodicidad, CONCAT(em.nombre1, ' ', em.nombre2, ' ', em.apellido1, ' ', em.apellido2) empleado, em.genero genero_empleado, CONCAT(re.nombre1, ' ', re.apellido1) responsable "
                . "FROM nomina no "
                . "JOIN t_depto t_d ON (no.depto = t_d.id) "
                . "JOIN t_cargo t_c ON (no.cargo = t_c.id) "
                . "JOIN t_periodicidad_nomina t_p ON (no.t_periodicidad = t_p.id) "
                . "LEFT JOIN sede s ON (no.sede_caja_origen = s.id) "
                . "LEFT JOIN t_caja t_ca ON (no.t_caja_origen = t_ca.id) "
                . "JOIN empleado em ON ((no.id_empleado = em.id) and (no.dni_empleado = em.dni)) "
                . "JOIN empleado re ON ((no.id_responsable = re.id) and (no.dni_responsable = re.dni)) "
                . "where ((no.prefijo='" . $prefijo . "')AND(no.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function concepto_nomina_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT c_n.*, t_c.tipo, t_c.debito_credito "
                . "FROM concepto_nomina c_n "
                . "JOIN t_concepto_nomina t_c ON (c_n.t_concepto_nomina = t_c.id) "
                . "where ((c_n.prefijo_nomina='" . $prefijo . "')AND(c_n.id_nomina='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function concepto_nomina_group_matricula($prefijo, $id) {
        $SqlInfo = "(SELECT c_n.*, t_c.tipo, t_c.debito_credito, sum(valor_unitario) total "
                . "FROM concepto_nomina c_n "
                . "JOIN t_concepto_nomina t_c ON (c_n.t_concepto_nomina = t_c.id) "
                . "where ((c_n.prefijo_nomina='" . $prefijo . "')AND(c_n.id_nomina='" . $id . "')AND((c_n.t_concepto_nomina='28')or(c_n.t_concepto_nomina='29'))) group by c_n.detalle order by c_n.id) "
                . "UNION "
                . "(SELECT c_n.*, t_c.tipo, t_c.debito_credito, c_n.valor_unitario total "
                . "FROM concepto_nomina c_n "
                . "JOIN t_concepto_nomina t_c ON (c_n.t_concepto_nomina = t_c.id) "
                . "where ((c_n.prefijo_nomina='" . $prefijo . "')AND(c_n.id_nomina='" . $id . "')AND(c_n.t_concepto_nomina!='28')AND(c_n.t_concepto_nomina!='29')) order by c_n.id)";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function total_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT COALESCE(SUM(mt.total), 0) total,COALESCE(SUM(mt.efectivo_retirado), 0) efectivo_caja,COALESCE(SUM(mt.valor_retirado), 0) valor_cuenta ";
        $query.="FROM nomina mt ";
        $query.="LEFT JOIN t_caja tc ON mt.t_caja_origen=tc.id ";
        $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1) ";
        $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
        $query.="where true ";
        $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND mt.t_caja_origen= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta_origen= '{$criterios['cuenta']}' " : "";
        if (!empty($criterios['id_dni_empleado'])) {
            list($id_empleado, $dni_empleado) = explode("_", $criterios['id_dni_empleado']);
            $query.= " AND (mt.id_empleado = '$id_empleado' AND mt.dni_empleado = '$dni_empleado') ";
        }
        if (!empty($criterios['id_dni_responsable'])) {
            list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
            $query.= " AND (mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable') ";
        }
        $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja_origen IS NOT NULL " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta_origen IS NOT NULL " : "";
        return $this->db->query($query)->result();
    }

    public function cantidad_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad ";
        $query.="FROM nomina mt ";
        $query.="LEFT JOIN t_caja tc ON mt.t_caja_origen=tc.id ";
        $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1) ";
        $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
        $query.="where true ";
        $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND mt.t_caja_origen= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta_origen= '{$criterios['cuenta']}' " : "";
        if (!empty($criterios['id_dni_empleado'])) {
            list($id_empleado, $dni_empleado) = explode("_", $criterios['id_dni_empleado']);
            $query.= " AND (mt.id_empleado = '$id_empleado' AND mt.dni_empleado = '$dni_empleado') ";
        }
        if (!empty($criterios['id_dni_responsable'])) {
            list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
            $query.= " AND (mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable') ";
        }
        $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja_origen IS NOT NULL " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta_origen IS NOT NULL " : "";
        return $this->db->query($query)->result();
    }

    public function listar_transacciones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT mt.*,t_d.tipo tipo_depto, tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2,CONCAT(em.nombre1, ' ', em.nombre2, ' ', em.apellido1) empleado ";
        $query.="FROM nomina mt  ";
        $query.="LEFT JOIN t_caja tc ON mt.t_caja_origen=tc.id ";
        $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1) ";
        $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
        $query.="JOIN empleado em ON mt.dni_empleado=em.dni AND mt.id_empleado=em.id ";
        $query.="JOIN t_depto t_d ON mt.depto=t_d.id ";
        $query.="where true ";
        $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND mt.t_caja_origen= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta_origen= '{$criterios['cuenta']}' " : "";
        if (!empty($criterios['id_dni_empleado'])) {
            list($id_empleado, $dni_empleado) = explode("_", $criterios['id_dni_empleado']);
            $query.= " AND (mt.id_empleado = '$id_empleado' AND mt.dni_empleado = '$dni_empleado') ";
        }
        if (!empty($criterios['id_dni_responsable'])) {
            list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
            $query.= " AND (mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable') ";
        }
        $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja_origen IS NOT NULL " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta_origen IS NOT NULL " : "";
        $query.=" ORDER BY mt.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }
    
    public function listar_todas_las_transacciones($criterios) {
        $query = "SELECT mt.*,t_d.tipo tipo_depto, tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2,CONCAT(em.nombre1, ' ', em.nombre2, ' ', em.apellido1) empleado ";
        $query.="FROM nomina mt  ";
        $query.="LEFT JOIN t_caja tc ON mt.t_caja_origen=tc.id ";
        $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1) ";
        $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
        $query.="JOIN empleado em ON mt.dni_empleado=em.dni AND mt.id_empleado=em.id ";
        $query.="JOIN t_depto t_d ON mt.depto=t_d.id ";
        $query.="where true ";
        $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
        $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
        $query.=(isset($criterios['caja'])) ? "AND mt.t_caja_origen= '{$criterios['caja']}' " : "";
        $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta_origen= '{$criterios['cuenta']}' " : "";
        if (!empty($criterios['id_dni_empleado'])) {
            list($id_empleado, $dni_empleado) = explode("_", $criterios['id_dni_empleado']);
            $query.= " AND (mt.id_empleado = '$id_empleado' AND mt.dni_empleado = '$dni_empleado') ";
        }
        if (!empty($criterios['id_dni_responsable'])) {
            list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
            $query.= " AND (mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable') ";
        }
        $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja_origen IS NOT NULL " : "";
        $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta_origen IS NOT NULL " : "";
        $query.=" ORDER BY mt.fecha_trans ";
        return $this->db->query($query)->result();
    }    

}
