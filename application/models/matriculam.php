<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Matriculam extends CI_Model {

    public function __construct() {
        parent::__construct();
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
        $query = "SELECT *,t.nombre1,t.nombre2,t.apellido1,t.apellido2,p.nombre nombre_plan,s.nombre nombre_sede,tdni.abreviacion nombre_dni,
                    tc.cargo_masculino,em.nombre1 nom1res,em.nombre2 nom2res,em.apellido1 apell1res,em.apellido2 apell2res,
                    ea.estado nombre_estado,ma.observacion observacion_matricula,td.abreviacion dni_ejecutivo,
                    em2.nombre1 nombre1ejecutivo,em2.nombre2 nombre2ejecutivo,em2.apellido1 apellido1ejecutivo,em2.apellido2 apellido2ejecutivo
                  FROM matricula ma
                  JOIN titular t ON ma.dni_titular=t.dni AND ma.id_titular=t.id
                  JOIN t_plan p ON ma.plan=p.id
                  JOIN t_dni tdni ON ma.dni_titular=tdni.id
                  JOIN sede s ON ma.sede=s.id
                  LEFT JOIN  alumno al ON ma.contrato=al.matricula
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
        $query.=" LIMIT $inicio,$filasPorPagina";
        // echo $query;
        return $this->db->query($query)->result();
    }

    public function listar_matriculas_excel($criterios) {
        $query = "SELECT *,t.nombre1,t.nombre2,t.apellido1,t.apellido2,p.nombre nombre_plan,s.nombre nombre_sede,tdni.abreviacion nombre_dni,
                    tc.cargo_masculino,em.nombre1 nom1res,em.nombre2 nom2res,em.apellido1 apell1res,em.apellido2 apell2res,
                    ea.estado nombre_estado,ma.observacion observacion_matricula,td.abreviacion dni_ejecutivo,
                    em2.nombre1 nombre1ejecutivo,em2.nombre2 nombre2ejecutivo,em2.apellido1 apellido1ejecutivo,em2.apellido2 apellido2ejecutivo
                  FROM matricula ma
                  JOIN titular t ON ma.dni_titular=t.dni AND ma.id_titular=t.id
                  JOIN t_plan p ON ma.plan=p.id
                  JOIN t_dni tdni ON ma.dni_titular=tdni.id
                  JOIN sede s ON ma.sede=s.id
                  LEFT JOIN  alumno al ON ma.contrato=al.matricula
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

}
