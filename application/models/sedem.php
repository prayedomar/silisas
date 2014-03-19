<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Sedem extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_las_sedes() {
        $id_responsable = $_SESSION["idResponsable"];
        $dni_responsable = $_SESSION["dniResponsable"];
        $query = "SELECT * FROM sede WHERE id IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='$id_responsable' AND dni='$dni_responsable' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='$id_responsable' AND dni_empleado='$dni_responsable' AND vigente=1) as T1) order by nombre ";
        return $this->db->query($query)->result();
    }

    public function listar_todas_las_sedes_sin_resposanble() {
        $query = "SELECT * FROM sede";
        return $this->db->query($query)->result();
    }

    public function cantidadSedes($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM sede s
                  JOIN pais p ON p.id=s.pais
                  JOIN provincia pr ON pr.id=s.provincia
                  JOIN ciudad c ON c.id=s.ciudad
                  JOIN est_sede es ON es.id=s.estado
                  JOIN empleado em ON em.id=s.id_responsable AND em.dni=s.dni_responsable
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(s.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['pais'])) ? "AND s.pais = '{$criterios['pais']}'" : "";
        $query.=(!empty($criterios['departamento'])) ? "AND s.provincia = '{$criterios['departamento']}'" : "";
        $query.=(!empty($criterios['ciudad'])) ? "AND s.ciudad = '{$criterios['ciudad']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND s.estado = '{$criterios['estado']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_sedes($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT s.*,p.id id_pais,pr.id id_departamento,c.id id_ciudad,es.id id_estado, p.nombre pais,pr.nombre departamento,c.nombre ciudad,es.estado,em.nombre1,em.nombre2,em.apellido1,em.apellido2
                  FROM sede s
                  JOIN pais p ON p.id=s.pais
                  JOIN provincia pr ON pr.id=s.provincia
                  JOIN ciudad c ON c.id=s.ciudad
                  JOIN est_sede es ON es.id=s.estado
                  JOIN empleado em ON em.id=s.id_responsable AND em.dni=s.dni_responsable
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(s.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['pais'])) ? "AND s.pais = '{$criterios['pais']}'" : "";
        $query.=(!empty($criterios['departamento'])) ? "AND s.provincia = '{$criterios['departamento']}'" : "";
        $query.=(!empty($criterios['ciudad'])) ? "AND s.ciudad = '{$criterios['ciudad']}'" : "";
        $query.=(!empty($criterios['estado'])) ? "AND s.estado = '{$criterios['estado']}'" : "";
        $query.=" order by s.nombre LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

    public function actualizarSede($id_sede, $nombre, $pais, $departamento, $ciudad, $estado, $direccion, $tel1, $tel2, $observacion) {
        $query = "UPDATE sede SET nombre='$nombre',pais='$pais',provincia='$departamento',ciudad='$ciudad',direccion='$direccion',tel1='$tel1',tel2='$tel2',estado='$estado',observacion='$observacion',id_responsable='{$_SESSION["idResponsable"]}',dni_responsable='{$_SESSION["dniResponsable"]}',fecha_trans=now() WHERE id='$id_sede'";
        return $this->db->query($query);
    }

}
