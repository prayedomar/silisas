<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Cod_autorizacionm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cod_autorizacion_id($id) {
        $SqlInfo = "SELECT * "
                . "FROM cod_autorizacion "
                . "where (id='" . $id . "')";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function cod_autorizacion_id_vigente($id) {
        $SqlInfo = "SELECT * "
                . "FROM cod_autorizacion "
                . "where ((vigente='1') AND (id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function cod_autorizacion_id_vigente_tabla($id, $tabla_autorizada) {
        $SqlInfo = "SELECT * "
                . "FROM cod_autorizacion "
                . "where ((vigente='1') AND (id='" . $id . "') AND (tabla_autorizada='" . $tabla_autorizada . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function cod_autorizacion_id_vigente_registro($id, $registro_autorizado) {
        $SqlInfo = "SELECT * "
                . "FROM cod_autorizacion "
                . "where ((vigente='1') AND (id='" . $id . "') AND (registro_autorizado='" . $registro_autorizado . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function cod_autorizacion_id_vigente_empleado_autorizado($id) {
        $SqlInfo = "SELECT * "
                . "FROM cod_autorizacion "
                . "where ((vigente='1') AND (id='" . $id . "') AND (id_empleado_autorizado='" . $_SESSION['idResponsable'] . "') AND (dni_empleado_autorizado='" . $_SESSION['dniResponsable'] . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function cantidad_cod_criterios($criterios) {
        $query = "SELECT count(*) cantidad 
                  FROM cod_autorizacion  
                  WHERE true ";
        $query.=(!empty($criterios['id'])) ? "AND id = '{$criterios['id']}'" : "";
        $query.=(!empty($criterios['tabla_autorizada'])) ? "AND tabla_autorizada = '{$criterios['tabla_autorizada']}'" : "";
        $query.=(!empty($criterios['id_empleado_autorizado'])) ? "AND id_empleado_autorizado = '{$criterios['id_empleado_autorizado']}'" : "";
        $query.=(!empty($criterios['id_responsable'])) ? "AND id_responsable = '{$criterios['id_responsable']}'" : "";
        $query.=(!empty($criterios['vigente'])) ? "AND vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_cod_criterios($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT c_a.*, t_a.nombre nombre_permiso , CONCAT(e_a.nombre1, ' ', e_a.apellido1) autorizado, CONCAT(e_r.nombre1, ' ', e_r.apellido1) responsable   
                  FROM cod_autorizacion c_a
                  JOIN empleado e_a ON ((c_a.id_empleado_autorizado = e_a.id) and (c_a.dni_empleado_autorizado = e_a.dni)) 
                  JOIN empleado e_r ON ((c_a.id_responsable = e_r.id) and (c_a.dni_responsable = e_r.dni)) 
                  JOIN tabla_autorizacion t_a on t_a.id=c_a.tabla_autorizada 
                  WHERE true ";
        $query.=(!empty($criterios['id'])) ? "AND c_a.id = '{$criterios['id']}'" : "";
        $query.=(!empty($criterios['tabla_autorizada'])) ? "AND c_a.tabla_autorizada = '{$criterios['tabla_autorizada']}'" : "";
        $query.=(!empty($criterios['id_empleado_autorizado'])) ? "AND c_a.id_empleado_autorizado = '{$criterios['id_empleado_autorizado']}'" : "";
        $query.=(!empty($criterios['id_responsable'])) ? "AND c_a.id_responsable = '{$criterios['id_responsable']}'" : "";
        $query.=(isset($criterios['vigente']) && $criterios['vigente'] != "") ? "AND c_a.vigente = '{$criterios['vigente']}'" : "";
        $query.=" order by c_a.id DESC LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

    public function cantidad_cod_pdte_responsable() {
        $query = "SELECT count(*) cantidad "
                . "FROM cod_autorizacion "
                . "where ((id_empleado_autorizado='{$_SESSION["idResponsable"]}') AND (dni_empleado_autorizado='{$_SESSION["dniResponsable"]}') AND (vigente=1))";
        return $this->db->query($query)->result();
    }

    public function cod_pdte_responsable_rapida() {
        $query = "SELECT id "
                . "FROM cod_autorizacion "
                . "where ((id_empleado_autorizado='{$_SESSION["idResponsable"]}') AND (dni_empleado_autorizado='{$_SESSION["dniResponsable"]}') AND (vigente=1))";
        return $this->db->query($query)->result();
    }

}
