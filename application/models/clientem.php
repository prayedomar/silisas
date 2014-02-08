<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Clientem extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_clientes($criterios, $inicio, $filasPorPagina) {
        $query ="SELECT count(*) cantidad
                  FROM cliente c
                  JOIN t_dni td ON c.dni=td.id
                  JOIN pais pa ON c.pais=pa.id
                  JOIN provincia pro ON c.provincia=pro.id
                  JOIN ciudad ciu ON c.ciudad=ciu.id
                  JOIN t_domicilio tdom ON c.t_domicilio=tdom.id
                  JOIN sede s ON c.sede_ppal=s.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND c.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND c.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(c.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(c.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(c.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(c.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND c.fecha_nacimiento = '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(isset($criterios['estado']) && $criterios['estado'] == "c") ? "AND (SELECT count(*) FROM prestamo pr 
                                                                                 WHERE pr.id_beneficiario=c.id AND
                                                                                 pr.dni_beneficiario=c.dni AND
                                                                                (pr.estado=1 OR pr.estado=2) ) = 1" : "";
        $query.=(isset($criterios['estado']) && $criterios['estado'] == "s") ? "AND (SELECT count(*) FROM prestamo pr 
                                                                                WHERE pr.id_beneficiario=c.id AND
                                                                                pr.dni_beneficiario=c.dni AND
                                                                                (pr.estado=1 OR pr.estado=2) ) = 0" : "";
        $query.=(isset($criterios['sede_ppal'])) ? "AND c.sede_ppal = '{$criterios['sede_ppal']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_clientes($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT c.*,c.id documento,td.*,pa.nombre pais,pro.nombre provincia,ciu.nombre ciudad,
                  s.nombre sede,tdom.tipo tipo_domicilio,(SELECT count(*) FROM prestamo pr 
                                                                                 WHERE pr.id_beneficiario=c.id AND
                                                                                 pr.dni_beneficiario=c.dni AND
                                                                                (pr.estado=1 OR pr.estado=2) ) cantidad_prestamos
                  FROM cliente c
                  JOIN t_dni td ON c.dni=td.id
                  JOIN pais pa ON c.pais=pa.id
                  JOIN provincia pro ON c.provincia=pro.id
                  JOIN ciudad ciu ON c.ciudad=ciu.id
                  JOIN t_domicilio tdom ON c.t_domicilio=tdom.id
                  JOIN sede s ON c.sede_ppal=s.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND c.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND c.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['primer_nombre'])) ? "AND lower(c.nombre1) LIKE '%" . strtolower($criterios['primer_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_nombre'])) ? "AND lower(c.nombre2) LIKE '%" . strtolower($criterios['segundo_nombre']) . "%'" : " ";
        $query.=(!empty($criterios['primer_apellido'])) ? "AND lower(c.apellido1) LIKE '%" . strtolower($criterios['primer_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['segundo_apellido'])) ? "AND lower(c.apellido2) LIKE '%" . strtolower($criterios['segundo_apellido']) . "%'" : " ";
        $query.=(!empty($criterios['fecha_nacimiento'])) ? "AND c.fecha_nacimiento = '{$criterios['fecha_nacimiento']}'" : "";
        $query.=(isset($criterios['estado']) && $criterios['estado'] == "c") ? "AND (SELECT count(*) FROM prestamo pr 
                                                                                 WHERE pr.id_beneficiario=c.id AND
                                                                                 pr.dni_beneficiario=c.dni AND
                                                                                (pr.estado=1 OR pr.estado=2) ) = 1" : "";
        $query.=(isset($criterios['estado']) && $criterios['estado'] == "s") ? "AND (SELECT count(*) FROM prestamo pr 
                                                                                WHERE pr.id_beneficiario=c.id AND
                                                                                pr.dni_beneficiario=c.dni AND
                                                                                (pr.estado=1 OR pr.estado=2) ) = 0" : "";
        $query.=(isset($criterios['sede_ppal'])) ? "AND c.sede_ppal = '{$criterios['sede_ppal']}'" : "";
        $query.=" order by c.apellido1,c.apellido2 LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
