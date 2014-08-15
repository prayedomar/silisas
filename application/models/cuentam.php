<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Cuentam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function listar_todas_las_cuentas() {
        $query = "SELECT * FROM cuenta";
        return $this->db->query($query)->result();
    }    

    public function cantidad_cuentas($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM  cuenta c
                  JOIN t_cuenta tc ON c.t_cuenta=tc.id
                  JOIN banco b ON c.banco=b.id
                  JOIN empleado e ON c.dni_responsable=e.dni AND c.id_responsable=e.id
                  WHERE true ";
        $query.=(!empty($criterios['numero_cuenta'])) ? "AND c.id = '{$criterios['numero_cuenta']}'" : "";
        $query.=(!empty($criterios['cuenta'])) ? "AND c.t_cuenta = '{$criterios['cuenta']}'" : "";
        $query.=(!empty($criterios['banco'])) ? "AND c.banco = '{$criterios['banco']}'" : "";
        $query.=(!empty($criterios['nombre_cuenta'])) ? "AND lower(c.nombre_cuenta) LIKE '%" . strtolower($criterios['nombre_cuenta']) . "%'" : " ";
        $query.=(isset($criterios['vigente'])) ? "AND c.vigente = '{$criterios['vigente']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_cuentas($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT c.*,tc.tipo tipo_cuenta,b.nombre nombre_banco,
                  e.nombre1 nombre1_resp,e.nombre2 nombre2_resp,e.apellido1 apellido1_resp,e.apellido2 apellido2_resp
                  FROM  cuenta c
                  JOIN t_cuenta tc ON c.t_cuenta=tc.id
                  JOIN banco b ON c.banco=b.id
                  JOIN empleado e ON c.dni_responsable=e.dni AND c.id_responsable=e.id
                  WHERE true ";
        $query.=(!empty($criterios['numero_cuenta'])) ? "AND c.id = '{$criterios['numero_cuenta']}'" : "";
        $query.=(!empty($criterios['cuenta'])) ? "AND c.t_cuenta = '{$criterios['cuenta']}'" : "";
        $query.=(!empty($criterios['banco'])) ? "AND c.banco = '{$criterios['banco']}'" : "";
        $query.=(!empty($criterios['nombre_cuenta'])) ? "AND lower(c.nombre_cuenta) LIKE '%" . strtolower($criterios['nombre_cuenta']) . "%'" : " ";
        $query.=(isset($criterios['vigente'])) ? "AND c.vigente = '{$criterios['vigente']}'" : "";
        $query.=" LIMIT $inicio,$filasPorPagina";
        return $this->db->query($query)->result();
    }

}
