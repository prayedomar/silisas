<?php

class Traslado_contrato_matricula extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_traslado_contrato_matricula";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['sede_actual'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['sede_destino'] = $this->select_model->sede_activa();
        $data['action_validar'] = base_url() . "traslado_contrato_matricula/validar";
        $data['action_crear'] = base_url() . "traslado_contrato_matricula/insertar";
        $this->parser->parse('traslado_contrato_matricula/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
            $this->form_validation->set_rules('contrato_inicial', 'Número de Contrato Inicial', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('contrato_final', 'Número de Contrato Final', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('sede_actual', 'Sede Actual', 'required|callback_select_default');
            $this->form_validation->set_rules('sede_destino', 'Sede Destino', 'required|callback_select_default');

            //Cuando ya se hayan validado que los campos tengan el tamaño ideal y el required entonces validamos lo otro
            $error_valores = "";
            if ($this->form_validation->run() != FALSE) {
                $contrato_inicial = $this->input->post('contrato_inicial');
                $contrato_final = $this->input->post('contrato_final');
                if ($contrato_inicial > $contrato_final) {
                    $error_valores = "<p>El campo Contrato Inicial, debe ser menor o igual al campo Contrato Final</p>";
                } else {
                    //Maxímo una insercion de 1.000 contratos.
                    if (($contrato_final - $contrato_inicial) >= 1000) {
                        $error_valores = "<p>La inserción masiva, es de máximo 1.000 Contratos (Ejemplo: 20000->20999).</p>";
                    } else {
                        $bandera_contrato = 0;
                        $error_valores = "<p>Los siguientes contratos, no existen en la base de datos: ";
                        for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                            $check_contrato_fisico = $this->select_model->contrato_matricula_id($i);
                            if ($check_contrato_fisico != TRUE) {
                                $error_valores .= $i . " ";
                                $bandera_contrato = 1;
                            }
                        }
                        $error_valores .= "</p>";
                        if ($bandera_contrato == 0) { //Porque no hubo errror de existencia
                            $sede_actual = $this->input->post('sede_actual');
                            $error_valores = "<p>Los siguientes contratos, no se encuentran en la Sede Actual ingresada: ";
                            for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                                $check_contrato_fisico = $this->select_model->contrato_matricula_id_sede($i, $sede_actual);
                                if ($check_contrato_fisico != TRUE) {
                                    $error_valores .= $i . " ";
                                    $bandera_contrato = 1;
                                }
                            }
                            $error_valores .= "</p>";
                            if ($bandera_contrato == 0) { //Porque no hubo errror de existencia
                                $error_valores = "";
                            }
                        }
                    }
                }
            }

            $error_sedes = "";
            if ((($this->input->post('sede_actual')) != "default") && (($this->input->post('sede_destino')) != "default")) {
                if ($this->input->post('sede_actual') == $this->input->post('sede_destino')) {
                    $error_sedes = "<p>La Sede Destino, debe ser distinta a la Sede Actual.</p>";
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_sedes != "")) {
                echo form_error('contrato_inicial') . form_error('contrato_final') . $error_valores . form_error('sede_actual') . form_error('sede_destino') . $error_sedes;
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
        $this->escapar($_POST);            
            $contrato_inicial = ucwords(strtolower($this->input->post('contrato_inicial')));
            $contrato_final = ucwords(strtolower($this->input->post('contrato_final')));
            $sede_actual = ucwords(strtolower($this->input->post('sede_actual')));
            $sede_destino = ucwords(strtolower($this->input->post('sede_destino')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $data["tab"] = "crear_traslado_contrato_matricula";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "traslado_contrato_matricula/crear";
            $data['msn_recrear'] = "Crear otro Traslado de Contratos";

            for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                $error = $this->update_model->contrato_matricula_sede_actual($i, $sede_destino);
                if (isset($error)) {
                    $data['trans_error'] = $error;
                    $this->parser->parse('trans_error', $data);
                    return;
                } else {
                    $error1 = $this->insert_model->traslado_contrato($i, $sede_actual, $sede_destino, 2, $fecha_trans, $id_responsable, $dni_responsable);
                    if (isset($error1)) {
                        $data['trans_error'] = $error1;
                        $this->parser->parse('trans_error', $data);
                        return;
                    }
                }
            }
            $this->parser->parse('trans_success', $data);
        } else {
            redirect(base_url());
        }
    }

}
