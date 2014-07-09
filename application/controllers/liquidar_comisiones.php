<?php

class Liquidar_comisiones extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear($contrato) {
        $data["tab"] = "crear_liquidar_comisiones";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['id_matricula'] = "$contrato";
        $data['action_validar'] = base_url() . "liquidar_comisiones/validar";
        $data['action_crear'] = base_url() . "liquidar_comisiones/insertar";

        $data['action_llena_matricula_iliquidada'] = base_url() . "liquidar_comisiones/llena_matricula_iliquidada";
        $data['action_llena_detalle_matricula'] = base_url() . "liquidar_comisiones/llena_detalle_matricula_liquidar";
        $data['action_llena_ejecutivo'] = base_url() . "liquidar_comisiones/llena_empleado_rrpp_sedePpal";
        $data['action_llena_cargo_comision_faltante'] = base_url() . "liquidar_comisiones/llena_cargo_comision_faltante";
        $data['action_llena_cargo_ejecutivo_directo'] = base_url() . "liquidar_comisiones/llena_cargo_ejecutivo_directo";

        $this->parser->parse('liquidar_comisiones/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('matricula', 'Número de Matrícula', 'required|callback_select_default');
            $this->form_validation->set_rules('ejecutivo_directo', 'Comisión Directa', 'required|callback_select_default');

            $error_escalas = "";
            if (($this->input->post('escalas')) && ($this->input->post('cargos_escalas'))) {
                $cargos_escalas = $this->input->post('cargos_escalas');
                $escalas = $this->input->post('escalas');
                $i = 0;
                foreach ($escalas as $fila) {
                    if ($fila == "default") {
                        list($id_cargo, $nombre_cargo) = explode("-", $cargos_escalas[$i]);
                        $error_escalas .= "<p>La Escala: " . $nombre_cargo . ", es obligatoria.</p>";
                    }
                    $i++;
                }
            }
            $error_comisiones = "";
            if ($this->input->post('ejecutivo_directo') && $this->input->post('matricula')) {
                $id_matricula = $this->input->post('matricula');
                //Aqui vamos a verificar que si exita la tabla de comisiones para el plan de la matricula.
                list($id_ejecutivo_directo, $dni_ejecutivo_directo, $cargo_ejecutivo_directo) = explode("-", $this->input->post('ejecutivo_directo'));
                $plan = $this->select_model->matricula_id($id_matricula)->plan;
                $valor_unitario = $this->select_model->comision_matricula($plan, $cargo_ejecutivo_directo);
                if ($valor_unitario != TRUE) {
                    $error_comisiones = '<P>No se ha definido alguna de las comisiones que corresponden al plan de la matrícula.</P>';
                }
                $cargos_escalas = $this->input->post('cargos_escalas');
                $escalas = $this->input->post('escalas');
                if (($cargos_escalas == TRUE) && ($escalas == TRUE)) {
                    //Si hay escalas las pagamos.
                    $i = 0;
                    foreach ($escalas as $fila) {
                        //pregutnamos si la escala es diferenete a la opcion de no se le va a pagar a nadie
                        if ($fila != "nula") {
                            list($cargo_escala, $nombre_cargo) = explode("-", $cargos_escalas[$i]);
                            $valor_unitario = $this->select_model->comision_escala($plan, $cargo_escala);
                            if ($valor_unitario != TRUE) {
                                $error_comisiones = '<P>No se ha definido alguna de las comisiones que corresponden al plan de la matrícula.</P>';
                            }
                        }
                        $i++;
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_escalas != "") || ($error_comisiones != "")) {
                echo form_error('matricula') . form_error('ejecutivo_directo') . $error_escalas . $error_comisiones;
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
            $this->load->model('matriculam');
            $id_matricula = $this->input->post('matricula');
            list($id_ejecutivo_original, $dni_ejecutivo_original, $cargo_ejecutivo_original) = explode("-", $this->input->post('ejecutivo_original'));
            list($id_ejecutivo_directo, $dni_ejecutivo_directo, $cargo_ejecutivo_directo) = explode("-", $this->input->post('ejecutivo_directo'));

            $est_concepto_nomina = 2; //2: Pendiente

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;

            $matricula = $this->select_model->matricula_id($id_matricula);
            $t_concepto_nomina = 29; //29, 'Comisión Directa Matricula
            $plan = $matricula->plan;
            //a continuacion asumimos que las comisiones del tipo de plan estan creadas.
            $valor_unitario = $this->select_model->comision_matricula($plan, $cargo_ejecutivo_directo)->comision;
            //Si no encuentra en la base de datos la comision, entonces la comision es cero.
            if ($valor_unitario != TRUE) {
                $valor_unitario = 0.00;
            }
            $sede_matricula = $this->matriculam->sede_matricula_id($id_matricula);
            $detalle = "Matrícula: " . $id_matricula . " (" . $sede_matricula->nombre_sede . ")";

            $data["tab"] = "crear_liquidar_comisiones";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "liquidar_comisiones/crear/new";
            $data['msn_recrear'] = "Crear otra Liquidación de Matrícula.";

            //si cambiaron el ejectuivo principal lo cambiamos en la matricula
            if ($id_ejecutivo_original != $id_ejecutivo_directo) {
                $error = $this->update_model->ejecutivo_matricula($id_matricula, $id_ejecutivo_directo, $dni_ejecutivo_directo);
                if (isset($error)) {
                    $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                    return;
                } else {
                    $this->insert_model->cambio_ejecutivo_matricula($id_matricula, $id_ejecutivo_original, $dni_ejecutivo_original, $id_ejecutivo_directo, $dni_ejecutivo_directo, $id_responsable, $dni_responsable);
                }
            }

            $error1 = $this->insert_model->concepto_nomina($id_ejecutivo_directo, $dni_ejecutivo_directo, NULL, NULL, $t_concepto_nomina, $detalle, $id_matricula, $plan, $cargo_ejecutivo_directo, $cargo_ejecutivo_directo, 1, $valor_unitario, $est_concepto_nomina, $sede, $id_responsable, $dni_responsable);
            if (isset($error1)) {
                $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $cargos_escalas = $this->input->post('cargos_escalas');
                $escalas = $this->input->post('escalas');
                //Si hay escalas las pagamos.
                if (($cargos_escalas == TRUE) && ($escalas == TRUE)) {
                    $t_concepto_nomina = 28; //28, 'Comisión Escala Matricula
                    $i = 0;
                    foreach ($escalas as $fila) {
                        //pregutnamos si la escala es diferenete a la opcion de no se le va a pagar a nadie
                        if ($fila != "nula") {
                            list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $fila);
                            list($cargo_escala, $nombre_cargo) = explode("-", $cargos_escalas[$i]);
                            //Asumimos que las comisiones ya estan creadas
                            $valor_unitario = $this->select_model->comision_escala($plan, $cargo_escala)->comision;
                            if ($valor_unitario != TRUE) {
                                $valor_unitario = 0.00;
                            }
                            $error2 = $this->insert_model->concepto_nomina($id_ejecutivo, $dni_ejecutivo, NULL, NULL, $t_concepto_nomina, $detalle, $id_matricula, $plan, $cargo_escala, $cargo_ejecutivo, 1, $valor_unitario, $est_concepto_nomina, $sede, $id_responsable, $dni_responsable);
                            if (isset($error2)) {
                                $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                                $this->parser->parse('trans_error', $data);
                                return;
                            }
                        }
                        $i++;
                    }
                }
                //Colocamos las comisiones de don libardo: 
                //SEde poblado (Id: 2): gana escala divisional, si el ejecutivo directo de la matricula tiene cargo inferior a regional (Hernan)
                //Sede floresta (Id: 1): gana escala divisional, si el ejecutivo directo de la matricula tiene cargo inferior a divisional
                $nivel_jerarquico_ejecutivo_directo = $this->select_model->t_cargo_id($cargo_ejecutivo_directo)->nivel_jerarquico;
                if ($id_ejecutivo_directo != '98672030') { //Porq si el hizo la matricula se le duplicarias
                    if (($sede_matricula->id_sede == '2') && ($nivel_jerarquico_ejecutivo_directo > '6')) {
                        $t_concepto_nomina = '28'; //28, 'Comisión Escala Matricula
                        $cargo_escala = '16'; //Divisional
                        $valor_unitario = $this->select_model->comision_escala($plan, $cargo_escala)->comision;
                        $error2 = $this->insert_model->concepto_nomina('98672030', '1', NULL, NULL, $t_concepto_nomina, $detalle, $id_matricula, $plan, $cargo_escala, $cargo_escala, 1, $valor_unitario, $est_concepto_nomina, $sede, $id_responsable, $dni_responsable);
                    } else {
                        if (($sede_matricula->id_sede == '1') && ($nivel_jerarquico_ejecutivo_directo > '7')) {
                            $t_concepto_nomina = '28'; //28, 'Comisión Escala Matricula
                            $cargo_escala = '16'; //Divisional
                            $valor_unitario = $this->select_model->comision_escala($plan, $cargo_escala)->comision;
                            $error2 = $this->insert_model->concepto_nomina('98672030', '1', NULL, NULL, $t_concepto_nomina, $detalle, $id_matricula, $plan, $cargo_escala, $cargo_escala, 1, $valor_unitario, $est_concepto_nomina, $sede, $id_responsable, $dni_responsable);
                        }
                    }
                }
                //ACtualizamos el estado de la matricula a liquidada.
                $error2 = $this->update_model->matricula_liquidacion_escalas($id_matricula, 1);
                if (isset($error2)) {
                    $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $this->parser->parse('trans_success', $data);
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_matricula_iliquidada() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $matriculas = $this->select_model->matricula_iliquida_responsable($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($matriculas == TRUE) {
                    foreach ($matriculas as $fila) {
                        echo '<option value="' . $fila->contrato . '">' . $fila->contrato . '</option>';
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

    public function llena_detalle_matricula_liquidar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('matricula')) && ($this->input->post('matricula') != "default")) {
                $contrato = $this->input->post('matricula');
                $detalle = $this->select_model->detalle_matricula_liquidar($contrato);
                if ($detalle == TRUE) {
                    $response = array(
                        'respuesta' => 'OK',
                        'detalleMatricula' => '',
                        'IdDniEjecutivo' => $detalle->id . "-" . $detalle->dni . "-" . $detalle->cargo,
                        'CargoEjecutivo' => $detalle->cargo
                    );
                    $response['detalleMatricula'] = '<tr>
                            <td class="text-center">' . $detalle->titular . '</td>
                            <td class="text-center">' . $detalle->plan . '</td>
                            <td class="text-center">' . $detalle->observacion . '</td>
                            <td class="text-center">' . $detalle->ejecutivo . '</td>                                
                            <td class="text-center">' . $detalle->name_cargo . '</td>
                            <td class="text-center">' . $detalle->fecha_matricula . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error'
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

    public function llena_cargo_comision_faltante() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable')) && ($this->input->post('ejecutivoDirecto')) && (($this->input->post('ejecutivoDirecto')) != "default")) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivoDirecto'));
                $t_cargos = $this->select_model->t_cargo_superior_rrpp($cargo_ejecutivo);
                if ($t_cargos == TRUE) {
                    foreach ($t_cargos as $fila) {
                        echo '<div class="form-group">
                            <label>Escala: ' . $fila->cargo_masculino . '<em class="required_asterisco">*</em></label>
                            <input name="cargos_escalas[]" type="hidden" value="' . $fila->id . "-" . $fila->cargo_masculino . '">
                            <select name="escalas[]" class="form-control exit_caution">
                            <option value="default">Seleccione Ejecutivo para la escala</option>';
                        $ejecutivos = $this->select_model->empleado_rrpp_cargo_superior($fila->id, $id_responsable, $dni_responsable);
                        if ($ejecutivos == TRUE) {
                            foreach ($ejecutivos as $registro) {
                                echo '<option value="' . $registro->id . "-" . $registro->dni . "-" . $registro->cargo . '">' . $registro->nombre1 . " " . $registro->nombre2 . " " . $registro->apellido1 . " " . $registro->apellido2 . '</option>';
                            }
                        }
                        echo '<option value="nula">ÉSTA ESCALA NO SE PAGARÁ A NADIE</option>
                        </select>
                        </div>  ';
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

    public function llena_cargo_ejecutivo_directo() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('ejecutivoDirecto')) && ($this->input->post('ejecutivoDirecto') != "default")) {
                list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivoDirecto'));
                $t_cargo = $this->select_model->t_cargo_id($cargo_ejecutivo);
                //Validamos que la consulta devuelva algo
                if ($t_cargo == TRUE) {
                    echo '<label>Cargo: ' . $t_cargo->cargo_masculino . '</label><em class="required_asterisco">*</em>';
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

    function consultar() {
        $data["tab"] = "consultar_liquidar_comisiones";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');

        $data['action_llena_comisiones_matricula'] = base_url() . "liquidar_comisiones/llena_comisiones_matricula";

        $this->parser->parse('liquidar_comisiones/consultar', $data);
        $this->load->view('footer');
    }

    public function llena_comisiones_matricula() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('matricula')) {
                $matricula = $this->input->post('matricula');
                $conceptos = $this->select_model->concepto_nomina_matricula($matricula);
                $total = $this->select_model->total_concepto_nomina_matricula($matricula);
                if ($conceptos == TRUE) {
                    foreach ($conceptos as $fila) {
                        echo '<tr>
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>                        
                                <td class="text-center">' . $fila->prefijo_nomina . ' ' . $fila->id_nomina . '</td>
                                <td class="text-center">' . $fila->ejecutivo . '</td>
                                <td class="text-center">' . $fila->tipo_concepto . '</td>
                                <td class="text-center">' . $fila->escala . '</td>
                                <td class="text-center">$' . number_format($fila->valor_unitario, 2, '.', ',') . '</td>                                
                            </tr>';
                    }
                    echo '<tr><td colspan="5"><p style="text-align:right;font-size:18px;"><b>Total</b></p></td><td><p style="text-align:right;font-size:18px;">$' . number_format($total->total, 2, '.', ',') . '</p></td></tr>';
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
