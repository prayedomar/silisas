<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Reporte_alumnom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function reporte_alumno($id_alumno, $dni_alumno) {
        $query = "SELECT r_a.*, CASE r_a.asistencia WHEN '1' THEN 'Si' ELSE 'No' END asistio, CONCAT(re.nombre1, ' ', re.apellido1) responsable 
                  FROM reporte_alumno r_a 
                  JOIN empleado re ON ((r_a.id_responsable = re.id) and (r_a.dni_responsable = re.dni)) 
                  WHERE ((r_a.id_alumno='$id_alumno') AND (r_a.dni_alumno='$dni_alumno')) ORDER BY r_a.fecha_clase";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }

    public function cantidad_reportes($criterios) {
        $query = "SELECT count(*) cantidad 
                  FROM reporte_alumno r_a 
                  WHERE vigente='1' ";
        $query.=(!empty($criterios['desde'])) ? "AND r_a.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND r_a.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND r_a.sede= '{$criterios['sede']}' " : "";
        $query.=(!empty($criterios['id_alumno'])) ? "AND r_a.id_alumno = '{$criterios['id_alumno']}'" : "";
        $query.=(!empty($criterios['id_responsable'])) ? "AND r_a.id_responsable = '{$criterios['id_responsable']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_reportes($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT r_a.*, CONCAT(al.nombre1, ' ', al.nombre2, ' ', al.apellido1) alumno, CONCAT(e_r.nombre1, ' ', e_r.apellido1) responsable,s.nombre nombre_sede   
                  FROM reporte_alumno r_a 
                  JOIN sede s ON r_a.sede=s.id                   
                  JOIN alumno al ON ((r_a.id_alumno = al.id) and (r_a.dni_alumno = al.dni)) 
                  JOIN empleado e_r ON ((r_a.id_responsable = e_r.id) and (r_a.dni_responsable = e_r.dni)) 
                  WHERE vigente='1' ";
        $query.=(!empty($criterios['desde'])) ? "AND r_a.fecha_trans >='{$criterios['desde']} 00:00:00' " : "";
        $query.=(!empty($criterios['hasta'])) ? "AND r_a.fecha_trans <= '{$criterios['hasta']} 23:59:59' " : "";
        $query.=(isset($criterios['sede'])) ? "AND r_a.sede= '{$criterios['sede']}' " : "";
        $query.=(!empty($criterios['id_alumno'])) ? "AND r_a.id_alumno = '{$criterios['id_alumno']}'" : "";
        $query.=(!empty($criterios['id_responsable'])) ? "AND r_a.id_responsable = '{$criterios['id_responsable']}'" : "";
        $query.=" ORDER BY r_a.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
