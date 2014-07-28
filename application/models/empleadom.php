<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Empleadom extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function empleado_id_dni($id, $dni) {
        $SqlInfo = "SELECT e.*, CONCAT(e.nombre1, ' ', e.nombre2, ' ', e.apellido1, ' ', e.apellido2) nombres "
                . "FROM empleado e "
                . "where ((e.id='" . $id . "') AND (e.dni='" . $dni . "'))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function empleados_rrpp_concepto_pdte_responsable() {
        $query = "SELECT e.*, CONCAT(e.nombre1, ' ', e.nombre2, ' ', e.apellido1, ' ', e.apellido2) nombres "
                . "FROM empleado e "
                . "where ((e.id IN(SELECT id_empleado FROM concepto_nomina WHERE (estado=2))) AND (e.dni IN(SELECT dni_empleado FROM concepto_nomina WHERE (estado=2)))AND((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE (id='" . $_SESSION['idResponsable'] . "') AND (dni='" . $_SESSION['dniResponsable'] . "'))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $_SESSION['idResponsable'] . "') AND (dni_empleado='" . $_SESSION['dniResponsable'] . "') AND (vigente=1)))) AND ( NOT(id='1' AND dni='1'))) ORDER BY nombres";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }
    
    public function empleados_rrpp_responsable() {
        $query = "SELECT e.*, CONCAT(e.nombre1, ' ', e.nombre2, ' ', e.apellido1, ' ', e.apellido2) nombres "
                . "FROM empleado e "
                . "where ((e.depto='3')AND(e.estado!='3'AND((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE (id='" . $_SESSION['idResponsable'] . "') AND (dni='" . $_SESSION['dniResponsable'] . "'))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $_SESSION['idResponsable'] . "') AND (dni_empleado='" . $_SESSION['dniResponsable'] . "') AND (vigente=1)))) AND ( NOT(id='1' AND dni='1')))) ORDER BY nombres";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }    
    
    public function empleados_administracion_responsable() {
        $query = "SELECT e.*, CONCAT(e.nombre1, ' ', e.nombre2, ' ', e.apellido1, ' ', e.apellido2) nombres "
                . "FROM empleado e "
                . "where ((e.depto!='3')AND(e.estado!='3')AND((sede_ppal IN(SELECT sede_ppal FROM empleado WHERE (id='" . $_SESSION['idResponsable'] . "') AND (dni='" . $_SESSION['dniResponsable'] . "'))) OR (sede_ppal IN(SELECT sede_secundaria FROM empleado_x_sede WHERE (id_empleado='" . $_SESSION['idResponsable'] . "') AND (dni_empleado='" . $_SESSION['dniResponsable'] . "') AND (vigente=1)))) AND ( NOT(id='1' AND dni='1'))) ORDER BY nombres";
        if ($this->db->query($query)->num_rows() > 0) {
            return $this->db->query($query)->result();
        }
    }     

    public function jefe_de_empleado($id_subdito, $dni_subdito) {
        $SqlInfo = "SELECT jefe.*, CONCAT(jefe.nombre1, ' ', jefe.nombre2, ' ', jefe.apellido1, ' ', jefe.apellido2) nombres "
                . "FROM empleado jefe "
                . "JOIN empleado e "
                . "where ((e.id='" . $id_subdito . "') AND (e.dni='" . $dni_subdito . "') AND (jefe.id=e.id_jefe) AND (jefe.dni=e.dni_jefe))";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function cantidad_empleados($criterios, $inicio, $filasPorPagina) {
        $id_responsable = $_SESSION["idResponsable"];
        $dni_responsable = $_SESSION["dniResponsable"];
        $query = "SELECT count(*) cantidad 
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN sede s ON e.sede_ppal=s.id
                  JOIN pais pa ON e.pais=pa.id
                  JOIN provincia pro ON e.provincia=pro.id
                  JOIN ciudad ciu ON e.ciudad=ciu.id
                  JOIN t_domicilio tdom ON e.t_domicilio=tdom.id
                  JOIN est_empleado estem ON e.estado=estem.id
                  JOIN t_depto tdepto ON e.depto=tdepto.id
                  JOIN t_cargo tcargo ON e.cargo=tcargo.id
                  JOIN salario sl ON e.salario=sl.id
                  JOIN empleado e2 ON e.id_jefe=e2.id AND e.dni_jefe=e2.dni
                  WHERE (NOT(e.id='1' AND e.dni='1')) AND e.sede_ppal IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='$id_responsable' AND dni='$dni_responsable' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='$id_responsable' AND dni_empleado='$dni_responsable' AND vigente=1) as T1) ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['estado'])) ? "AND e.estado = '{$criterios['estado']}'" : "";
        $query.=(!empty($criterios['sede'])) ? "AND e.sede_ppal = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['depto'])) ? "AND e.depto = '{$criterios['depto']}'" : "";
        $query.=(!empty($criterios['cargo'])) ? "AND e.cargo = '{$criterios['cargo']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(e.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(e.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_empleados($criterios, $inicio, $filasPorPagina) {
        $id_responsable = $_SESSION["idResponsable"];
        $dni_responsable = $_SESSION["dniResponsable"];
        $query = "SELECT c_l.t_contrato, c_l.fecha_inicio, c_l.fecha_fin, e.*,CONCAT(e.id_jefe, '-', e.dni_jefe) jefe_id_dni, e.id documento,td.*,s.nombre sede,pa.id id_pais,pa.nombre pais,pro.id id_provincia,pro.nombre provincia,ciu.id id_ciudad,ciu.nombre ciudad,
                  tdom.tipo tipo_domicilio,estem.estado estado_empleado,tdepto.id id_depto,tdepto.tipo depto,
                  tcargo.cargo_masculino,tcargo.cargo_femenino,sl.nombre nombre_salario,
                  e2.nombre1 nombre1_jefe,e2.nombre2 nombre2_jefe,e2.apellido1 apellido1_jefe,e2.apellido2 apellido2_jefe
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN sede s ON e.sede_ppal=s.id
                  JOIN pais pa ON e.pais=pa.id
                  JOIN provincia pro ON e.provincia=pro.id
                  JOIN ciudad ciu ON e.ciudad=ciu.id
                  JOIN t_domicilio tdom ON e.t_domicilio=tdom.id
                  JOIN est_empleado estem ON e.estado=estem.id
                  JOIN t_depto tdepto ON e.depto=tdepto.id
                  JOIN t_cargo tcargo ON e.cargo=tcargo.id
                  JOIN salario sl ON e.salario=sl.id
                  JOIN contrato_laboral c_l ON ((c_l.id_empleado=e.id) AND (c_l.dni_empleado=e.dni)) 
                  JOIN empleado e2 ON e.id_jefe=e2.id AND e.dni_jefe=e2.dni
                   WHERE (NOT(e.id='1' AND e.dni='1')) AND e.sede_ppal IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='$id_responsable' AND dni='$dni_responsable' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='$id_responsable' AND dni_empleado='$dni_responsable' AND vigente=1) as T1) ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['estado'])) ? "AND e.estado = '{$criterios['estado']}'" : "";
        $query.=(!empty($criterios['sede'])) ? "AND e.sede_ppal = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['depto'])) ? "AND e.depto = '{$criterios['depto']}'" : "";
        $query.=(!empty($criterios['cargo'])) ? "AND e.cargo = '{$criterios['cargo']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(e.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(e.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        $query.=" order by e.apellido1,e.apellido2 LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

    public function listar_empleados_excel($criterios) {
        $id_responsable = $_SESSION["idResponsable"];
        $dni_responsable = $_SESSION["dniResponsable"];
        $query = "SELECT c_l.t_contrato, c_l.fecha_inicio, c_l.fecha_fin, e.*,CONCAT(e.id_jefe, '-', e.dni_jefe) jefe_id_dni, e.id documento,td.*,s.nombre sede,pa.id id_pais,pa.nombre pais,pro.id id_provincia,pro.nombre provincia,ciu.id id_ciudad,ciu.nombre ciudad,
                  tdom.tipo tipo_domicilio,estem.estado estado_empleado,tdepto.id id_depto,tdepto.tipo depto,
                  tcargo.cargo_masculino,tcargo.cargo_femenino,sl.nombre nombre_salario,
                  e2.nombre1 nombre1_jefe,e2.nombre2 nombre2_jefe,e2.apellido1 apellido1_jefe,e2.apellido2 apellido2_jefe
                  FROM empleado e
                  JOIN t_dni td ON e.dni=td.id
                  JOIN sede s ON e.sede_ppal=s.id
                  JOIN pais pa ON e.pais=pa.id
                  JOIN provincia pro ON e.provincia=pro.id
                  JOIN ciudad ciu ON e.ciudad=ciu.id
                  JOIN t_domicilio tdom ON e.t_domicilio=tdom.id
                  JOIN est_empleado estem ON e.estado=estem.id
                  JOIN t_depto tdepto ON e.depto=tdepto.id
                  JOIN t_cargo tcargo ON e.cargo=tcargo.id
                  JOIN salario sl ON e.salario=sl.id
                  JOIN contrato_laboral c_l ON ((c_l.id_empleado=e.id) AND (c_l.dni_empleado=e.dni)) 
                  JOIN empleado e2 ON e.id_jefe=e2.id AND e.dni_jefe=e2.dni
                   WHERE (NOT(e.id='1' AND e.dni='1')) AND e.sede_ppal IN (SELECT DISTINCT sede_ppal FROM (SELECT sede_ppal FROM empleado WHERE id='$id_responsable' AND dni='$dni_responsable' UNION SELECT sede_secundaria FROM empleado_x_sede WHERE id_empleado='$id_responsable' AND dni_empleado='$dni_responsable' AND vigente=1) as T1) ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND e.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND e.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(e.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(e.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(e.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(e.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['estado'])) ? "AND e.estado = '{$criterios['estado']}'" : "";
        $query.=(!empty($criterios['sede'])) ? "AND e.sede_ppal = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['depto'])) ? "AND e.depto = '{$criterios['depto']}'" : "";
        $query.=(!empty($criterios['cargo'])) ? "AND e.cargo = '{$criterios['cargo']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(e.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(e.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        $query.=" order by e.apellido1,e.apellido2";
        return $this->db->query($query)->result();
    }

    public function actualizarEmpleado($criterios) {
        $this->load->model('t_cargom');
        list($id_jefe, $dni_jefe) = explode("-", $criterios["jefe"]);
        if ($criterios["t_contrato"] != "1") {
            $fecha_fin = $criterios["fecha_fin"];
        } else {
            $fecha_fin = NULL;
        }
        $perfil = $this->t_cargom->t_cargo_id($criterios["cargo"])->perfil;
        if ($criterios["id_old"] != $criterios["id_new"]) {
            $this->load->model('update_model');
            $password = $this->encrypt->encode($criterios["id_new"]); //Encriptamos el numero de identificacion  
            $this->update_model->cambiar_contraseña($criterios["id_old"], $criterios["dni_old"], '1', $password);
        }

        $query = "UPDATE usuario SET id='{$criterios["id_new"]}', dni='{$criterios["dni_new"]}', nombres='" . ucwords(mb_strtolower($criterios["nombre1"])) . " " . ucwords(mb_strtolower($criterios["nombre2"])) . "',genero='{$criterios["genero"]}', email='" . mb_strtolower($criterios["email"]) . "', perfil='" . $perfil . "' where id='{$criterios["id_old"]}' and dni='{$criterios["dni_old"]}' and t_usuario= 1 ";
        $this->db->query($query);

        $query = "UPDATE empleado SET nombre1='" . ucwords(mb_strtolower($criterios["nombre1"])) . "',nombre2='" . ucwords(mb_strtolower($criterios["nombre2"])) . "',apellido1='" . ucwords(mb_strtolower($criterios["apellido1"])) . "',apellido2='" . ucwords(mb_strtolower($criterios["apellido2"])) . "', fecha_nacimiento='{$criterios["fecha_nacimiento"]}',genero='{$criterios["genero"]}', est_civil='{$criterios["est_civil"]}',pais='{$criterios["pais"]}',provincia='{$criterios["provincia"]}',ciudad='{$criterios["ciudad"]}', t_domicilio='{$criterios["t_domicilio"]}', direccion='" . ucwords(mb_strtolower($criterios["direccion"])) . "', barrio='" . ucwords(mb_strtolower($criterios["barrio"])) . "', telefono='{$criterios["telefono"]}', celular='{$criterios["celular"]}', email='" . strtolower($criterios["email"]) . "', cuenta='{$criterios["cuenta"]}', sede_ppal='{$criterios["sede_ppal"]}', fecha_ingreso='{$criterios["fecha_ingreso"]}', depto='{$criterios["depto"]}' , cargo='{$criterios["cargo"]}' , salario='{$criterios["salario"]}' , id_jefe='{$id_jefe}' , dni_jefe='{$dni_jefe}' , observacion='" . ucwords(mb_strtolower($criterios["observacion"])) . "' where id='{$criterios["id_new"]}' and dni='{$criterios["dni_new"]}' ";
        $this->db->query($query);

        $query = "UPDATE contrato_laboral SET t_contrato='{$criterios["t_contrato"]}', fecha_inicio='{$criterios["fecha_inicio"]}', fecha_fin='{$fecha_fin}', observacion='" . ucwords(mb_strtolower($criterios["observacion"])) . "' where id_empleado='{$criterios["id_new"]}' and dni_empleado='{$criterios["dni_new"]}' ";
        return $this->db->query($query);
    }

}
