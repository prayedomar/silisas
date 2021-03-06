<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Matriculam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function matricula_id($id) {
        $SqlInfo = "SELECT e_m.estado tipo_estado, ma.*, se.nombre sede_ppal, CONCAT(t_p.nombre, ' - ', t_p.anio) nombre_plan, t_p.valor_total, t_p.valor_inicial, t_p.valor_cuota, t_p.cant_cuotas, CONCAT(ej.nombre1, ' ', ej.nombre2, ' ', ej.apellido1) ejecutivo, CONCAT(re.nombre1, ' ', re.apellido1) responsable, CONCAT(ti.nombre1, ' ', ti.nombre2, ' ', ti.apellido1,  ' ', ti.apellido2) titular, ti.direccion, ti.telefono, ti.celular, (t_p.valor_total - ((SELECT COALESCE(SUM(subtotal), 0) FROM factura WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(subtotal), 0) FROM recibo_caja WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(subtotal), 0) FROM abono_matricula WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(valor), 0) FROM descuento_matricula WHERE ((matricula=ma.contrato) AND (vigente=1))))+(SELECT COALESCE(SUM(total), 0) FROM nota_credito WHERE ((matricula=ma.contrato) AND (vigente=1)))) saldo, (SELECT COALESCE(SUM(valor), 0) FROM descuento_matricula WHERE ((matricula=ma.contrato) AND (vigente=1))) descuentos "
                . "FROM matricula ma "
                . "JOIN empleado ej ON ((ma.id_ejecutivo = ej.id) and (ma.dni_ejecutivo = ej.dni)) "
                . "JOIN titular ti ON ((ma.id_titular = ti.id) and (ma.dni_titular = ti.dni)) "
                . "JOIN empleado re ON ((ma.id_responsable = re.id) and (ma.dni_responsable = re.dni)) "
                . "JOIN t_plan t_p ON ma.plan=t_p.id "
                . "JOIN est_deuda e_m ON ma.estado=e_m.id "
                . "JOIN sede se ON ma.sede=se.id "
                . "where (ma.contrato='" . $id . "')";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }
    
    public function sede_matricula_id($id) {
        $SqlInfo = "SELECT ma.sede id_sede, se.nombre nombre_sede "
                . "FROM matricula ma "
                . "JOIN sede se ON ma.sede=se.id "
                . "where (ma.contrato='" . $id . "')";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }    

    public function pagos_matricula_id($id_matricula) {
        $query = "SELECT fa.prefijo, fa.id, fa.t_trans, fa.subtotal, fa.int_mora, fa.descuento, fa.id_responsable, fa.dni_responsable, fa.fecha_trans, CONCAT(re.nombre1, ' ', re.apellido1) responsable "
                . "FROM factura fa "
                . "JOIN matricula ma ON (fa.matricula = ma.contrato) "
                . "JOIN empleado re ON ((fa.id_responsable = re.id) and (fa.dni_responsable = re.dni)) "
                . "where ((ma.contrato='" . $id_matricula . "') AND (fa.vigente='1')) "
                . "UNION "
                . "SELECT rc.prefijo, rc.id, rc.t_trans, rc.subtotal, rc.int_mora, rc.descuento, rc.id_responsable, rc.dni_responsable, rc.fecha_trans, CONCAT(re.nombre1, ' ', re.apellido1) responsable  "
                . "FROM recibo_caja rc "
                . "JOIN matricula ma ON (rc.matricula = ma.contrato) "
                . "JOIN empleado re ON ((rc.id_responsable = re.id) and (rc.dni_responsable = re.dni)) "
                . "where ((ma.contrato='" . $id_matricula . "') AND (rc.vigente='1'))"
                . "UNION "
                . "SELECT am.prefijo, am.id, am.t_trans, am.subtotal, am.int_mora, am.descuento, am.id_responsable, am.dni_responsable, am.fecha_trans, CONCAT(re.nombre1, ' ', re.apellido1) responsable  "
                . "FROM abono_matricula am "
                . "JOIN matricula ma ON (am.matricula = ma.contrato) "
                . "JOIN empleado re ON ((am.id_responsable = re.id) and (am.dni_responsable = re.dni)) "
                . "where ((ma.contrato='" . $id_matricula . "') AND (am.vigente='1')) ORDER BY fecha_trans";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }

    public function cantidad_matriculas($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM matricula ma
                  JOIN titular t ON ma.dni_titular=t.dni AND ma.id_titular=t.id
                  JOIN t_plan p ON ma.plan=p.id
                  JOIN t_dni tdni ON ma.dni_titular=tdni.id
                  JOIN sede s ON ma.sede=s.id
                  LEFT JOIN  alumno al ON ma.contrato=al.matricula
                  where true ";
        $query.=(!empty($criterios['contrato'])) ? "AND ma.contrato = '{$criterios['contrato']}'" : "";
        $query.=(!empty($criterios['fecha_matricula_desde'])) ? "AND ma.fecha_matricula >= '{$criterios['fecha_matricula_desde']}'" : "";
        $query.=(!empty($criterios['fecha_matricula_hasta'])) ? "AND ma.fecha_matricula <= '{$criterios['fecha_matricula_hasta']}'" : "";
        $query.=(!empty($criterios['id_titular'])) ? "AND ma.id_titular = '{$criterios['id_titular']}'" : "";
        $query.=(!empty($criterios['id_ejecutivo'])) ? "AND ma.id_ejecutivo = '{$criterios['id_ejecutivo']}'" : "";
        $query.=(!empty($criterios['cargo_ejecutivo'])) ? "AND ma.cargo_ejecutivo = '{$criterios['cargo_ejecutivo']}'" : "";
        $query.=(!empty($criterios['plan'])) ? "AND ma.plan = '{$criterios['plan']}'" : "";
        $query.=(!empty($criterios['datacredito'])) ? "AND ma.datacredito = '{$criterios['datacredito']}'" : "";
        $query.=(!empty($criterios['juridico'])) ? "AND ma.juridico = '{$criterios['juridico']}'" : "";
        $query.=(!empty($criterios['sede'])) ? "AND ma.sede = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND ma.estado = '{$criterios['estado']}'" : "";
        $query.=(!empty($criterios['id_alumno'])) ? "AND al.id = '{$criterios['id_alumno']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_matriculas($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT ma.*,t.nombre1,t.nombre2,t.apellido1,t.apellido2,p.nombre nombre_plan,p.anio anio_plan,s.nombre nombre_sede,tdni.abreviacion nombre_dni,
                    tc.cargo_masculino,em.nombre1 nom1res,em.nombre2 nom2res,em.apellido1 apell1res,em.apellido2 apell2res,
                    ed.estado nombre_estado,ma.observacion observacion_matricula,td.abreviacion dni_ejecutivo,
                    em2.nombre1 nombre1ejecutivo,em2.nombre2 nombre2ejecutivo,em2.apellido1 apellido1ejecutivo,em2.apellido2 apellido2ejecutivo
                  FROM matricula ma
                  JOIN titular t ON ma.dni_titular=t.dni AND ma.id_titular=t.id
                  JOIN t_plan p ON ma.plan=p.id
                  JOIN t_dni tdni ON ma.dni_titular=tdni.id
                  JOIN sede s ON ma.sede=s.id
                  LEFT JOIN  alumno al ON ma.contrato=al.matricula AND al.matricula is null
                  JOIN  t_cargo tc ON ma.cargo_ejecutivo=tc.id
                   JOIN empleado em ON ma.dni_responsable=em.dni AND ma.id_responsable=em.id
                    JOIN  est_deuda ed ON ma.estado=ed.id
                       JOIN empleado em2 ON ma.dni_ejecutivo=em2.dni AND ma.id_ejecutivo=em2.id
                         JOIN  t_dni td ON ma.dni_ejecutivo=td.id
                  where true ";
        $query.=(!empty($criterios['contrato'])) ? "AND ma.contrato = '{$criterios['contrato']}'" : "";
        $query.=(!empty($criterios['fecha_matricula_desde'])) ? "AND ma.fecha_matricula >= '{$criterios['fecha_matricula_desde']}'" : "";
        $query.=(!empty($criterios['fecha_matricula_hasta'])) ? "AND ma.fecha_matricula <= '{$criterios['fecha_matricula_hasta']}'" : "";
        $query.=(!empty($criterios['id_titular'])) ? "AND ma.id_titular = '{$criterios['id_titular']}'" : "";
        $query.=(!empty($criterios['id_ejecutivo'])) ? "AND ma.id_ejecutivo = '{$criterios['id_ejecutivo']}'" : "";
        $query.=(!empty($criterios['cargo_ejecutivo'])) ? "AND ma.cargo_ejecutivo = '{$criterios['cargo_ejecutivo']}'" : "";
        $query.=(!empty($criterios['plan'])) ? "AND ma.plan = '{$criterios['plan']}'" : "";
        $query.=(!empty($criterios['datacredito'])) ? "AND ma.datacredito = '{$criterios['datacredito']}'" : "";
        $query.=(!empty($criterios['juridico'])) ? "AND ma.juridico = '{$criterios['juridico']}'" : "";
        $query.=(!empty($criterios['sede'])) ? "AND ma.sede = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND ma.estado = '{$criterios['estado']}'" : "";
        $query.=(!empty($criterios['id_alumno'])) ? "AND al.id = '{$criterios['id_alumno']}'" : "";
        $query.=" order by ma.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
        //   echo $query;
        return $this->db->query($query)->result();
    }

    public function listar_matriculas_excel($criterios) {
        $query = "SELECT ma.*,t.nombre1,t.nombre2,t.apellido1,t.apellido2,p.nombre nombre_plan,s.nombre nombre_sede,tdni.abreviacion nombre_dni,
                    tc.cargo_masculino,em.nombre1 nom1res,em.nombre2 nom2res,em.apellido1 apell1res,em.apellido2 apell2res,
                    ea.estado nombre_estado,ma.observacion observacion_matricula,td.abreviacion dni_ejecutivo,
                    em2.nombre1 nombre1ejecutivo,em2.nombre2 nombre2ejecutivo,em2.apellido1 apellido1ejecutivo,em2.apellido2 apellido2ejecutivo
                  FROM matricula ma
                  JOIN titular t ON ma.dni_titular=t.dni AND ma.id_titular=t.id
                  JOIN t_plan p ON ma.plan=p.id
                  JOIN t_dni tdni ON ma.dni_titular=tdni.id
                  JOIN sede s ON ma.sede=s.id
                 LEFT JOIN  alumno al ON ma.contrato=al.matricula AND al.matricula is null
                  JOIN  t_cargo tc ON ma.cargo_ejecutivo=tc.id
                   JOIN empleado em ON ma.dni_responsable=em.dni AND ma.id_responsable=em.id
                    JOIN  est_alumno ea ON ma.estado=ea.id
                       JOIN empleado em2 ON ma.dni_ejecutivo=em2.dni AND ma.id_ejecutivo=em2.id
                         JOIN  t_dni td ON ma.dni_ejecutivo=td.id
                  where true ";
        $query.=(!empty($criterios['contrato'])) ? "AND ma.contrato = '{$criterios['contrato']}'" : "";
        $query.=(!empty($criterios['fecha_matricula_desde'])) ? "AND ma.fecha_matricula >= '{$criterios['fecha_matricula_desde']}'" : "";
        $query.=(!empty($criterios['fecha_matricula_hasta'])) ? "AND ma.fecha_matricula <= '{$criterios['fecha_matricula_hasta']}'" : "";
        $query.=(!empty($criterios['id_titular'])) ? "AND ma.id_titular = '{$criterios['id_titular']}'" : "";
        $query.=(!empty($criterios['id_ejecutivo'])) ? "AND ma.id_ejecutivo = '{$criterios['id_ejecutivo']}'" : "";
        $query.=(!empty($criterios['cargo_ejecutivo'])) ? "AND ma.cargo_ejecutivo = '{$criterios['cargo_ejecutivo']}'" : "";
        $query.=(!empty($criterios['plan'])) ? "AND ma.plan = '{$criterios['plan']}'" : "";
        $query.=(!empty($criterios['datacredito'])) ? "AND ma.datacredito = '{$criterios['datacredito']}'" : "";
        $query.=(!empty($criterios['juridico'])) ? "AND ma.juridico = '{$criterios['juridico']}'" : "";
        $query.=(!empty($criterios['sede'])) ? "AND ma.sede = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND ma.estado = '{$criterios['estado']}'" : "";
        $query.=(!empty($criterios['id_alumno'])) ? "AND al.id = '{$criterios['id_alumno']}'" : "";
        // echo $query;
        return $this->db->query($query)->result();
    }
    
    public function matricula_iliquida_responsable($id_responsable, $dni_responsable) {
       $query = "SELECT ma.*, t_p.* "
                . "FROM matricula ma "
                . "JOIN t_plan t_p ON t_p.id=ma.plan  "
                . "where ((ma.estado != '5') AND (ma.pagaran_escalas='1') AND (ma.liquidacion_escalas='0') AND (ma.sede IN(SELECT sede_ppal FROM empleado WHERE (id='" . $id_responsable . "') AND (dni='" . $dni_responsable . "'))) AND ((((SELECT COALESCE(SUM(subtotal), 0) FROM factura WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(valor), 0) FROM descuento_matricula WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(subtotal), 0) FROM recibo_caja WHERE ((matricula=ma.contrato) AND (vigente=1)))+(SELECT COALESCE(SUM(subtotal), 0) FROM abono_matricula WHERE ((matricula=ma.contrato) AND (vigente=1))))-(SELECT COALESCE(SUM(total), 0) FROM nota_credito WHERE ((matricula=ma.contrato) AND (vigente=1))))>=t_p.valor_inicial)) ORDER BY contrato";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }      
    

}
