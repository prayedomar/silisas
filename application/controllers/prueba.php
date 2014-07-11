<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {

        $this->load->model('empleadom');
        $this->load->model('t_cargom');
        list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("+", '1128265521+1+18');
        $t_cargos = $this->select_model->t_cargo_superior_rrpp_comisiones($cargo_ejecutivo);
        if ($t_cargos == TRUE) {
            //Buscamos el primer jefe por encima del que hizo la matricula.
            $jefe_actual = $this->empleadom->jefe_de_empleado($id_ejecutivo, $dni_ejecutivo);
            $response['htmlEscalas'] = "";
            foreach ($t_cargos as $fila) {
                //VAlidamos que el jefe si pertenezcca a relaciones publicas
                if ($jefe_actual->depto == '3') {
                    $response['htmlEscalas'] .= '<div class="form-group">
                            <label>Escala: ' . $fila->cargo_masculino . '<em class="required_asterisco">*</em></label>
                            <input name="cargos_escalas[]" type="hidden" value="' . $fila->id . "+" . $fila->cargo_masculino . '">
                            <select name="escalas[]" class="form-control exit_caution">';
                    $jerarquia_jefe_catual = $this->t_cargom->t_cargo_id($jefe_actual->cargo)->nivel_jerarquico;
                    $jerarqui_escala = $this->t_cargom->t_cargo_id($fila->id)->nivel_jerarquico;
                    echo "jefe actual: " . $jefe_actual->nombre1 . "<br>";                    
                    echo "jerarquia escala: " . $jerarqui_escala . "<br>";
                    echo "jerarquia jefe actual: " . $jerarquia_jefe_catual . "<br>";
                    if ($jerarqui_escala >= $jerarquia_jefe_catual) {
                        $response['htmlEscalas'] .= '<option value="' . $jefe_actual->id . "+" . $jefe_actual->dni . "+" . $jefe_actual->cargo . '">' . $jefe_actual->nombre1 . " " . $jefe_actual->nombre2 . " " . $jefe_actual->apellido1 . " " . $jefe_actual->apellido2 . '</option>';
                    } else {
                        $jefe_actual = $this->empleadom->jefe_de_empleado($jefe_actual->id, $jefe_actual->dni);
                        $response['htmlEscalas'] .= '<option value="' . $jefe_actual->id . "+" . $jefe_actual->dni . "+" . $jefe_actual->cargo . '">' . $jefe_actual->nombre1 . " " . $jefe_actual->nombre2 . " " . $jefe_actual->apellido1 . " " . $jefe_actual->apellido2 . '</option>';
                        //Escala es del jefe actual y siga
                    }
                    $response['htmlEscalas'] .= '<option value="nula">ÉSTA ESCALA NO SE PAGARÁ A NADIE</option>
                        </select>
                        </div>  ';
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>Alguno de los jefes del organigrama, no pertenece al departamento de relaciones públicas.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            }
            $response['respuesta'] = "OK";
            echo json_encode($response);
            return false;
        } else {
            $response = array(
                'respuesta' => 'alert'
            );
            echo json_encode($response);
            return false;
        }
    }

}
