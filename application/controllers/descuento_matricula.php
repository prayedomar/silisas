<?php

class Descuento_matricula extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_descuento_matricula";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['action_validar'] = base_url() . "descuento_matricula/validar";
        $data['action_crear'] = base_url() . "descuento_matricula/insertar";
        $data['action_recargar'] = base_url() . "descuento_matricula/crear";
        $data['action_validar_matricula'] = base_url() . "descuento_matricula/validar_matricula";
        $data['action_llena_saldo_matricula'] = base_url() . "descuento_matricula/llena_saldo_matricula";
        $this->parser->parse('descuento_matricula/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('id', 'Número de matrícula', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('total', 'Valor del descuento', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
            $error_valores = "";
            if ($this->input->post('total')) {
                $saldo = $this->input->post('saldo');
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                if ($total > $saldo) {
                    $error_valores = "<p>El valor del descuento, no puede ser mayor al saldo de la matrícula</p>";
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('id') . form_error('total') . $error_valores . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $id_matricula = $this->input->post('id');
            $valor = round(str_replace(",", "", $this->input->post('total')), 2);
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');

            $data["tab"] = "crear_descuento_matricula";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "descuento_matricula/crear";
            $data['msn_recrear'] = "Crear otro descuento de matricula";

            $error = $this->insert_model->descuento_matricula($id_matricula, $valor, $observacion, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

    public function validar_matricula() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->load->model('matriculam');
            $matricula = $this->matriculam->matricula_id($this->input->post('id'));
            if ($matricula == TRUE) {
                if ($matricula->estado != '5') {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => '',
                        'saldo' => $matricula->saldo,
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $matricula->titular . '</td>
                            <td>' . $matricula->nombre_plan . '</td>
                            <td class="text-center">$' . number_format($matricula->valor_total, 2, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($matricula->descuentos, 2, '.', ',') . '</td>                             
                                <td class="text-center">$' . number_format($matricula->saldo, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $matricula->sede_ppal . '</td>                         
                            <td class="text-center">' . date("Y-m-d", strtotime($matricula->fecha_trans)) . '</td>  
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>La matrícula se encuentra anulada.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La matrícula no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
