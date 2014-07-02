<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Transferenciam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function transferencia_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT tr.*, so.nombre nombre_sede_origen, sd.nombre nombre_sede_destino, CONCAT(em.nombre1, ' ', em.nombre2, ' ', em.apellido1) nombre_remitente, t_ca_o.tipo nombre_caja_origen, t_ca_d.tipo nombre_caja_destino "
                . "FROM transferencia tr "
                . "LEFT JOIN caja ca ON ((tr.sede_caja_destino=ca.sede) AND (tr.t_caja_destino=ca.t_caja)) "
                . "LEFT JOIN t_caja t_ca_o ON tr.t_caja_origen = t_ca_o.id "
                . "LEFT JOIN t_caja t_ca_d ON tr.t_caja_destino = t_ca_d.id "
                . "JOIN sede so ON tr.sede_origen = so.id "
                . "JOIN empleado em ON ((tr.id_responsable = em.id) and (tr.dni_responsable = em.dni)) "
                . "JOIN sede sd ON tr.sede_destino = sd.id "
                . "where ((tr.prefijo='" . $prefijo . "')AND(tr.id='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function aprobar_transferencia_prefijo_id($prefijo, $id) {
        $SqlInfo = "SELECT a_tr.*, CONCAT(em.nombre1, ' ', em.nombre2, ' ', em.apellido1) empleado_autoriza "
                . "FROM aprobar_transferencia a_tr "
                . "JOIN empleado em ON ((a_tr.id_responsable = em.id) and (a_tr.dni_responsable = em.dni)) "
                . "where ((a_tr.prefijo_transferencia='" . $prefijo . "')AND(a_tr.id_transferencia='" . $id . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function transferencia_pdte_responsable($id_responsable, $dni_responsable) {
        $SqlInfo = "SELECT tr.*, so.nombre nombre_sede_origen, sd.nombre nombre_sede_destino, CONCAT(em.nombre1, ' ', em.nombre2, ' ', em.apellido1) nombre_remitente, t_ca_o.tipo nombre_caja_origen, t_ca_d.tipo nombre_caja_destino "
                . "FROM transferencia tr "
                . "LEFT JOIN cuenta cu ON tr.cuenta_destino = cu.id "
                . "LEFT JOIN caja ca ON ((tr.sede_caja_destino=ca.sede) AND (tr.t_caja_destino=ca.t_caja)) "
                . "LEFT JOIN t_caja t_ca_o ON tr.t_caja_origen = t_ca_o.id "
                . "LEFT JOIN t_caja t_ca_d ON tr.t_caja_destino = t_ca_d.id "
                . "JOIN sede so ON tr.sede_origen = so.id "
                . "JOIN empleado em ON ((tr.id_responsable = em.id) and (tr.dni_responsable = em.dni)) "
                . "JOIN sede sd ON tr.sede_destino = sd.id "
                . "where ((((ca.id_encargado='" . $id_responsable . "')  AND (ca.dni_encargado='" . $dni_responsable . "') AND (ca.vigente=1)) OR ((cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='" . $id_responsable . "') AND (dni_encargado='" . $dni_responsable . "') AND (permiso_consultar=1)))))) AND (tr.est_traslado=2)) ORDER BY tr.fecha_trans";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function transferencia_pdte_responsable_rapida() {
        $query = "SELECT tr.* "
                . "FROM transferencia tr "
                . "LEFT JOIN cuenta cu ON tr.cuenta_destino = cu.id "
                . "LEFT JOIN caja ca ON ((tr.sede_caja_destino=ca.sede) AND (tr.t_caja_destino=ca.t_caja)) "
                . "where ((((ca.id_encargado='{$_SESSION["idResponsable"]}')  AND (ca.dni_encargado='{$_SESSION["dniResponsable"]}') AND (ca.vigente=1)) OR ((cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='{$_SESSION["idResponsable"]}') AND (dni_encargado='{$_SESSION["dniResponsable"]}') AND (permiso_consultar=1)))))) AND (tr.est_traslado=2)) ORDER BY tr.fecha_trans";
        return $this->db->query($query)->result();
    }

    public function cantidad_transferencia_pdte_responsable_rapida() {
        $query = "SELECT count(*) cantidad "
                . "FROM transferencia tr "
                . "LEFT JOIN cuenta cu ON tr.cuenta_destino = cu.id "
                . "LEFT JOIN caja ca ON ((tr.sede_caja_destino=ca.sede) AND (tr.t_caja_destino=ca.t_caja)) "
                . "where ((((ca.id_encargado='{$_SESSION["idResponsable"]}')  AND (ca.dni_encargado='{$_SESSION["dniResponsable"]}') AND (ca.vigente=1)) OR ((cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='{$_SESSION["idResponsable"]}') AND (dni_encargado='{$_SESSION["dniResponsable"]}') AND (permiso_consultar=1)))))) AND (tr.est_traslado=2))";
        return $this->db->query($query)->result();
    }

    //SIrve para saber si una transferencia esta autorizada para un usuario con perfiles: directivo, admon_sede, auxiliar_admon_sede
    //Esta autorizado si la sede de origen o destino pertenece a cualquiera de las sedes autorizadas del responsable
    public function transferencia_aurotorizada_directivos($prefijo, $id) {
        $query = "SELECT count(*) cantidad "
                . "FROM transferencia tr "
                . "where ((tr.prefijo='" . $prefijo . "')AND(tr.id='" . $id . "')AND((tr.sede_origen IN(SELECT sede_ppal FROM empleado WHERE (id='{$_SESSION["idResponsable"]}') AND (dni='{$_SESSION["dniResponsable"]}'))) OR (tr.sede_origen IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='{$_SESSION["idResponsable"]}') AND (dni_empleado='{$_SESSION["dniResponsable"]}') AND (vigente=1))) OR (tr.sede_destino IN(SELECT sede_ppal FROM empleado WHERE (id='{$_SESSION["idResponsable"]}') AND (dni='{$_SESSION["dniResponsable"]}'))) OR (tr.sede_destino IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='{$_SESSION["idResponsable"]}') AND (dni_empleado='{$_SESSION["dniResponsable"]}') AND (vigente=1)))))";
        return $this->db->query($query)->result();
    }

    //SIrve para saber si una transferencia esta autorizada para un usuario con perfiles: cartera, secretaria
    //Esta autorizado si la Transferencia realizadas por el responsable o enviadas hacía su caja autorizada o hacía una cuenta bancaria autorizada para usted consultarla.   
    public function transferencia_aurotorizada_empleados($prefijo, $id) {
        $query = "SELECT count(*) cantidad "
                . "FROM transferencia tr "
                . "LEFT JOIN cuenta cu ON tr.cuenta_destino = cu.id "
                . "LEFT JOIN caja ca ON ((tr.sede_caja_destino=ca.sede) AND (tr.t_caja_destino=ca.t_caja)) "
                . "where (((tr.prefijo='" . $prefijo . "')AND(tr.id='" . $id . "')) AND (((tr.id_responsable='{$_SESSION["idResponsable"]}')AND(tr.dni_responsable='{$_SESSION["idResponsable"]}')) OR ((ca.id_encargado='{$_SESSION["idResponsable"]}') AND (ca.dni_encargado='{$_SESSION["dniResponsable"]}') AND (ca.vigente=1)) OR ((cu.id IN (SELECT cuenta FROM cuenta_x_sede_x_empleado WHERE ((id_encargado='{$_SESSION["idResponsable"]}') AND (dni_encargado='{$_SESSION["dniResponsable"]}') AND (permiso_consultar=1)))))))";
        return $this->db->query($query)->result();
    }

}
