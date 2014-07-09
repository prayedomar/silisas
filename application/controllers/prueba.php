<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $this->load->model("t_cargom");
        $this->load->model("comisiones_matriculam");
        $id_plan = 1;
        $depto = '3'; //Relaciones publicas
        $cargos_rrpp = $this->select_model->cargo_depto($depto);
        $response = array(
            'html_directas' => '',
            'html_escalas' => ''
        );
        foreach ($cargos_rrpp as $fila) {
            $response['html_directas'] .= '<div class="form-group">
                                                    <label>Cargo: ' . $fila->cargo_masculino . '<em class="required_asterisco">*</em></label>
                                                         <div class="input-group">
                                                            <span class="input-group-addon">$</span>
                                                            <input type="text" name="comision_directa[]" class="form-control exit_caution decimal decimal2 miles" placeholder="0.00" maxlength="12"';
            $comision = $this->comisiones_matriculam->comision_directa($id_plan, $fila->id);
            if ($comision) {
                $response['html_directas'] .= ' value="' . number_format($comision->comision, '2', '.', ',') . '"></div></div>';
            } else {
                $response['html_directas'] .= '></div></div>';
            }
            $response['html_escalas'] .= '<div class="form-group">
                                                    <label>Cargo: ' . $fila->cargo_masculino . '<em class="required_asterisco">*</em></label>
                                                         <div class="input-group">
                                                            <span class="input-group-addon">$</span>
                                                            <input type="text" name="comision_directa[]" class="form-control exit_caution decimal decimal2 miles" placeholder="0.00" maxlength="12"';
            $comision = $this->comisiones_matriculam->comision_escala($id_plan, $fila->id);
            if ($comision) {
                $response['html_escalas'] .= ' value="' . number_format($comision->comision, '2', '.', ',') . '"></div></div>';
            } else {
                $response['html_escalas'] .= '></div></div>';
            }
        }
        echo json_encode($response);
        return false;
    }

}
