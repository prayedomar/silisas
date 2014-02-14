<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Llamado_atencionm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_llamados_atencion($criterios, $inicio, $filasPorPagina) {
        $id_responsable = $_SESSION["idResponsable"];
        $dni_responsable = $_SESSION["dniResponsable"];
        $query = "SELECT count(*) cantidad
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN llamado_atencion la ON e.dni=la.dni_empleado AND e.id=la.id_empleado
                  JOIN t_sancion ts ON la.t_sancion=ts.id
                  JOIN t_falta_laboral tfl ON la.t_falta_laboral=tfl.id
                   JOIN empleado e2 ON e2.dni=la.dni_responsable AND e2.id=la.id_responsable
                   WHERE e.sede_ppal IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='$id_responsable' AND dni='$dni_responsable' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='$id_responsable' AND dni_empleado='$dni_responsable' AND vigente=1) as T1) ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['tipo_sancion'])) ? "AND la.t_sancion = '{$criterios['tipo_sancion']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND la.vigente = '{$criterios['vigente']}'" : "";
          $query.=(!empty($criterios['sede'])) ? "AND e.sede_ppal = '{$criterios['sede']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_llamados_atencion($criterios, $inicio, $filasPorPagina) {
        $id_responsable = $_SESSION["idResponsable"];
        $dni_responsable = $_SESSION["dniResponsable"];
        $query = "SELECT e.*,e.id documento,td.*,la.*,ts.tipo llamado,tfl.falta,
                   e2.nombre1 nom1_resposable,e2.nombre2 nom2_resposable,e2.apellido1 apell1_resposable,e2.apellido2 apell2_resposable
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN llamado_atencion la ON e.dni=la.dni_empleado AND e.id=la.id_empleado
                  JOIN t_sancion ts ON la.t_sancion=ts.id
                  JOIN t_falta_laboral tfl ON la.t_falta_laboral=tfl.id
                   JOIN empleado e2 ON e2.dni=la.dni_responsable AND e2.id=la.id_responsable
                   WHERE e.sede_ppal IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='$id_responsable' AND dni='$dni_responsable' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='$id_responsable' AND dni_empleado='$dni_responsable' AND vigente=1) as T1) ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['tipo_sancion'])) ? "AND la.t_sancion = '{$criterios['tipo_sancion']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND la.vigente = '{$criterios['vigente']}'" : "";
         $query.=(!empty($criterios['sede'])) ? "AND e.sede_ppal = '{$criterios['sede']}'" : "";
        $query.=" order by e.apellido1,e.apellido2 LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
