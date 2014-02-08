<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Proveedorm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_proveedores($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM proveedor pr
                  JOIN t_dni td ON pr.dni=td.id
                  JOIN pais pa ON pr.pais=pa.id
                  LEFT JOIN provincia pro ON pr.provincia=pro.id
                  LEFT JOIN ciudad ciu ON pr.ciudad=ciu.id
                  LEFT JOIN t_domicilio tdom ON pr.t_domicilio=tdom.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND pr.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND pr.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['reazon_social'])) ? "AND lower(a.reazon_social) LIKE '%" . strtolower($criterios['reazon_social']) . "%'" : " ";
        $query.=(!empty($criterios['pais'])) ? "AND pr.pais = '{$criterios['pais']}'" : "";
        $query.=(!empty($criterios['departamento'])) ? "AND pr.provincia = '{$criterios['departamento']}'" : "";
        $query.=(!empty($criterios['ciudad'])) ? "AND pr.ciudad = '{$criterios['ciudad']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_proveedores($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT pr.*,pr.id documento,td.*,pa.nombre pais,pro.nombre provincia,ciu.nombre ciudad,
                  tdom.tipo tipo_domicilio
                  FROM proveedor pr
                   JOIN t_dni td ON pr.dni=td.id
                  JOIN pais pa ON pr.pais=pa.id
                  LEFT JOIN provincia pro ON pr.provincia=pro.id
                  LEFT JOIN ciudad ciu ON pr.ciudad=ciu.id
                  LEFT JOIN t_domicilio tdom ON pr.t_domicilio=tdom.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND pr.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND pr.id = '{$criterios['numero_documento']}'" : "";
        $query.=(!empty($criterios['reazon_social'])) ? "AND lower(a.reazon_social) LIKE '%" . strtolower($criterios['reazon_social']) . "%'" : " ";
        $query.=(!empty($criterios['pais'])) ? "AND pr.pais = '{$criterios['pais']}'" : "";
        $query.=(!empty($criterios['departamento'])) ? "AND pr.provincia = '{$criterios['departamento']}'" : "";
        $query.=(!empty($criterios['ciudad'])) ? "AND pr.ciudad = '{$criterios['ciudad']}'" : "";
        $query.=" order by pr.razon_social LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
