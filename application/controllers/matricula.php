<?php

class MAtricula extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    //Crear: Matrícula
    function crear() {
        $data["tab"] = "crear_matricula";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['dni_titular'] = $this->select_model->t_dni_titular();
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);

        $data['action_llena_plan_comercial'] = base_url() . "matricula/llena_plan_comercial";
        $data['action_llena_ejecutivo'] = base_url() . "matricula/llena_empleado_rrpp_sedePpal";
        $data['action_validar'] = base_url() . "matricula/validar";
        $data['action_crear'] = base_url() . "matricula/insertar";

        $this->parser->parse('matricula/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('contrato', 'Número de Contrato Físico', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('dni_titular', 'Tipo de Id. del Titular', 'required|callback_select_default');
            $this->form_validation->set_rules('id_titular', 'Número de Id. del Titular', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('fecha_matricula', 'Fecha de Inicio', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('ejecutivo', 'Ejecutivo', 'required|callback_select_default');
            $this->form_validation->set_rules('plan', 'Plan Comercial', 'required');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            //Validamos que el número de contrato físico exista en dicha sede
            $error_contrato = "";
            if ($this->input->post('contrato')) {
                $contrato = $this->input->post('contrato');
                $id_responsable = $this->session->userdata('idResponsable');
                $dni_responsable = $this->session->userdata('dniResponsable');
                $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

                $check_contrato = $this->select_model->contrato_matricula_id($contrato);
                if ($check_contrato != TRUE) {
                    $error_contrato = "<p>El contrato físico ingresado, no existe en la base de datos.</p>";
                } else {
                    $check_contrato = $this->select_model->contrato_matricula_id_sede($contrato, $sede);
                    if ($check_contrato != TRUE) {
                        $error_contrato = "<p>El contrato físico ingresado, no se encuentra en su sede principal.</p>";
                    } else {
                        $check_contrato = $this->select_model->contrato_matricula_vacio_id($contrato);
                        if ($check_contrato != TRUE) {
                            $error_contrato = "<p>El contrato físico ingresado, no se encuentra vacío.</p>";
                        }
                    }
                }
            }
            $error_titular = "";
            if (($this->input->post('id_titular')) && ($this->input->post('dni_titular'))) {
                $t_usuario = 2; //Titular
                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_titular'), $this->input->post('dni_titular'), $t_usuario);
                if ($check_usuario != TRUE) {
                    $error_titular = "<p>El Titular ingresado, no existe en la Base de Datos.</p>";
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_contrato != "") || ($error_titular != "")) {
                echo form_error('contrato') . $error_contrato . form_error('fecha_matricula') . form_error('dni_titular') . form_error('id_titular') . $error_titular . form_error('ejecutivo') . form_error('plan') . form_error('observacion');
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
            $contrato = $this->input->post('contrato');
            $fecha_matricula = $this->input->post('fecha_matricula');
            $id_titular = $this->input->post('id_titular');
            $dni_titular = $this->input->post('dni_titular');
            list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivo'));
            $plan = $this->input->post('plan');
            //La cantidad de alumnos y materiales es la misma que la que se describe en el plan comercial seleccionado.
            $cant_alumnos_disponibles = $this->select_model->t_plan_id($plan)->cant_alumnos;
            $cant_materiales_disponibles = $cant_alumnos_disponibles;
            $datacredito = 1;
            $juridico = 0;
            $liquidacion_escalas = 0;  //Hasta el moemento no se han creados las comisiones de las escalas
            $estado = 2; //2: Activo            
            $observacion = ucfirst(strtolower($this->input->post('observacion')));

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

            $data["tab"] = "crear_matricula";
            $this->isLogin($data["tab"]);

            $error = $this->insert_model->matricula($contrato, $fecha_matricula, $id_titular, $dni_titular, $id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo, $plan, $cant_alumnos_disponibles, $cant_materiales_disponibles, $datacredito, $juridico, $liquidacion_escalas, $sede, $estado, $observacion, $id_responsable, $dni_responsable);

            if (isset($error)) {
                $data["tab"] = "crear_matricula";
                $this->load->view("header", $data);
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $data['url_recrear'] = base_url() . "matricula/crear";
                $data['msn_recrear'] = "Crear otra Matrícula";
                $this->parser->parse('trans_error', $data);
            } else {
                //Si todo salió bien, entonces cambiamos el estado del contrato fisico, de 1:vacío a 2:Activo
                $new_estado = 2;
                $error1 = $this->update_model->contrato_matricula_estado($contrato, $new_estado);
                if (isset($error1)) {
                    $data["tab"] = "crear_matricula";
                    $this->load->view("header", $data);
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $data['url_recrear'] = base_url() . "matricula/crear";
                    $data['msn_recrear'] = "Crear otra Matrícula";
                    $this->parser->parse('trans_error', $data);
                    return;
                }
                //Sí todo salió bien, Enviamos al formulario de liquidar_matricula
//                redirect(base_url() . 'liquidar_comisiones/crear/' . $contrato);
                //Temporalemnte mejor mostraremos el ok y listo. Boorar todo el parrafo siguiente y listo
                $data["tab"] = "crear_matricula";
                $this->load->view("header", $data);
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $data['url_recrear'] = base_url() . "matricula/crear";
                $data['msn_recrear'] = "Crear otra Matrícula";
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

    //Crear: Matrícula
    function editar_plan() {
        $data["tab"] = "editar_plan_matricula";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['dni_titular'] = $this->select_model->t_dni_titular();
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
        $data['action_recargar'] = base_url() . "matricula/editar_plan";
        $data['action_llena_t_plan_old'] = base_url() . "matricula/llena_t_plan_old";
        $data['action_llena_plan_comercial'] = base_url() . "matricula/llena_plan_comercial";
        $data['action_validar'] = base_url() . "matricula/validar_editar_plan";
        $data['action_crear'] = base_url() . "matricula/insertar_editar_plan";

        $this->parser->parse('matricula/editar_plan', $data);
        $this->load->view('footer');
    }

    function validar_editar_plan() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('id_matricula', 'Número de matrícula', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('plan_old', 'Tipo de plan actual', 'required');
            $this->form_validation->set_rules('plan_new', 'Nuevo tipo de plan', 'required');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            if ($this->form_validation->run() == FALSE) {
                echo form_error('id_matricula') . form_error('plan_old') . form_error('plan_new') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar_editar_plan() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $id_matricula = $this->input->post('id_matricula');
            $plan_old = $this->input->post('plan_old');
            $plan_new = $this->input->post('plan_new');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');

            $data["tab"] = "editar_plan_matricula";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "matricula/editar_plan";
            $data['msn_recrear'] = "Editar otro tipo de plan de una matrícula";

            $error = $this->update_model->cambio_plan_matricula($id_matricula, $plan_new);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->cambio_plan_matricula($id_matricula, $plan_old, $plan_new, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $this->parser->parse('trans_success', $data);
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_t_plan_old() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $id_matricula = $this->input->post('matricula');
            $matricula = $this->select_model->matricula_titular_idMatricula($id_matricula);
            if ($matricula == TRUE) {
                $id_responsable = $this->session->userdata('idResponsable');
                $dni_responsable = $this->session->userdata('dniResponsable');
                $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
                $check_matricula_sede = $this->select_model->matricula_id_sede($id_matricula, $sede);
                if ($check_matricula_sede == TRUE) {
                    $response = array(
                        'respuesta' => 'OK',
                        'nombreTitular' => $matricula->titular,
                        'plan_old' => $matricula->id,
                        'filasTablaOld' => '',
                        'filasTablaNew' => ''
                    );
                    $response['filasTablaOld'] = '<tr>
                            <td class="text-center">' . $matricula->nombre . '</td>
                            <td class="text-center">' . $matricula->anio . '</td>                                
                            <td class="text-center">' . $matricula->cant_alumnos . '</td>
                            <td class="text-center">$' . number_format($matricula->valor_total, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($matricula->valor_inicial, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($matricula->valor_cuota, 0, '.', ',') . '</td>                                
                            <td class="text-center">' . $matricula->cant_cuotas . '</td>                              
                        </tr>';
                    //Llenamos los nuevos planes que tengan el mismo numero de alumnos
                    $planes = $this->select_model->t_plan_igual_cantAlumnos($id_matricula);
                    if ($planes == TRUE) {
                        foreach ($planes as $fila) {
                            $response['filasTablaNew'] .= '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="plan_new" id="plan_new" value="' . $fila->id . '"/></td>
                            <td class="text-center">' . $fila->nombre . '</td>
                            <td class="text-center">' . $fila->anio . '</td>                                
                            <td class="text-center">' . $fila->cant_alumnos . '</td>
                            <td class="text-center">$' . number_format($fila->valor_total, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_inicial, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_cuota, 0, '.', ',') . '</td>                                
                            <td class="text-center">' . $fila->cant_cuotas . '</td>
                        </tr>';
                        }
                    } else {
                        $response['filasTablaNew'] = "";
                    }
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong>La matrícula no pertenece a su sede principal.</strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong>La matrícula no existe en la base de datos.</strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_rrpp_sedePpal() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . "-" . $fila->cargo . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

}
