<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Transaccionesm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function anular_transaccion_id($t_trans, $prefijo_transaccion, $id_transaccion) {
        $SqlInfo = "SELECT * "
                . "FROM anular_transaccion "
                . "WHERE ((t_trans='" . $t_trans . "') AND (prefijo_transaccion='" . $prefijo_transaccion . "') AND (id_transaccion='" . $id_transaccion . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function movimiento_transaccion_id($t_trans, $prefijo, $id, $credito_debito) {
        $SqlInfo = "SELECT * "
                . "FROM movimiento_transaccion "
                . "WHERE ((t_trans='" . $t_trans . "') AND (prefijo='" . $prefijo . "') AND (id='" . $id . "') AND (credito_debito='" . $credito_debito . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function total_transacciones($criterios, $inicio, $filasPorPagina) {
        if ($_SESSION["perfil"] == "admon_sistema" || $_SESSION["perfil"] == "directivo" || $_SESSION["perfil"] == "admon_sede" || $_SESSION["perfil"] == "contador") {
            $query = "SELECT COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.total ELSE (-1 * mt.total) END), 0) total,COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.efectivo_caja ELSE (-1 * mt.efectivo_caja) END), 0) efectivo_caja,COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.valor_cuenta ELSE (-1 * mt.valor_cuenta) END), 0) valor_cuenta ";
            $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
            $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
            $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1) ";
            $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
            $query.="where true ";
            $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
            $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
            $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
            $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
            $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
            $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
            if (!empty($criterios['id_dni_responsable'])) {
                list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
            }
            $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
            $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
            $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
            $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
            $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
            return $this->db->query($query)->result();
        } else {
            //Si es el jefe de cartera puede ver todos las transferencias, recibos de caja, abonos a matricula y facturas independientemente de quien los haga.
            if ($_SESSION["perfil"] == "jefe_cartera") {
                $query = "SELECT COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.total ELSE (-1 * mt.total) END), 0) total,COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.efectivo_caja ELSE (-1 * mt.efectivo_caja) END), 0) efectivo_caja,COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.valor_cuenta ELSE (-1 * mt.valor_cuenta) END), 0) valor_cuenta ";
                $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
                $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
                $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1) ";
                $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
                $query.="where (((mt.t_trans='7') OR (mt.t_trans='8') OR (mt.t_trans='15')) OR ((mt.t_trans='13') AND (mt.id_responsable!='336419') AND (mt.id_responsable!='98667633') AND (mt.id_responsable!='98560635') AND (mt.id_responsable!='92540853') AND (mt.id_responsable!='43602288'))) ";
                $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
                $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
                $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
                $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
                $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
                $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
                if (!empty($criterios['id_dni_responsable'])) {
                    list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                    $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
                }
                $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
                $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
                $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
                return $this->db->query($query)->result();
            } else { //El caso de la secretaria, cartera y auxiliar administrativo: Mostrará los movimientos hechos por ellos y/o los realizados en su caja de efectivo     
                $query = "SELECT COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.total ELSE (-1 * mt.total) END), 0) total,COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.efectivo_caja ELSE (-1 * mt.efectivo_caja) END), 0) efectivo_caja,COALESCE(SUM(CASE mt.credito_debito WHEN '1' THEN mt.valor_cuenta ELSE (-1 * mt.valor_cuenta) END), 0) valor_cuenta ";
                $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
                $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
                $query.="JOIN sede s ON mt.sede=s.id ";
                $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
                $query.="where ((mt.id_responsable={$_SESSION["idResponsable"]}) AND (mt.dni_responsable={$_SESSION["dniResponsable"]})) ";
                $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
                $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
                $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
                $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
                $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
                $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
                if (!empty($criterios['id_dni_responsable'])) {
                    list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                    $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
                }
                $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
                $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
                $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
                return $this->db->query($query)->result();
            }
        }
    }

    public function cantidad_transacciones($criterios, $inicio, $filasPorPagina) {
        if ($_SESSION["perfil"] == "admon_sistema" || $_SESSION["perfil"] == "directivo" || $_SESSION["perfil"] == "admon_sede" || $_SESSION["perfil"] == "contador") {
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
            $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
            if (!empty($criterios['id_dni_responsable'])) {
                list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
            }
            $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
            $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
            $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
            $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
            $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
            return $this->db->query($query)->result();
        } else {
            //Si es el jefe de cartera puede ver todos las transferencias(que no sean de juan, ni de pamela, ni de eliana, ni de milton, ni de luis), recibos de caja, abonos a matricula y facturas independientemente de quien los haga.
            if ($_SESSION["perfil"] == "jefe_cartera") {
                $query = "SELECT count(*) cantidad ";
                $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
                $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
                $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1)";
                $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
                $query.="where (((mt.t_trans='7') OR (mt.t_trans='8') OR (mt.t_trans='15')) OR ((mt.t_trans='13') AND (mt.id_responsable!='336419') AND (mt.id_responsable!='98667633') AND (mt.id_responsable!='98560635') AND (mt.id_responsable!='92540853') AND (mt.id_responsable!='43602288'))) ";
                $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
                $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
                $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
                $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
                $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
                $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
                if (!empty($criterios['id_dni_responsable'])) {
                    list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                    $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
                }
                $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
                $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
                $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
                return $this->db->query($query)->result();
            } else { //El caso de la secretaria, cartera y auxiliar administrativo
                $query = "SELECT count(*) cantidad ";
                $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
                $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
                $query.="JOIN sede s ON mt.sede=s.id ";
                $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
                $query.="where ((mt.id_responsable={$_SESSION["idResponsable"]}) AND (mt.dni_responsable={$_SESSION["dniResponsable"]})) ";
                $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
                $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
                $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
                $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
                $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
                $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
                if (!empty($criterios['id_dni_responsable'])) {
                    list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                    $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
                }
                $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
                $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
                $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
                return $this->db->query($query)->result();
            }
        }
    }

    public function listar_transacciones($criterios, $inicio, $filasPorPagina) {
        if ($_SESSION["perfil"] == "admon_sistema" || $_SESSION["perfil"] == "directivo" || $_SESSION["perfil"] == "admon_sede" || $_SESSION["perfil"] == "contador") {
            $query = "SELECT CASE mt.credito_debito WHEN '1' THEN 'ing' ELSE 'egre' END ingreso_egreso, mt.*,ts.nombre_tabla tipo_trans,tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2,t_trans.nombre_controlador ";
            $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
            $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
            $query.="JOIN t_trans ON mt.t_trans=t_trans.id ";
            $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1)";
            $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
            $query.="where true ";
            $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
            $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
            $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
            $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
            $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
            $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
            if (!empty($criterios['id_dni_responsable'])) {
                list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
            }
            $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
            $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
            $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
            $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
            $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
            $query.=" ORDER BY mt.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
            return $this->db->query($query)->result();
        } else {
            //Si es el jefe de cartera puede ver todos las transferencias(desde o hacia su caja), recibos de caja, abonos a matricula y facturas independientemente de quien los haga.
            if ($_SESSION["perfil"] == "jefe_cartera") {
                $query = "SELECT CASE mt.credito_debito WHEN '1' THEN 'ing' ELSE 'egre' END ingreso_egreso, mt.*,ts.nombre_tabla tipo_trans,tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2,t_trans.nombre_controlador ";
                $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
                $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
                $query.="JOIN t_trans ON mt.t_trans=t_trans.id ";
                $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1)";
                $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
                $query.="where (((mt.t_trans='7') OR (mt.t_trans='8') OR (mt.t_trans='15')) OR ((mt.t_trans='13') AND (mt.id_responsable!='336419') AND (mt.id_responsable!='98667633') AND (mt.id_responsable!='98560635') AND (mt.id_responsable!='92540853') AND (mt.id_responsable!='43602288'))) ";
                $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
                $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
                $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
                $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
                $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
                $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
                if (!empty($criterios['id_dni_responsable'])) {
                    list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                    $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
                }
                $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
                $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
                $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
                $query.=" ORDER BY mt.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
                return $this->db->query($query)->result();
            } else { //El caso de la secretaria, cartera y auxiliar administrativo        
                $query = "SELECT CASE mt.credito_debito WHEN '1' THEN 'ing' ELSE 'egre' END ingreso_egreso, mt.*,ts.nombre_tabla tipo_trans,tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2,t_trans.nombre_controlador ";
                $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
                $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
                $query.="JOIN t_trans ON mt.t_trans=t_trans.id ";
                $query.="JOIN sede s ON mt.sede=s.id ";
                $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
                $query.="where ((mt.id_responsable={$_SESSION["idResponsable"]}) AND (mt.dni_responsable={$_SESSION["dniResponsable"]})) ";
                $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
                $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
                $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
                $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
                $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
                $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
                if (!empty($criterios['id_dni_responsable'])) {
                    list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                    $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
                }
                $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
                $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
                $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
                $query.=" ORDER BY mt.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
                return $this->db->query($query)->result();
            }
        }
    }

    public function listar_transacciones_excel($criterios) {
        if ($_SESSION["perfil"] == "admon_sistema" || $_SESSION["perfil"] == "directivo" || $_SESSION["perfil"] == "admon_sede" || $_SESSION["perfil"] == "contador") {
            $query = "SELECT CASE mt.credito_debito WHEN '1' THEN 'ing' ELSE 'egre' END ingreso_egreso, mt.*,ts.nombre_tabla tipo_trans,tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2 ";
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
            $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
            if (!empty($criterios['id_dni_responsable'])) {
                list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
            }
            $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
            $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
            $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
            $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
            $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
            $query.=" ORDER BY mt.fecha_trans";
            return $this->db->query($query)->result();
        } else {
            //Si es el jefe de cartera puede ver todos las transferencias(desde o hacia su caja), recibos de caja, abonos a matricula y facturas independientemente de quien los haga.
            if ($_SESSION["perfil"] == "jefe_cartera") {
                $query = "SELECT CASE mt.credito_debito WHEN '1' THEN 'ing' ELSE 'egre' END ingreso_egreso, mt.*,ts.nombre_tabla tipo_trans,tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2 ";
                $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
                $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
                $query.="JOIN sede s ON mt.sede=s.id AND s.id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='{$_SESSION["idResponsable"]}' AND dni='{$_SESSION["dniResponsable"]}' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='{$_SESSION["idResponsable"]}' AND dni_empleado='{$_SESSION["dniResponsable"]}' AND vigente=1) as T1)";
                $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
                $query.="where (((mt.t_trans='7') OR (mt.t_trans='8') OR (mt.t_trans='15')) OR ((mt.t_trans='13') AND (mt.id_responsable!='336419') AND (mt.id_responsable!='98667633') AND (mt.id_responsable!='98560635') AND (mt.id_responsable!='92540853') AND (mt.id_responsable!='43602288'))) ";
                $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
                $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
                $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
                $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
                $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
                $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
                if (!empty($criterios['id_dni_responsable'])) {
                    list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                    $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
                }
                $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
                $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
                $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
                $query.=" ORDER BY mt.fecha_trans";
                return $this->db->query($query)->result();
            } else { //El caso de la secretaria, cartera y auxiliar administrativo           
                $query = "SELECT CASE mt.credito_debito WHEN '1' THEN 'ing' ELSE 'egre' END ingreso_egreso, mt.*,ts.nombre_tabla tipo_trans,tc.tipo caja,s.nombre sede,e.nombre1,e.nombre2,e.apellido1,e.apellido2 ";
                $query.="FROM movimiento_transaccion mt  JOIN t_trans ts ON mt.t_trans=ts.id ";
                $query.="LEFT JOIN t_caja tc ON mt.t_caja=tc.id ";
                $query.="JOIN empleado e ON mt.dni_responsable=e.dni AND mt.id_responsable=e.id ";
                $query.="JOIN sede s ON mt.sede=s.id ";
                $query.="where ((mt.id_responsable={$_SESSION["idResponsable"]}) AND (mt.dni_responsable={$_SESSION["dniResponsable"]})) ";
                $query.=(!empty($criterios['desde'])) ? "AND mt.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
                $query.=(!empty($criterios['hasta'])) ? "AND mt.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
                $query.=(isset($criterios['sede'])) ? "AND mt.sede= '{$criterios['sede']}' " : "";
                $query.=(isset($criterios['id'])) ? "AND mt.id= '{$criterios['id']}' " : "";
                $query.=(isset($criterios['caja'])) ? "AND mt.t_caja= '{$criterios['caja']}' " : "";
                $query.=(isset($criterios['cuenta'])) ? "AND mt.cuenta= '{$criterios['cuenta']}' " : "";
                if (!empty($criterios['id_dni_responsable'])) {
                    list($id_responsable, $dni_responsable) = explode("_", $criterios['id_dni_responsable']);
                    $query.= " AND mt.id_responsable = '$id_responsable' AND mt.dni_responsable = '$dni_responsable'";
                }
                $query.=(!isset($criterios['vigente'])) ? "AND mt.vigente= '1' " : "AND mt.vigente='0' ";
                $query.=(isset($criterios['tipo_trans'])) ? "AND mt.t_trans= '{$criterios['tipo_trans']}' " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "e") ? " AND mt.t_caja IS NOT NULL " : "";
                $query.=(isset($criterios['efectivo_bancos']) && $criterios['efectivo_bancos'] == "b") ? " AND mt.cuenta IS NOT NULL " : "";
                $query.=(isset($criterios['credito_debito'])) ? "AND mt.credito_debito = '{$criterios['credito_debito']}'" : "";
                $query.=" ORDER BY mt.fecha_trans";
                return $this->db->query($query)->result();
            }
        }
    }

}
