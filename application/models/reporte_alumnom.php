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

}
