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
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
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
            $contrato_inicial = ucwords(mb_strtolower($this->input->post('contrato_inicial')));
            $contrato_final = ucwords(mb_strtolower($this->input->post('contrato_final')));
            $sede_actual = ucwords(mb_strtolower($this->input->post('sede_actual')));
            $estado = 1; //1:Vacio

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');

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

    function anular() {
        $data["tab"] = "anular_contrato_matricula";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "contrato_matricula/validar_anular";
        $data['action_crear'] = base_url() . "contrato_matricula/insertar_anular";
        $data['action_recargar'] = base_url() . "contrato_matricula/anular";
        $data['action_validar_contrato_matricula_anular'] = base_url() . "contrato_matricula/validar_contrato_matricula_anular";
        $this->parser->parse('contrato_matricula/anular', $data);
        $this->load->view('footer');
    }

    function validar_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('id', 'Número de Matrícula', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('cant_materiales_devueltos', 'Cantidad de materiales devueltos', 'required|trim|max_length[2]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
            if (($this->form_validation->run() == FALSE) || ($error_matricula != "")) {
                echo form_error('id') . $error_cod . form_error('cod_autorizacion') . form_error('cant_materiales_devueltos') . $error_materiales . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar_anular() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $this->load->model('update_model');
            $id = $this->input->post('id');
            $cant_materiales_devueltos = $this->input->post('cant_materiales_devueltos');
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $estado = '5'; //Anulado

            $data["tab"] = "anular_contrato_matricula";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "contrato_matricula/anular";
            $data['msn_recrear'] = "Anular otra matrícula";
            $error = $this->update_model->contrato_matricula_estado($id, $estado);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $this->load->model('alumnom');
                //Si anulo la contrato_matricula, anulo los alumnos que esten inscritos en dicha contrato_matricula.
                $error1 = $this->alumnom->anular_alumnos_contrato_matricula_anulada($id);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $error2 = $this->insert_model->anular_contrato_matricula($id, $cant_materiales_devueltos, $observacion, $id_responsable, $dni_responsable);
                    if (isset($error2)) {
                        $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                    } else {
                        $this->parser->parse('trans_success', $data);
                    }
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function validar_contrato_matricula_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $id = $this->input->post('id');
            $this->load->model('contrato_matriculam');
            $contrato_matricula = $this->contrato_matriculam->contrato_matricula_id($id);
            if ($contrato_matricula == TRUE) {
                if ($contrato_matricula->estado != 3) {
                    $this->load->model('matriculam');
                    $matricula = $this->matriculam->matricula_id($id);
                    if (($matricula != TRUE) || ($matricula->estado == '5')) {
                        $response = array(
                            'respuesta' => 'OK',
                            'filasTabla' => ''
                        );
                        $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $contrato_matricula->id . '</td>
                            <td class="text-center">' . $contrato_matricula->sede . '</td>
                            <td class="text-center">' . $contrato_matricula->estado_contrato . '</td>                              
                            <td class="text-center">' . $contrato_matricula->responsable . '</td>       
                            <td class="text-center">' . date("Y-m-d", strtotime($contrato_matricula->fecha_trans)) . '</td>
                        </tr>';
                        echo json_encode($response);
                        return false;
                    } else {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p><strong><center>Existe una matrícula vigente con éste número de contrato. <br>Si desea anular éste contrato físico, anule la matrícula correspondiente.</center></strong></p>'
                        );
                        echo json_encode($response);
                        return false;
                    }
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>El contrato físico de matrícula, ya se encuentra anulado.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>El contrato físico de matrícula, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
