<?php

class Plan_matricula extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function editar() {
        $data["tab"] = "editar_plan_matricula";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $this->load->model("t_planm");
        $data['t_plan'] = $this->t_planm->listar_todas_los_planes();
        $data['action_recargar'] = base_url() . "plan_matricula/editar";
        $data['action_validar'] = base_url() . "plan_matricula/validar";
        $data['action_crear'] = base_url() . "plan_matricula/insertar";
        $data['action_llena_plan_matricula'] = base_url() . "plan_matricula/llena_plan_matricula";
        $this->parser->parse('plan_matricula/editar', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('nombre', 'Nombre del plan', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('anio', 'Año de vigencia', 'required|trim|xss_clean|max_length[10]');
            $this->form_validation->set_rules('cant_alumnos', 'Cantidad de cupos de enseñanza', 'required|trim|max_length[2]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('puntos_real', 'Puntos para premios (real)', 'required|trim|max_length[2]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('puntos_ireal', 'Puntos para premios (ireal)', 'required|trim|max_length[5]|numeric|callback_valor_positivo');
            $this->form_validation->set_rules('cant_cuotas', 'Cantidad de cuotas', 'required|trim|max_length[2]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('valor_total', 'Valor total', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('valor_inicial', 'Valor cuota inicial', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('valor_cuota', 'Valor cuotas', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('vigente', 'Vigente para crear nuevas matrículas', 'required|callback_select_default');
            //Validamos los conceptos de nomina
            $error_comisiones = "";
            if (($this->input->post('comision_directas')) && ($this->input->post('comision_escalas'))) {
                $this->load->model("t_cargom");
                $comisiones_directas = $this->input->post('comision_directas');
                $comisiones_escalas = $this->input->post('comision_escalas');

                //Cuantos cargos hayan, cuantos input vendrán
                $depto = '3'; //Relaciones publicas                
                $cargos_rrpp = $this->t_cargom->cargo_depto($depto);
                $i = 0;
                foreach ($cargos_rrpp as $fila) {
                    if ($comisiones_directas[$i] == '') {
                        $error_comisiones .= "<p>El campo comisión directa (" . $fila->cargo_masculino . "), es obligatorio.</p>";
                    }
                    $i++;
                }
                $i = 0;
                foreach ($cargos_rrpp as $fila) {
                    if ($comisiones_escalas[$i] == '') {
                        $error_comisiones .= "<p>El campo comisión por escala  (" . $fila->cargo_masculino . "), es obligatorio.</p>";
                    }
                    $i++;
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_comisiones != "")) {
                echo form_error('nombre') . form_error('anio') . form_error('cant_alumnos') . form_error('puntos_real') . form_error('puntos_ireal') . form_error('cant_cuotas') . form_error('valor_total') . form_error('valor_inicial') . form_error('valor_cuota') . form_error('vigente') . $error_comisiones;
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
            $this->load->model("t_planm");
            $id_plan = $this->input->post('id_plan');
            $nombre = ucwords(mb_strtolower($this->input->post('nombre')));
            $anio = ucwords(mb_strtolower($this->input->post('anio')));
            $cant_alumnos = str_replace(",", "", $this->input->post('cant_alumnos'));
            $puntos_real = str_replace(",", "", $this->input->post('puntos_real'));
            $puntos_ireal = round(str_replace(",", "", $this->input->post('puntos_ireal')), 2);
            $cant_cuotas = str_replace(",", "", $this->input->post('cant_cuotas'));
            $valor_total = round(str_replace(",", "", $this->input->post('valor_total')), 2);
            $valor_inicial = round(str_replace(",", "", $this->input->post('valor_inicial')), 2);
            $valor_cuota = round(str_replace(",", "", $this->input->post('valor_cuota')), 2);
            $vigente = $this->input->post('vigente');

            $data["tab"] = "editar_plan_matricula";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "plan_matricula/editar";
            $data['msn_recrear'] = "Editar otro plan de matrícula";

            $error1 = $this->t_planm->actualizar_t_plan($id_plan, $nombre, $anio, $cant_alumnos, $puntos_real, $puntos_ireal, $cant_cuotas, $valor_total, $valor_inicial, $valor_cuota, $vigente);
            if (isset($error1)) {
                $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $this->load->model("t_cargom");
                $this->load->model("comisiones_matriculam");
                $comisiones_directas = $this->input->post('comision_directas');
                $comisiones_escalas = $this->input->post('comision_escalas');
                $depto = '3'; //Relaciones publicas                
                $cargos_rrpp = $this->t_cargom->cargo_depto($depto);
                $i = 0;
                foreach ($cargos_rrpp as $fila) {
                    $comision_directa = round(str_replace(",", "", $comisiones_directas[$i]), 2);
                    $error2 = $this->comisiones_matriculam->insertar_comision_directa($id_plan, $fila->id, $comision_directa);
                    if (isset($error2)) {
                        $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                        return;
                    }
                    $comision_escala = round(str_replace(",", "", $comisiones_escalas[$i]), 2);
                    $error3 = $this->comisiones_matriculam->insertar_comision_escala($id_plan, $fila->id, $comision_escala);
                    if (isset($error3)) {
                        $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                        return;
                    }
                    $i++;
                }
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

    public
            function llena_plan_matricula() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->load->model("t_cargom");
            $this->load->model("comisiones_matriculam");
            $this->load->model("t_planm");
            $id_plan = $this->input->post('idPlan');
            $depto = '3'; //Relaciones publicas
            $plan = $this->t_planm->t_plan_id($id_plan);
            $cargos_rrpp = $this->t_cargom->cargo_depto($depto);
            if (($plan) && ($cargos_rrpp)) {
                $response = array(
                    'respuesta' => 'OK',
                    'nombre' => $plan->nombre,
                    'cant_alumnos' => $plan->cant_alumnos,
                    'anio' => $plan->anio,
                    'valor_total' => number_format($plan->valor_total, '2', '.', ','),
                    'valor_inicial' => number_format($plan->valor_inicial, '2', '.', ','),
                    'valor_cuota' => number_format($plan->valor_cuota, '2', '.', ','),
                    'cant_cuotas' => $plan->cant_cuotas,
                    'puntos_ireal' => $plan->puntos_ireal,
                    'puntos_real' => $plan->puntos_real,
                    'vigente' => $plan->vigente,
                    'html_directas' => '',
                    'html_directas' => '',
                    'html_escalas' => ''
                );
                foreach ($cargos_rrpp as $fila) {
                    $response['html_directas'] .= '<div class="form-group">
                                                    <label>Cargo: ' . $fila->cargo_masculino . '<em class="required_asterisco">*</em></label>
                                                         <div class="input-group">
                                                            <span class="input-group-addon">$</span>
                                                            <input type="text" name="comision_directas[]" class="form-control exit_caution decimal decimal2 miles" placeholder="0.00" maxlength="12"';
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
                                                            <input type="text" name="comision_escalas[]" class="form-control exit_caution decimal decimal2 miles" placeholder="0.00" maxlength="12"';
                    $comision = $this->comisiones_matriculam->comision_escala($id_plan, $fila->id);
                    if ($comision) {
                        $response['html_escalas'] .= ' value="' . number_format($comision->comision, '2', '.', ',') . '"></div></div>';
                    } else {
                        $response['html_escalas'] .= '></div></div>';
                    }
                }
                echo json_encode($response);
                return false;
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p>Error al cargar las comisiones de la base de datos.</p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
