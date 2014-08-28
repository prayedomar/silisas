<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Titularm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_salones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM sede s
                  JOIN salon sa on s.id=sa.sede
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(sa.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['sede'])) ? "AND s.id = '{$criterios['sede']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND sa.vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }
    
    public function titular_matricula($matricula) {
        $SqlInfo = "SELECT t.* "
                . "FROM titular t "
                . "JOIN matricula ma ON ((ma.id_titular=t.id) AND (ma.dni_titular=t.dni)) "
                . "where (ma.contrato='" . $matricula . "')";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }      

    public function listar_salones($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT sa.*,s.nombre sede 
                  FROM sede s
                  JOIN salon sa on s.id=sa.sede
                  WHERE true ";
        $query.=(!empty($criterios['nombre'])) ? "AND lower(sa.nombre) LIKE '%" . strtolower($criterios['nombre']) . "%'" : " ";
        $query.=(!empty($criterios['sede'])) ? "AND s.id = '{$criterios['sede']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND sa.vigente = '{$criterios['vigente']}'" : "";
        $query.=" order by sa.nombre LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

    public function cantidad_titulares($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM titular t
                  JOIN t_dni td ON td.id=t.dni
                  JOIN pais pa ON t.pais=pa.id
                  JOIN provincia pro ON t.provincia=pro.id
                  JOIN ciudad ciu ON t.ciudad=ciu.id
                  JOIN t_domicilio tdom ON t.t_domicilio=tdom.id
                      WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND t.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND t.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(t.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(t.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(t.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(t.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(t.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(t.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND t.vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_titulares($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT t.*,t.id documento,td.tipo,pa.nombre,pa.id id_pais,pa.nombre pais,pro.id id_provincia,pro.nombre provincia,ciu.nombre ciudad,ciu.id id_ciudad,tdom.tipo tipo_domicilio
                  FROM titular t
                  JOIN t_dni td ON td.id=t.dni
                  JOIN pais pa ON t.pais=pa.id
                  JOIN provincia pro ON t.provincia=pro.id
                  JOIN ciudad ciu ON t.ciudad=ciu.id
                  JOIN t_domicilio tdom ON t.t_domicilio=tdom.id
                      WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND t.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND t.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(t.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(t.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(t.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(t.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(t.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(t.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND t.vigente = '{$criterios['vigente']}'" : "";
        $query.=" order by t.fecha_trans DESC LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

    public function listar_titulares_excel($criterios) {
        $query = "SELECT t.*,t.id documento,td.tipo,pa.nombre,pa.nombre pais,pro.nombre provincia,ciu.nombre ciudad,tdom.tipo tipo_domicilio
                  FROM titular t
                  JOIN t_dni td ON td.id=t.dni
                  JOIN pais pa ON t.pais=pa.id
                  JOIN provincia pro ON t.provincia=pro.id
                  JOIN ciudad ciu ON t.ciudad=ciu.id
                  JOIN t_domicilio tdom ON t.t_domicilio=tdom.id
                      WHERE true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND t.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND t.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(t.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(t.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(t.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(t.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND DATE_FORMAT(t.fecha_nacimiento,'%m-%d') >= '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(!empty($criterios['fecha_nacimiento_hasta'])) ? "AND DATE_FORMAT(t.fecha_nacimiento,'%m-%d') <= '{$criterios['fecha_nacimiento_hasta']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND t.vigente = '{$criterios['vigente']}'" : "";
        $query.=" order by t.fecha_trans DESC";
        return $this->db->query($query)->result();
    }

    public function actualizarTitular($criterios) {
        if ($criterios["id_old"] != $criterios["id_new"]) {
            $this->load->model('update_model');
            $password = $this->encrypt->encode($criterios["id_new"]); //Encriptamos el numero de identificacion  
            $this->update_model->cambiar_contraseña($criterios["id_old"], $criterios["dni_old"], '1', $password);
        }       
        $query = "UPDATE usuario SET id='{$criterios["id_new"]}', dni='{$criterios["dni_new"]}', nombres='" . ucwords(mb_strtolower($criterios["nombre1"])) . " " . ucwords(mb_strtolower($criterios["nombre2"])) . "',genero='{$criterios["genero"]}', email='" . mb_strtolower($criterios["email"]) . "' where id='{$criterios["id_old"]}' and dni='{$criterios["dni_old"]}' and t_usuario=2 ";
        return $this->db->query($query);        
        $query = "UPDATE titular SET nombre1='" . ucwords(mb_strtolower($criterios["nombre1"])) . "',nombre2='" . ucwords(mb_strtolower($criterios["nombre2"])) . "',apellido1='" . ucwords(mb_strtolower($criterios["apellido1"])) . "',apellido2='" . ucwords(mb_strtolower($criterios["apellido2"])) . "', fecha_nacimiento='{$criterios["fecha_nacimiento"]}',genero='{$criterios["genero"]}',pais='{$criterios["pais"]}',provincia='{$criterios["provincia"]}',ciudad='{$criterios["ciudad"]}', t_domicilio='{$criterios["t_domicilio"]}', direccion='" . ucwords(mb_strtolower($criterios["direccion"])) . "', barrio='" . ucwords(mb_strtolower($criterios["barrio"])) . "', telefono='{$criterios["telefono"]}', celular='{$criterios["celular"]}', email='" . strtolower($criterios["email"]) . "', observacion='" . strtolower($criterios["observacion"]) . "' where id='{$criterios["id_new"]}' and dni='{$criterios["dni_new"]}' ";
        $this->db->query($query);
    }

}
