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
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['dni_titular'] = $this->select_model->t_dni_titular();
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);

        $data['action_llena_plan_comercial'] = base_url() . "matricula/llena_plan_comercial";
        $data['action_llena_ejecutivo'] = base_url() . "matricula/llena_empleado_rrpp_sedePpal";
        $data['action_validar'] = base_url() . "matricula/validar";
        $data['action_crear'] = base_url() . "matricula/insertar";

        $this->parser->parse('crear_matricula', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
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
                $id_responsable = $this->input->post('id_responsable');
                $dni_responsable = $this->input->post('dni_responsable');
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
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

            $error = $this->insert_model->matricula($contrato, $fecha_matricula, $id_titular, $dni_titular, $id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo, $plan, $cant_alumnos_disponibles, $cant_materiales_disponibles, $datacredito, $juridico, $liquidacion_escalas, $sede, $estado, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            if (isset($error)) {
                $data["tab"] = "crear_matricula";
                $this->load->view("header", $data);
                $data['trans_error'] = $error;
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
                    $data['trans_error'] = $error1;
                    $data['url_recrear'] = base_url() . "matricula/crear";
                    $data['msn_recrear'] = "Crear otra Matrícula";
                    $this->parser->parse('trans_error', $data);
                    return;
                }
                //Sí todo salió bien, Enviamos al formulario de liquidar_matricula
                redirect(base_url() . 'liquidar_comisiones/crear/' . $contrato);
            }
        } else {
            redirect(base_url());
        }
    }
    
    public function llena_plan_comercial() {
        if ($this->input->is_ajax_request()) {
            $planes = $this->select_model->t_plan_activo();
            if ($planes == TRUE) {
                foreach ($planes as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="plan" id="plan" value="' . $fila->id . '"/></td>
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
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }  
    
    public function llena_empleado_rrpp_sedePpal() {
        if ($this->input->is_ajax_request()) {
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
