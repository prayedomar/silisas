<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class Matriculam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function cantidad_matriculas($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT count(*) cantidad
                  FROM matricula ma
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND a.dni = '{$criterios['tipo_documento']}'" : "";
        return $this->db->query($query)->result();
    }

    public function listar_matriculas($criterios, $inicio, $filasPorPagina) {
        $query = "SELECT *,t.nombre1,t.nombre2,t.apellido1,t.apellido2,p.nombre nombre_plan,s.nombre nombre_sede,tdni.abreviacion nombre_dni
                  FROM matricula ma
                  JOIN titular t ON ma.dni_titular=t.dni AND ma.id_titular=t.id
                  JOIN t_plan p ON ma.plan=p.id
                    JOIN t_dni tdni ON ma.dni_titular=tdni.id
                    JOIN sede s ON ma.sede=s.id
                  where true ";
        $query.=(!empty($criterios['tipo_documento'])) ? "AND a.dni = '{$criterios['tipo_documento']}'" : "";
        $query.=" LIMIT $inicio,$filasPorPagina";
       // echo $query;
        return $this->db->query($query)->result();
    }

}
