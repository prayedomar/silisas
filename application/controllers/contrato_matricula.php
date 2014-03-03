<?php

class Contrato_matricula extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

    //Crear: Contrato Físico de matricula
    function crear() {
        $data["tab"] = "crear_contrato_matricula";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "contrato_matricula/validar";
        $data['action_crear'] = base_url() . "contrato_matricula/insertar";
        $this->parser->parse('contrato_matricula/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
        $this->escapar($_POST);            
            $this->form_validation->set_rules('contrato_inicial', 'Número de Contrato Inicial', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('contrato_final', 'Número de Contrato Final', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('sede_actual', 'Sede Actual', 'required|callback_select_default');

            //Cuando ya se hayan validado que los campos tengan el tamaño ideal y el required entonces validamos lo otro
            $error_valores = "";
            if ($this->form_validation->run() != FALSE) {
                $contrato_inicial = $this->input->post('contrato_inicial');
                $contrato_final = $this->input->post('contrato_final');
                if ($contrato_inicial > $contrato_final) {
                    $error_valores = "<p>El campo Contrato Inicial, debe ser menor o igual al campo Contrato Final</p>";
                } else {
                    //Maxímo una insercion de 10.000 contratos.
                    if (($contrato_final - $contrato_inicial) >= 1000) {
                        $error_valores = "<p>La inserción masiva, es de máximo 1.000 Contratos (Ejemplo: 10000->10999).</p>";
                    } else {
                        $bandera_contrato = 0;
                        $error_valores = "<p>Los siguientes contratos, ya existen en la base de datos: ";
                        for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                            $check_contrato_fisico = $this->select_model->contrato_matricula_id($i);
                            if ($check_contrato_fisico == TRUE) {
                                $error_valores .= $i . " ";
                                $bandera_contrato = 1;
                            }
                        }
                        $error_valores .= "</p>";
                        if ($bandera_contrato == 0) {
                            $error_valores = "";
                        }
                    }
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('contrato_inicial') . form_error('contrato_final') . $error_valores . form_error('sede_actual');
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
            $contrato_inicial = ucwords(strtolower($this->input->post('contrato_inicial')));
            $contrato_final = ucwords(strtolower($this->input->post('contrato_final')));
            $sede_actual = ucwords(strtolower($this->input->post('sede_actual')));
            $estado = 1; //1:Vacio
            
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $data["tab"] = "crear_contrato_matricula";
            $this->isLogin($data["tab"]);               
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "contrato_matricula/crear";
            $data['msn_recrear'] = "Crear otros Contratos Físicos";

            for ($i = $contrato_inicial; $i <= $contrato_final; $i++) {
                $error = $this->insert_model->contrato_matricula($i, $sede_actual, $estado, $id_responsable, $dni_responsable);
                if (isset($error)) {
                    $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                    $this->parser->parse('welcome', $data);
                    $this->load->view('footer');
                    return;
                }
            }
            $this->parser->parse('trans_success', $data);
        } else {
            redirect(base_url());
        }
    }

}
