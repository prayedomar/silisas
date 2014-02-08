<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Cajam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_cajas($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM caja c
                  JOIN sede s ON c.sede=s.id
                  JOIN t_caja tc ON c.t_caja=tc.id
                  JOIN t_dni td ON c.dni_encargado=td.id
                  JOIN empleado e ON c.dni_encargado=e.dni AND c.id_encargado=e.id
                  WHERE true ";
        $query.=(!empty($criterios['sede'])) ? "AND c.sede = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['caja'])) ? "AND c.t_caja = '{$criterios['caja']}'" : "";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND c.dni_encargado = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND c.id_encargado = '{$criterios['numero_documento']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND c.vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_cajas($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT c.*,s.nombre sede,td.tipo tipo_documento,tc.tipo caja,
            e.nombre1 nombre1_resp,e.nombre2 nombre2_resp,e.apellido1 apellido1_resp,e.apellido2 apellido2_resp
                  FROM caja c
                  JOIN sede s ON c.sede=s.id
                  JOIN t_caja tc ON c.t_caja=tc.id
                  JOIN t_dni td ON c.dni_encargado=td.id
                  JOIN empleado e ON c.dni_encargado=e.dni AND c.id_encargado=e.id
                  WHERE true ";
        $query.=(!empty($criterios['sede'])) ? "AND c.sede = '{$criterios['sede']}'" : "";
        $query.=(!empty($criterios['caja'])) ? "AND c.t_caja = '{$criterios['caja']}'" : "";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND c.dni_encargado = '{$criterios['tipo_documento']}'" : "";
        $query.=(!empty($criterios['numero_documento'])) ? "AND c.id_encargado = '{$criterios['numero_documento']}'" : "";
        $query.=(isset($criterios['vigente'])) ? "AND c.vigente = '{$criterios['vigente']}'" : "";
        $query.=" LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
