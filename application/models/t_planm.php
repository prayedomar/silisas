<?php

if (!defined('BASEPATH'))
    exit('No direct script acces allowed');

class T_planm extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function listar_todas_los_planes() {
        $query = "SELECT * FROM t_plan ORDER BY anio DESC, cant_alumnos";
        return $this->db->query($query)->result();
    }

    public function t_plan_id($id_plan) {
        $SqlInfo = "SELECT * "
                . "FROM t_plan "
                . "WHERE (id='" . $id_plan . "')";
        $query = $this->db->query($SqlInfo);
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function actualizar_t_plan($id_plan, $nombre, $anio, $cant_alumnos, $puntos_real, $puntos_ireal, $cant_cuotas, $valor_total, $valor_inicial, $valor_cuota, $vigente) {
        $query = "UPDATE t_plan SET nombre='$nombre',anio='$anio',cant_alumnos='$cant_alumnos',puntos_real='$puntos_real',puntos_ireal='$puntos_ireal',cant_cuotas='$cant_cuotas',valor_total='$valor_total',valor_inicial='$valor_inicial',valor_cuota='$valor_cuota',vigente='$vigente' WHERE id='$id_plan'";
        $this->db->query($query);
        if ($error = $this->db->_error_message()) {
            return $error;
        }
    }

}
