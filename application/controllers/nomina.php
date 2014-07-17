<?php

class Nomina extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
        $this->load->model('nominam');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_nomina";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);

        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_activo_sedes_responsable($id_responsable, $dni_responsable);
        $data['sede_ppal'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['action_validar'] = base_url() . "nomina/validar";
        $data['action_crear'] = base_url() . "nomina/insertar";
        $data['action_recargar'] = base_url() . "nomina/crear";
        $data['action_llena_info_contrato_laboral'] = base_url() . "nomina/llena_info_contrato_laboral";
        $data['action_llena_info_ultimas_nominas'] = base_url() . "nomina/llena_info_ultimas_nominas";
        $data['action_llena_info_adelantos'] = base_url() . "nomina/llena_info_adelantos";
        $data['action_llena_info_prestamos'] = base_url() . "nomina/llena_info_prestamos";
        $data['action_llena_info_ausencias'] = base_url() . "nomina/llena_info_ausencias";
        $data['action_llena_info_seguridad_social'] = base_url() . "nomina/llena_info_seguridad_social";
        $data['action_llena_concepto_pdtes_rrpp'] = base_url() . "nomina/llena_concepto_pdtes_rrpp";
        $data['action_llena_concepto_cotidiano'] = base_url() . "nomina/llena_concepto_cotidiano";
        $data['action_llena_agregar_concepto'] = base_url() . "nomina/llena_agregar_concepto";
        $data['action_llena_info_t_concepto'] = base_url() . "nomina/llena_info_t_concepto";
        $data['action_llena_periodicidad_nomina'] = base_url() . "nomina/llena_periodicidad_nomina";
        $data['action_validar_fechas_periodicidad'] = base_url() . "nomina/validar_fechas_periodicidad";
        $data['action_llena_cuenta_responsable'] = base_url() . "nomina/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "nomina/llena_caja_responsable";
        $this->parser->parse('nomina/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            $this->form_validation->set_rules('periodicidad', 'Periodicidad', 'required|callback_select_default');
            $this->form_validation->set_rules('fecha_inicio', 'Fecha Inicio', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('fecha_fin', 'Fecha Fin', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('total_nomina', 'Total Nómina', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            $error_valores = "";
            if ($this->input->post('total_nomina')) {
                $total = round(str_replace(",", "", $this->input->post('total_nomina')), 2);
                if (!$this->input->post('valor_retirado')) {
                    $valor_retirado = 0;
                } else {
                    $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
                }
                if (!$this->input->post('efectivo_retirado')) {
                    $efectivo_retirado = 0;
                } else {
                    $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
                }
                if (round(($valor_retirado + $efectivo_retirado), 2) != $total) {
                    $error_valores = "<p>La suma del valor retirado de una cuenta y el efectivo retirado de una caja, deben sumar exactamente: $" . $total . ", en vez de: $" . number_format(($valor_retirado + $efectivo_retirado), 2, '.', ',') . ".</p>";
                }
            }
            //Validamos los conceptos de nomina
            $error_conceptos = "";
            if (($this->input->post('t_concepto_nomina')) && ($this->input->post('detalle')) && ($this->input->post('cantidad')) && ($this->input->post('valor_unitario'))) {
                $t_concepto_nomina = $this->input->post('t_concepto_nomina');
                $detalle = $this->input->post('detalle');
                $cantidad = $this->input->post('cantidad');
                $valor_unitario = $this->input->post('valor_unitario');
                $i = 0;
                foreach ($t_concepto_nomina as $fila) {
                    if ($fila == "default") {
                        $error_conceptos .= "<p>El campo Tipo Concepto, es obligatorio.</p>";
                    }
                    if (($cantidad[$i] <= '0') || ($cantidad[$i] == '')) {
                        $error_conceptos .= "<p>El campo Cantidad, debe ser mayor a cero.</p>";
                    }
                    if ($valor_unitario[$i] == '') {
                        $error_conceptos .= "<p>El campo valor unitario, es obligatorio</p>";
                    }
                    $i++;
                }
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_conceptos != "")) {
                echo form_error('empleado') . form_error('periodicidad') . form_error('fecha_inicio') . form_error('fecha_fin') . $error_conceptos . form_error('total_nomina') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion');
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
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $empleado = $this->select_model->empleado($id_empleado, $dni_empleado);
            $depto = $empleado->depto;
            $cargo = $empleado->cargo;
            $t_periodicidad = $this->input->post('periodicidad');
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            $dias_nomina = $this->input->post('dias_nomina');
            $dias_remunerados = $this->input->post('dias_remunerados');
            $ausencias = $this->input->post('cant_ausencias');
            $total_devengado = round(str_replace(",", "", $this->input->post('total_devengado')), 2);
            $total_deducido = round(str_replace(",", "", $this->input->post('total_deducido')), 2);
            $total = round(str_replace(",", "", $this->input->post('total_nomina')), 2);
            if (($this->input->post('cuenta')) && ($this->input->post('valor_retirado')) && ($this->input->post('valor_retirado') != 0)) {
                $cuenta_origen = $this->input->post('cuenta');
                $valor_retirado = round(str_replace(",", "", $this->input->post('valor_retirado')), 2);
            } else {
                $cuenta_origen = NULL;
                $valor_retirado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_retirado')) && ($this->input->post('efectivo_retirado') != 0)) {
                list($sede_caja_origen, $t_caja_origen) = explode("-", $this->input->post('caja'));
                $efectivo_retirado = round(str_replace(",", "", $this->input->post('efectivo_retirado')), 2);
            } else {
                $sede_caja_origen = NULL;
                $t_caja_origen = NULL;
                $efectivo_retirado = NULL;
            }
            $vigente = 1;
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_nomina = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_nomina = ($this->select_model->nextId_nomina($prefijo_nomina)->id) + 1;
            $t_trans = 9; //Nomina Laboral
            $credito_debito = 0; //Debito             
            $data["tab"] = "crear_nomina";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "nomina/crear";
            $data['msn_recrear'] = "Crear otra Nómina";
            $data['url_imprimir'] = base_url() . "nomina/consultar_pdf/" . $prefijo_nomina . "_" . $id_nomina . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_nomina, $id_nomina, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->nomina($prefijo_nomina, $id_nomina, $id_empleado, $dni_empleado, $depto, $cargo, $t_periodicidad, $fecha_inicio, $fecha_fin, $dias_nomina, $dias_remunerados, $ausencias, $total_devengado, $total_deducido, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    //traemos los campos de los conceptos
                    $rrpp_nuevo = $this->input->post('rrpp_nuevo');
                    $id_concepto = $this->input->post('id_concepto');
                    $t_concepto_nomina = $this->input->post('t_concepto_nomina');
                    $detalle = $this->input->post('detalle');
                    $cantidad = $this->input->post('cantidad');
                    $valor_unitario = $this->input->post('valor_unitario');
                    if (($rrpp_nuevo == TRUE) && ($id_concepto == TRUE) && ($t_concepto_nomina == TRUE) && ($detalle == TRUE) && ($cantidad == TRUE) && ($valor_unitario == TRUE)) {
                        $i = 0;
                        foreach ($rrpp_nuevo as $fila) {
                            //En el caso que sean conceptos pendiente de nomina por ser actualizados a OK
                            if ($fila == '1') {
                                //ACtualizamos los conceptos de rrpp a 1: OK y actualizamos el prefid de la nomina
                                $error2 = $this->update_model->concepto_nomina_rrpp_ok($id_concepto[$i], $prefijo_nomina, $id_nomina);
                                if (isset($error2)) {
                                    $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                                    $this->parser->parse('trans_error', $data);
                                    return;
                                }
                                //en el caso en que hayan que crear los conceptos
                            } else {
                                $t_concepto_temp = $t_concepto_nomina[$i];
                                $detalle_temp = strtolower($detalle[$i]);
                                $cantidad_temp = $cantidad[$i];
                                $valor_unitario_temp = round(str_replace(",", "", $valor_unitario[$i]), 2);
                                $error3 = $this->insert_model->concepto_nomina($id_empleado, $dni_empleado, $prefijo_nomina, $id_nomina, $t_concepto_temp, $detalle_temp, NULL, NULL, NULL, NULL, $cantidad_temp, $valor_unitario_temp, 1, $sede, $id_responsable, $dni_responsable);
                                if (isset($error3)) {
                                    $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                                    $this->parser->parse('trans_error', $data);
                                    return;
                                }
                            }
                            $i++;
                        }
                        $this->parser->parse('trans_success_print', $data);
                    } else {
                        $data['trans_error'] = "<p>No llegaron correctamente los conceptos al servidor. Comuníquele este error a soporte de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                        return;
                    }
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_contrato_laboral() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $contrato = $this->select_model->contrato_laboral_empleado($id_empleado, $dni_empleado);
                if (($contrato == TRUE)) {
                    if ($contrato->fecha_fin == NULL) {
                        $fecha_fin = "Indefinida";
                    } else {
                        $fecha_fin = $contrato->fecha_fin;
                    }
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Tipo Contrato</th>
                                                <th class="text-center">Fecha Inicio</th>                                                
                                                <th class="text-center">Fecha Fin</th>  
                                                <th class="text-center">Cargo</th>                                                
                                                <th class="text-center">Salario</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>                  
                                            <td class="text-center">' . $contrato->tipo_contrato . '</td>                                            
                                            <td class="text-center">' . $contrato->fecha_inicio . '</td>
                                            <td class="text-center">' . $fecha_fin . '</td>                                                
                                            <td class="text-center">' . $contrato->cargo . '</td>                                                
                                            <td class="text-center">' . $contrato->nombre_salario . '</td>                                  
                                            <td>' . $contrato->observacion . '</td>  
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>';
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

    public function llena_info_ultimas_nominas() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $nominas = $this->select_model->ultimas_nominas_empleado($id_empleado, $dni_empleado);
                if (($nominas == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Nómina</th>
                                                <th class="text-center">Fecha Inicial</th>
                                                <th class="text-center">Fecha Final</th>
                                                <th class="text-center">Sede</th>                                                
                                                <th class="text-center">Total Nómina</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($nominas as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo . " " . $fila->id . '</td>                            
                                <td class="text-center">' . $fila->fecha_inicio . '</td>
                                <td class="text-center">' . $fila->fecha_fin . '</td>  
                                <td class="text-center">' . $fila->nombre_sede . '</td>                                
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>                                   
                                <td>' . $fila->observacion . '</td>  
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
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

    public function llena_info_adelantos() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $adelantos = $this->select_model->adelanto_vigente_empleado($id_empleado, $dni_empleado);
                if (($adelantos == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Adelanto</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Sede</th>
                                                <th class="text-center">Valor Inicial</th>                                            
                                                <th class="text-center">Saldo Pdte.</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($adelantos as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_adelanto . " " . $fila->id_adelanto . '</td>       
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>                                
                                <td class="text-center">' . $fila->sede . '</td>
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>  
                                <td class="text-center">$' . number_format($fila->saldo, 2, '.', ',') . '</td>                                
                                <td>' . $fila->observacion . '</td>  
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
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

    public function llena_info_prestamos() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $prestamos = $this->select_model->prestamo_vigente_beneficiario($id_empleado, $dni_empleado);
                if (($prestamos == TRUE)) {
                    echo '<p class="help-block"><B>> </B>Los abonos a préstamos no se realizan por la nomina, sino por la opción: Crear->Abono a Préstamo.</p>
                        <div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Préstamo</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Sede</th>
                                                <th class="text-center">Valor Inicial</th>                                        
                                                <th class="text-center">Cant Cuotas</th>
                                                <th class="text-center">Cuota Fija</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($prestamos as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_prestamo . " " . $fila->id_prestamo . '</td>   
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>                                
                                <td class="text-center">' . $fila->sede . '</td>
                                <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>
                                <td class="text-center">' . $fila->cant_cuotas . '</td>                                
                                <td class="text-center">$' . number_format($fila->cuota_fija, 2, '.', ',') . '</td>                               
                                <td>' . $fila->observacion . '</td>
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
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

    public function llena_info_ausencias() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('empleado')) && ($this->input->post('fechaInicio')) && ($this->input->post('fechaFin'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $fecha_inicio_nomina = $this->input->post('fechaInicio');
                $fecha_fin_nomina = $this->input->post('fechaFin');
                $ausencias = $this->select_model->ausencia_entre_fechas($id_empleado, $dni_empleado, $fecha_inicio_nomina, $fecha_fin_nomina);
                $response = array(
                    'respuesta' => 'OK',
                    'html_ausencias' => '',
                    'cant_nomina' => $this->dias_entre_fechas($fecha_inicio_nomina, $fecha_fin_nomina) + 1,
                    'cant_ausencias' => 0,
                    'cant_no_remunerada' => 0
                );
                if (($ausencias == TRUE)) {
                    $response['html_ausencias'] = '<p class="help-block"><B>> </B>Sólo aparecerán las ausencias ocurridas entre el rango de fechas de la Nómina.</p>
                        <div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha Inicial</th>
                                                <th class="text-center">Fecha Final</th>                                        
                                                <th class="text-center">Días de Ausencia en Nómina</th>                                                 
                                                <th class="text-center">Tipo de Ausencia</th>
                                                <th class="text-center">Remuneración</th>
                                                <th class="text-center">Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($ausencias as $fila) {
                        //Calculamos la cantidad de dias de ausencia dentro de la nomina
                        //Primer caso: en que toda la ausencia este dentro del rango de la nomina
                        if (($fila->fecha_inicio >= $fecha_inicio_nomina) && ($fila->fecha_inicio <= $fecha_fin_nomina) && ($fila->fecha_fin >= $fecha_inicio_nomina) && ($fila->fecha_fin <= $fecha_fin_nomina)) {
                            $cant_ausencia = $this->dias_entre_fechas($fila->fecha_inicio, $fila->fecha_fin) + 1;
                        } else {
                            //Segundo caso: toda la nomina esta dentro del rango de la ausencia
                            if (($fecha_inicio_nomina >= $fila->fecha_inicio) && ($fecha_inicio_nomina <= $fila->fecha_fin) && ($fecha_fin_nomina >= $fila->fecha_inicio) && ($fecha_fin_nomina <= $fila->fecha_fin)) {
                                $cant_ausencia = $this->dias_entre_fechas($fecha_inicio_nomina, $fecha_fin_nomina) + 1;
                            } else {
                                //Tercer caso: que la nomina se salga por el lado derecho de la ausencia (diagrama de interseccion de conjuntos).
                                if ($fecha_inicio_nomina >= $fila->fecha_inicio) {
                                    $cant_ausencia = $this->dias_entre_fechas($fecha_inicio_nomina, $fila->fecha_fin) + 1;
                                } else {
                                    //Ultimo caso: que la nomina se salga por el lado izquierdo de la ausencia (diagrama de interseccion de conjuntos).
                                    $cant_ausencia = $this->dias_entre_fechas($fila->fecha_inicio, $fecha_fin_nomina) + 1;
                                }
                            }
                        }
                        if ($fila->salarial == 1) {
                            $remunerada = "Remunerada";
                        } else {
                            $response['cant_no_remunerada'] += $cant_ausencia;
                            $remunerada = "No remunerada";
                        }
                        $response['cant_ausencias'] += $cant_ausencia;
                        $response['html_ausencias'] .= '<tr>
                                <td class="text-center">' . $fila->fecha_inicio . '</td>   
                                <td class="text-center">' . $fila->fecha_fin . '</td>                                 
                                <td class="text-center">' . $cant_ausencia . '</td>                                
                                <td class="text-center">' . $fila->tipo . '</td>
                                <td class="text-center">' . $remunerada . '</td>                                                              
                                <td>' . $fila->descripcion . '</td>
                            </tr>';
                    }
                    $response['html_ausencias'] .= '</tbody>
                        </table>
                    </div>';
                }
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
            redirect(base_url());
        }
    }

    public function llena_info_seguridad_social() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $seguridades = $this->select_model->concepto_nomina_seguridad_social($id_empleado, $dni_empleado);
                if (($seguridades == TRUE)) {
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id. Nomina</th>
                                                <th class="text-center">Sede</th>                                             
                                                <th class="text-center">Fecha del Concepto</th>
                                                <th class="text-center">Valor del Pago SS.SS</th>
                                                <th class="text-center">Detalle</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($seguridades as $fila) {
                        echo '<tr>
                                <td class="text-center">' . $fila->prefijo_nomina . "-" . $fila->id_nomina . '</td>
                                <td class="text-center">' . $fila->nombre_sede . '</td>                                
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>
                                <td class="text-center">$' . number_format(($fila->cantidad * $fila->valor_unitario), 2, '.', ',') . '</td>                                    
                                <td class="text-center">' . $fila->detalle . '</td>
                            </tr>';
                    }
                    echo '</tbody>
                        </table>
                    </div>';
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

    public function llena_concepto_pdtes_rrpp() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $conceptos = $this->select_model->concepto_nomina_pdte_rrpp($id_empleado, $dni_empleado);
                if ($conceptos == TRUE) {
                    echo '<label>Conceptos Pendientes de RRPP</label>';
                    $i = 1;
                    foreach ($conceptos as $fila) {
                        //rrpp_nuevo: si es 1 es porq es concepto de rrpp y no se inserta si no que se actualiza a ok,
                        //ttpp_nuevo: si es 2 es porq es concepto nuevo y ahi que crearlo 
                        echo '<div class="div_input_group renglon_concepto renglon_pdte" id="div_concepto_pdte_' . $i . '">
                                <div class="row">
                                    <input type="hidden" name="rrpp_nuevo[]" id="rrpp_nuevo" value="1">                                
                                    <input type="hidden" name="id_concepto[]" id="id_concepto" value="' . $fila->id . '">
                                    <input type="hidden" name="t_concepto_nomina[]" id="t_concepto_nomina" value="' . $fila->t_concepto_nomina . '">
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Tipo de Concepto</label>
                                            <input name="nombre_concepto[]" id="nombre_concepto" type="text" class="form-control text-center" readonly value="' . $fila->tipo_concepto . '">
                                        </div>                            
                                    </div>     
                                    <input type="hidden" name="debito_credito[]" id="debito_credito" value="1">
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Nombre Escala</label>
                                            <input name="escala[]" id="escala" type="text" class="form-control text-center" readonly value="' . $fila->escala . '">
                                        </div>                            
                                    </div>                                    
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Detalle</label>
                                            <input name="detalle[]" id="detalle" type="text" class="form-control text-center" readonly value="' . $fila->detalle . '">
                                        </div>                            
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label class="required">Fecha</label>
                                            <input name="fecha_concepto[]" id="fecha_concepto" type="text" class="form-control text-center" readonly value="' . date("Y-m-d", strtotime($fila->fecha_trans)) . '">
                                        </div>                            
                                    </div>                                    
                                    <input type="hidden" name="cantidad[]" id="cantidad" value="' . number_format($fila->cantidad, 2, '.', ',') . '">
                                    <input type="hidden" name="valor_unitario[]" id="valor_unitario" value="' . number_format($fila->valor_unitario, 2, '.', ',') . '">                                    
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Devengado</label>                            
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total_concepto[]" id="total_concepto" class="form-control decimal2 miles text-center" readonly>
                                            </div>
                                        </div>                          
                                    </div>
                                    <div class="col-xs-1 padding_remove">
                                        <label class="label_btn_remove">. </label>                                
                                        <div class="form-group sin_margin_bottom text-center">
                                            <button class="btn btn-default drop_concepto_pdte" id="' . $i . '" type="button"><span class="glyphicon glyphicon-remove"></span></button>  
                                        </div>
                                    </div>
                                </div>      
                            </div>';
                        $i++;
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

    public function llena_concepto_cotidiano() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idUltimoConcepto')) && ($this->input->post('empleado'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $i = $this->input->post('idUltimoConcepto');
                $t_concepto = $this->select_model->t_concepto_nomina_cotidiano_empleado($id_empleado, $dni_empleado);
                if (($t_concepto == TRUE)) {
                    $response = array(
                        'respuesta' => 'OK',
                        'html_concepto' => '',
                        'ultimo_concepto' => $i,
                    );
                    foreach ($t_concepto as $fila) {
                        $i ++;
                        $response['ultimo_concepto'] = $i;
                        //rrpp_nuevo: si es 1 es porq es concepto de rrpp y no se inserta si no que se actualiza a ok,
                        //ttpp_nuevo: si es 2 es porq es concepto nuevo y ahi que crearlo                         
                        $response['html_concepto'] .= '<div class="div_input_group renglon_concepto renglon_cotidiano" id="div_concepto_new_' . $i . '">
                                <div class="row">
                                <input type="hidden" name="rrpp_nuevo[]" id="rrpp_nuevo" value="2">                                
                                <input type="hidden" name="id_concepto[]" id="id_concepto" value="NULL">                                
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Tipo de Concepto<em class="required_asterisco">*</em></label>
                                            <select name="t_concepto_nomina[]" id="t_concepto_nomina" class="form-control exit_caution">
                                                <option value="' . $fila->id . '">' . $fila->tipo . '</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="debito_credito[]" id="debito_credito">                                    
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <div id="label_detalle"><label class="required">Detalles adicionales</label></div>
                                            <input name="detalle[]" id="detalle" type="text" class="form-control exit_caution letras_numeros" placeholder="Detalle Adicional" maxlength="50" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Cantidad<em class="required_asterisco">*</em></label>
                                            <input name="cantidad[]" id="cantidad" type="text" class="form-control exit_caution numerico input_center" placeholder="Cantidad" maxlength="3" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Valor Unitario<em class="required_asterisco">*</em></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="valor_unitario[]" id="valor_unitario" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <div id="label_total_concepto"><label>Total Concepto</label></div>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total_concepto[]" id="total_concepto" class="form-control decimal decimal2 miles text-center" placeholder="0.00" maxlength="12" readonly>
                                            </div>
                                        </div>  
                                    </div>                                    
                                    <div class="col-xs-1  padding_remove">
                                        <label class="label_btn_remove">. </label>                                
                                        <div class="form-group sin_margin_bottom text-center">
                                            <button class="btn btn-default drop_concepto_new" id="' . $i . '" type="button"><span class="glyphicon glyphicon-remove"></span></button>  
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    }
                    echo json_encode($response);
                    return FALSE;
                } else {
                    $response = array(
                        'respuesta' => 'error'
                    );
                    echo json_encode($response);
                    return FALSE;
                }
            } else {
                $response = array(
                    'respuesta' => 'error'
                );
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_agregar_concepto() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idUltimoConcepto')) && ($this->input->post('empleado'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $i = $this->input->post('idUltimoConcepto') + 1;
                $t_concepto = $this->select_model->t_concepto_nomina_depto_empleado($id_empleado, $dni_empleado);
                echo '<div class="div_input_group renglon_concepto renglon_nuevo" id="div_concepto_new_' . $i . '">
                                <div class="row">
                                <input type="hidden" name="rrpp_nuevo[]" id="rrpp_nuevo" value="2">                                
                                <input type="hidden" name="id_concepto[]" id="id_concepto" value="NULL">                                 
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Tipo de Concepto<em class="required_asterisco">*</em></label>
                                            <select name="t_concepto_nomina[]" id="t_concepto_nomina" class="form-control exit_caution">
                                                <option value="default">T. de Concepto Nómina</option>';
                if (($t_concepto == TRUE)) {
                    foreach ($t_concepto as $fila) {
                        echo '                  <option value="' . $fila->id . '">' . $fila->tipo . '</option>';
                    }
                }
                echo '                       </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="debito_credito[]" id="debito_credito">                                    
                                    <div class="col-xs-3 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <div id="label_detalle"><label class="required">Detalles adicionales</label></div>
                                            <input name="detalle[]" id="detalle" type="text" class="form-control exit_caution letras_numeros" placeholder="Detalle Adicional" maxlength="50" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Cantidad<em class="required_asterisco">*</em></label>
                                            <input name="cantidad[]" id="cantidad" type="text" class="form-control exit_caution numerico input_center" placeholder="Cantidad" maxlength="3" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Valor Unitario<em class="required_asterisco">*</em></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="valor_unitario[]" id="valor_unitario" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="col-xs-2 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <div id="label_total_concepto"><label>Total Concepto</label></div>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" name="total_concepto[]" id="total_concepto" class="form-control decimal decimal2 miles text-center" placeholder="0.00" maxlength="12" readonly>
                                            </div>
                                        </div>  
                                    </div>                                    
                                    <div class="col-xs-1  padding_remove">
                                        <label class="label_btn_remove">. </label>                                
                                        <div class="form-group sin_margin_bottom text-center">
                                            <button class="btn btn-default drop_concepto_new" id="' . $i . '" type="button"><span class="glyphicon glyphicon-remove"></span></button>  
                                        </div>
                                    </div>
                                </div>
                            </div>';
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_info_t_concepto() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('tConceptoNomina')) && ($this->input->post('empleado'))) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $id_t_concepto = $this->input->post('tConceptoNomina');
                $concepto_base = $this->select_model->concepto_base_nomina_empleado($id_empleado, $dni_empleado, $id_t_concepto);
                $t_concepto = $this->select_model->t_concepto_nomina_id($id_t_concepto);
                if ($t_concepto == TRUE) {
                    //Si existe un concpeto base para el t_conepto y el salario del empleado, pasamos su valor unitario.
                    if ($concepto_base == TRUE) {
                        $concepto_base = number_format($concepto_base->valor_unitario, 2, '.', ',');
                    } else {
                        $concepto_base = 0.00;
                    }
                    $response = array(
                        'respuesta' => 'OK',
                        'valor_unitario' => $concepto_base,
                        'debito_credito' => $t_concepto->debito_credito,
                        't_cantidad_dias' => $t_concepto->t_cantidad_dias,
                        'placeholder_detalle' => $t_concepto->placeholder,
                        'detalle_requerido' => $t_concepto->detalle_requerido
                    );
                    echo json_encode($response);
                    return FALSE;
                } else {
                    $response = array(
                        'respuesta' => 'error'
                    );
                    echo json_encode($response);
                    return FALSE;
                }
            } else {
                $response = array(
                    'respuesta' => 'error'
                );
                echo json_encode($response);
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_cuenta_responsable() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $cuentas = $this->select_model->cuenta_banco_responsable_retirar($id_responsable, $dni_responsable);
                if (($cuentas == TRUE)) {
                    foreach ($cuentas as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="cuenta" id="cuenta" value="' . $fila->id . '"/></td>
                            <td>' . $fila->id . '</td>
                            <td class="text-center">' . $fila->t_cuenta . '</td>
                            <td>' . $fila->banco . '</td>
                            <td>' . $fila->nombre_cuenta . '</td>    
                            <td>' . $fila->observacion . '</td>   
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>    
                        </tr>';
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

    public function llena_periodicidad_nomina() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if ($this->input->post('empleado')) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $periodicidades = $this->select_model->periodicidad_nomina($id_empleado, $dni_empleado);
                if ($periodicidades == TRUE) {
                    foreach ($periodicidades as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->tipo . '</option>';
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

    public function validar_fechas_periodicidad() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('periodicidad')) && ($this->input->post('fechaInicio')) && ($this->input->post('fechaFin'))) {
                $periodicidad = $this->input->post('periodicidad');
                $fecha_inicio = $this->input->post('fechaInicio');
                $fecha_fin = $this->input->post('fechaFin');
                if (($this->fecha_valida($fecha_inicio)) && ($this->fecha_valida($fecha_fin))) {
                    if (($fecha_inicio) <= ($fecha_fin)) {
                        if ($periodicidad == '1') {
                            echo "OK";
                        } else {
                            if ($periodicidad == '2') {
                                if (($this->dias_entre_fechas($fecha_inicio, $fecha_fin)) == 6) {
                                    echo "OK";
                                } else {
                                    echo "<p>Las fechas deben coincidir con la periodicidad escogida: <strong>Semanal.</strong><p>La fecha inicial y final, debe ser de lunes a domingo.</p></p>";
                                }
                            } else {
                                if ($periodicidad == '3') {
                                    if ((date("m", strtotime($fecha_inicio)) == (date("m", strtotime($fecha_fin))))) {
                                        if ((((date("d", strtotime($fecha_inicio))) == '01') && ((date("d", strtotime($fecha_fin))) == '15')) || (((date("d", strtotime($fecha_inicio))) == '16') && ((date("d", strtotime($fecha_fin)) == date("d", (mktime(0, 0, 0, date("m", strtotime($fecha_fin)) + 1, 1, date("Y", strtotime($fecha_fin))) - 1)))))) {
                                            echo "OK";
                                        } else {
                                            echo "<p>Las fechas deben coincidir con la periodicidad escogida: <strong>Quincenal.</strong><p>La primer quincena del mes, será del 1 al 15 día del mes y la segunda quincena del mes, será del: 16 al último día del mes.</p></p>";
                                        }
                                    } else {
                                        echo "<p>Las fechas deben coincidir con la periodicidad escogida: <strong>Quincenal.</strong><p>La primer quincena del mes, será del 1 al 15 día del mes y la segunda quincena del mes, será del: 16 al último día del mes.</p></p>";
                                    }
                                } else {
                                    if ($periodicidad == '4') {
                                        if ((date("m", strtotime($fecha_inicio)) == (date("m", strtotime($fecha_fin))))) {
                                            if (((date("d", strtotime($fecha_inicio))) == '01') && ((date("d", strtotime($fecha_fin)) == date("d", (mktime(0, 0, 0, date("m", strtotime($fecha_fin)) + 1, 1, date("Y", strtotime($fecha_fin))) - 1))))) {
                                                echo "OK";
                                            } else {
                                                echo "<p>Las fechas deben coincidir con la periodicidad escogida: <strong>Mensual.</strong><p>La fecha inicial será el primer día del mes y la fecha final el útimo día del mes.</p></p>";
                                            }
                                        } else {
                                            echo "<p>Las fechas deben coincidir con la periodicidad escogida: <strong>Mensual.</strong><p>La fecha inicial será el primer día del mes y la fecha final el útimo día del mes.</p></p>";
                                        }
                                    } else {
                                        echo "<p>Periodicidad Desconocida.</p>";
                                    }
                                }
                            }
                        }
                    } else {
                        echo "<p>La fecha final no puede ser menor que la fecha inicial.</p>";
                    }
                } else {
                    echo "<p>Los campos de fecha, deben tener un formato valido: yyyy-mm-dd.</p>";
                }
            } else {
                echo "<p>Error en los campos de entrada.</p>";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_caja_responsable() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $cajas = $this->select_model->caja_responsable($id_responsable, $dni_responsable);
                if (($cajas == TRUE)) {
                    foreach ($cajas as $fila) {
                        $responsable = $this->select_model->empleado($fila->id_encargado, $fila->dni_encargado);
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="caja" id="caja" value="' . $fila->sede . "-" . $fila->t_caja . '"/></td>
                            <td class="text-center">' . $fila->name_sede . '</td>
                            <td>' . $fila->name_t_caja . '</td>  
                            <td>' . $responsable->nombre1 . " " . $responsable->nombre2 . " " . $responsable->apellido1 . " " . '</td>  
                            <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_trans)) . '</td>    
                        </tr>';
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

    function consultar() {
        $data["tab"] = "consultar_nomina";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "nomina/consultar_validar";
        $data['action_recargar'] = base_url() . "nomina/consultar";
        $this->parser->parse('nomina/consultar', $data);
        $this->load->view('footer');
    }

    public function consultar_validar() {
        $this->escapar($_POST);
        $this->form_validation->set_rules('prefijo', 'Prefijo de sede', 'required|callback_select_default');
        $this->form_validation->set_rules('id', 'Número o consecutivo', 'required|trim|max_length[13]|integer|callback_valor_positivo');
        $prefijo = $this->input->post('prefijo');
        $id = $this->input->post('id');
        $error_transaccion = "";
        if (($this->input->post('prefijo') != "default") && ($this->input->post('id'))) {
            $nomina = $this->nominam->nomina_prefijo_id($prefijo, $id);
            if ($nomina == TRUE) {
                if ($nomina->vigente == 0) {
                    $error_transaccion = "La nómina laboral, se encuentra anulada.";
                }
            } else {
                $error_transaccion = "La nómina laboral, no existe en la base de datos.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_nomina";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
            $data['action_crear'] = base_url() . "nomina/consultar_validar";
            $this->parser->parse('nomina/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "nomina/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_nomina, $salida_pdf) {
        $nomina_prefijo_id = $id_nomina;
        $id_nomina_limpio = str_replace("_", " ", $nomina_prefijo_id);
        list($prefijo, $id) = explode("_", $nomina_prefijo_id);
        $nomina = $this->nominam->nomina_prefijo_id($prefijo, $id);
        $conceptos_nomina = $this->nominam->concepto_nomina_group_matricula($prefijo, $id);
        if (($nomina == TRUE) && ($conceptos_nomina == TRUE)) {
            $dni_abreviado_empleado = $this->select_model->t_dni_id($nomina->dni_empleado)->abreviacion;
            if ($nomina->genero_empleado == "M") {
                $empleado_a = "o";
                $cargo = $nomina->cargo_masculino;
            } else {
                $empleado_a = "a";
                $cargo = $nomina->cargo_femenino;
            }

            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Nómina ' . $id_nomina_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Nómina ' . $id_nomina_limpio . ' Sili S.A.S');
            $pdf->SetKeywords('sili, sili sas');


//// se pueden modificar en el archivo tcpdf_config.php de libraries/config
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//relación utilizada para ajustar la conversión de los píxeles
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// ---------------------------------------------------------
// establecer el modo de fuente por defecto            
            $pdf->setFontSubsetting(true);
            $pdf->setPrintHeader(false); //no imprime la cabecera ni la linea
            $pdf->setPrintFooter(false); //no imprime el pie ni la linea        
// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
            $pdf->AddPage();

            //preparamos y maquetamos el contenido a crear
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:30px;font-weight: bold;font-style: italic;line-height:40px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:10px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:100px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:100px;}';
            $html .= 'td.c11{width:150px;}';
            $html .= 'td.c4{width:265px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c9{width:115px;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c20{width:240px;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c21{width:140px;height:33px;line-height:32px;font-weight: bold;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c22{width:110px;height:33px;line-height:32px;font-weight: bold;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;line-height:25px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;height:30px;line-height:25px;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.c29{background-color:#F5F5F5;}';
            $html .= 'td.c30{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'td.a3{text-align:justify;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'th.a3{background-color:#F5F5F5;}';
            $html .= 'th.d1{width:310px;}';
            $html .= 'th.d2{width:80px;}';
            $html .= 'th.d3{width:120px;}';
            $html .= 'th.d4{width:110px;}';
            $html .= 'th.d5{width:110px;}';
            $html .= 'th.d6{height:30px;line-height:25px;}';
            $html .= 'th.d7{border-top-color:#000000;border-bottom-color:#000000;border-left-color:#000000;border-right-color:#000000;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '<p class="b1">Medellín: Calle 47D # 77 AA - 67  (Floresta)  / Tels.: 4114107 – 4126800<br>'
                    . 'Medellín: Carrera 48B # 10 SUR - 118 (Poblado) / Tels.: 3128614 – 3126060<br>'
                    . 'Cali Sur: Carrera 44 # 5A – 26 (Tequendama) / Tels.: 3818008 – 3926723<br>'
                    . 'Cali Norte: Calle 25 # Norte 6A – 32 (Santa Mónica) / Tels.: 3816803 – 3816734<br>'
                    . 'Bucaramanga: Carrera 33 # 54 – 91 (Cabecera) / Tels.: 6832612 – 6174057<br>'
                    . 'Montería: Calle 58 # 6 – 39 (Castellana) / Tels.:7957110 – 7957110<br>'
                    . 'Montelíbano: Calle 17 # 13 2do piso / Tels.: 7625202 – 7625650<br>'
                    . 'Santa Marta: Carrera 13 B # 27 B – 84  (B. Bavaria) / Tels.: 4307566 – 4307570<br>'
                    . 'El Bagre: Calle 1 # 32 (Cornaliza) / Tels.: 8372645 – 8372653<br>'
                    . 'Caucasia: Carrera 8A # 22 – 48. 2do Piso (B. Kennedy) / Tels.: 8391693 - 8393582</p>'
                    . '</td>'
                    . '<td class="c2 a2 c1000"  colspan="2"></td>'
                    . '<br>'
                    . '</tr><tr>'
                    . '<td class="a2 c24" colspan="2">NÓMINA LABORAL</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . $id_nomina_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . date("Y-m-d", strtotime($nomina->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . $nomina->responsable . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Periodicidad:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nomina->tipo_periodicidad . '</td>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Periodo:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">Del ' . $nomina->fecha_inicio . ' al ' . $nomina->fecha_fin . '</td>'
                    . '</tr></table>'
                    . '<table><tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Días nómina:</b></td><td class="c9 c23 c12 c25 c26 c27 c28">' . $nomina->dias_nomina . '</td>'
                    . '<td class="c11 c23 c12 c25 c26 c27 c28"><b>Días remunerados:</b></td><td class="c3 c23 c12 c25 c26 c27 c28">' . $nomina->dias_remunerados . '</td>'
                    . '<td class="c11 c23 c12 c25 c26 c27 c28"><b>Ausencias:</b></td><td class="c9 c23 c12 c25 c26 c27 c28">' . $nomina->ausencias . '</td>'
                    . '</tr></table>'
                    . '<table><tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Emplead' . $empleado_a . ':</b></td><td class="c4 c23 c25 c26 c27 c28">' . $nomina->empleado . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Documento:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $dni_abreviado_empleado . ' ' . $nomina->id_empleado . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Departamento:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $nomina->departamento . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Cargo:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $cargo . '</td>'
                    . '</tr>'
                    . '</table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<th class="d1 c23 d6 a2 d7 a3"><b>Concepto</b></th>'
                    . '<th class="d2 c23 d6 a2 d7 a3"><b>Cantidad</b></th>'
                    . '<th class="d3 c23 d6 a2 d7 a3"><b>Valor unitario</b></th>'
                    . '<th class="d4 c23 d6 a2 d7 a3"><b>Devengado</b></th>'
                    . '<th class="d5 c23 d6 a2 d7 a3"><b>Deducido</b></th>'
                    . '</tr>';
            $cont_filas = 0;
            foreach ($conceptos_nomina as $fila) {
                if ($fila->debito_credito == 1) {
                    $devengado = $fila->cantidad * $fila->total;
                    $deducido = "0.00";
                } else {
                    $devengado = "0.00";
                    $deducido = $fila->cantidad * $fila->total;
                }
                if ($fila->detalle) {
                    $detalle = " - (" . $fila->detalle . ")";
                } else {
                    $detalle = "";
                }
                $cont_filas ++;
                $html .= '<tr>'
                        . '<td class="d1 c30 c27 c28">' . $fila->tipo . $detalle . '</td>'
                        . '<td class="d2 a2 c30 c27 c28">' . $fila->cantidad . '</td>'
                        . '<td class="d3 a2 c30 c27 c28">$' . number_format($fila->total, 1, '.', ',') . '</td>'
                        . '<td class="d4 a2 c30 c27 c28">$' . number_format($devengado, 1, '.', ',') . '</td>'
                        . '<td class="d5 a2 c30 c27 c28">$' . number_format($deducido, 1, '.', ',') . '</td>'
                        . '</tr>';
            }
//            for ($i = $cont_filas; $i < 5; $i++) {
            $html .= '<tr><td class="d1 c27 c28 c30"></td><td class="d2 c27 c28 c30"></td><td class="d3 c27 c28 c30"></td><td class="d4 c27 c28 c30"></td><td class="d5 c27 c28 c30"></td></tr>';
            $html .= '</table><table>';
            if ($nomina->observacion != "") {
                $html .= '<tr><td class="c10 c25 c27 c28" colspan="4"> </td></tr><tr><td class="a3 c30 c27 c28" colspan="4"><b>Observaciones: </b>' . $nomina->observacion . '.</td></tr>'
                        . '<tr><td class="c10 c26 c27 c28" colspan="4"> </td></tr>';
            }
            $html .= '<tr>'
                    . '<td class="c20 a2 c25 c26 c27 c28" rowspan="3"><br><br><br><br><br>___________________________<br>Firma empleado</td>'
                    . '<td class="c20 a2 c25 c26 c27 c28" rowspan="3"><br><br><br><br><br>___________________________<br>Firma y sello empresa</td>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total devengados (+)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total_devengado, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total deducidos (-)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total_deducido, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total percibido (=)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '</table><p class="b3">- Copia para el empleado -</p>';
            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            // reset pointer to the last page
            $pdf->lastPage();

            $pdf->AddPage();
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:30px;font-weight: bold;font-style: italic;line-height:40px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:10px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:100px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:100px;}';
            $html .= 'td.c11{width:150px;}';
            $html .= 'td.c4{width:265px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c9{width:115px;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c20{width:240px;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c21{width:140px;height:33px;line-height:32px;font-weight: bold;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c22{width:110px;height:33px;line-height:32px;font-weight: bold;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;line-height:25px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;height:30px;line-height:25px;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.c29{background-color:#F5F5F5;}';
            $html .= 'td.c30{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'td.a3{text-align:justify;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'th.a3{background-color:#F5F5F5;}';
            $html .= 'th.d1{width:310px;}';
            $html .= 'th.d2{width:80px;}';
            $html .= 'th.d3{width:120px;}';
            $html .= 'th.d4{width:110px;}';
            $html .= 'th.d5{width:110px;}';
            $html .= 'th.d6{height:30px;line-height:25px;}';
            $html .= 'th.d7{border-top-color:#000000;border-bottom-color:#000000;border-left-color:#000000;border-right-color:#000000;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '<p class="b1">Medellín: Calle 47D # 77 AA - 67  (Floresta)  / Tels.: 4114107 – 4126800<br>'
                    . 'Medellín: Carrera 48B # 10 SUR - 118 (Poblado) / Tels.: 3128614 – 3126060<br>'
                    . 'Cali Sur: Carrera 44 # 5A – 26 (Tequendama) / Tels.: 3818008 – 3926723<br>'
                    . 'Cali Norte: Calle 25 # Norte 6A – 32 (Santa Mónica) / Tels.: 3816803 – 3816734<br>'
                    . 'Bucaramanga: Carrera 33 # 54 – 91 (Cabecera) / Tels.: 6832612 – 6174057<br>'
                    . 'Montería: Calle 58 # 6 – 39 (Castellana) / Tels.:7957110 – 7957110<br>'
                    . 'Montelíbano: Calle 17 # 13 2do piso / Tels.: 7625202 – 7625650<br>'
                    . 'Santa Marta: Carrera 13 B # 27 B – 84  (B. Bavaria) / Tels.: 4307566 – 4307570<br>'
                    . 'El Bagre: Calle 1 # 32 (Cornaliza) / Tels.: 8372645 – 8372653<br>'
                    . 'Caucasia: Carrera 8A # 22 – 48. 2do Piso (B. Kennedy) / Tels.: 8391693 - 8393582</p>'
                    . '</td>'
                    . '<td class="c2 a2 c1000"  colspan="2"></td>'
                    . '<br>'
                    . '</tr><tr>'
                    . '<td class="a2 c24" colspan="2">NÓMINA LABORAL</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . $id_nomina_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . date("Y-m-d", strtotime($nomina->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . $nomina->responsable . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Periodicidad:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nomina->tipo_periodicidad . '</td>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Periodo:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">Del ' . $nomina->fecha_inicio . ' al ' . $nomina->fecha_fin . '</td>'
                    . '</tr></table>'
                    . '<table><tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Días nómina:</b></td><td class="c9 c23 c12 c25 c26 c27 c28">' . $nomina->dias_nomina . '</td>'
                    . '<td class="c11 c23 c12 c25 c26 c27 c28"><b>Días remunerados:</b></td><td class="c3 c23 c12 c25 c26 c27 c28">' . $nomina->dias_remunerados . '</td>'
                    . '<td class="c11 c23 c12 c25 c26 c27 c28"><b>Ausencias:</b></td><td class="c9 c23 c12 c25 c26 c27 c28">' . $nomina->ausencias . '</td>'
                    . '</tr></table>'
                    . '<table><tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Emplead' . $empleado_a . ':</b></td><td class="c4 c23 c25 c26 c27 c28">' . $nomina->empleado . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Documento:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $dni_abreviado_empleado . ' ' . $nomina->id_empleado . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Departamento:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $nomina->departamento . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Cargo:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $cargo . '</td>'
                    . '</tr>'
                    . '</table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<th class="d1 c23 d6 a2 d7 a3"><b>Concepto</b></th>'
                    . '<th class="d2 c23 d6 a2 d7 a3"><b>Cantidad</b></th>'
                    . '<th class="d3 c23 d6 a2 d7 a3"><b>Valor unitario</b></th>'
                    . '<th class="d4 c23 d6 a2 d7 a3"><b>Devengado</b></th>'
                    . '<th class="d5 c23 d6 a2 d7 a3"><b>Deducido</b></th>'
                    . '</tr>';
            $cont_filas = 0;
            foreach ($conceptos_nomina as $fila) {
                if ($fila->debito_credito == 1) {
                    $devengado = $fila->cantidad * $fila->total;
                    $deducido = "0.00";
                } else {
                    $devengado = "0.00";
                    $deducido = $fila->cantidad * $fila->total;
                }
                if ($fila->detalle) {
                    $detalle = " - (" . $fila->detalle . ")";
                } else {
                    $detalle = "";
                }
                $cont_filas ++;
                $html .= '<tr>'
                        . '<td class="d1 c30 c27 c28">' . $fila->tipo . $detalle . '</td>'
                        . '<td class="d2 a2 c30 c27 c28">' . $fila->cantidad . '</td>'
                        . '<td class="d3 a2 c30 c27 c28">$' . number_format($fila->total, 1, '.', ',') . '</td>'
                        . '<td class="d4 a2 c30 c27 c28">$' . number_format($devengado, 1, '.', ',') . '</td>'
                        . '<td class="d5 a2 c30 c27 c28">$' . number_format($deducido, 1, '.', ',') . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td class="d1 c27 c28 c30"></td><td class="d2 c27 c28 c30"></td><td class="d3 c27 c28 c30"></td><td class="d4 c27 c28 c30"></td><td class="d5 c27 c28 c30"></td></tr>';
            $html .= '</table><table>';
            if ($nomina->observacion != "") {
                $html .= '<tr><td class="c10 c25 c27 c28" colspan="4"> </td></tr><tr><td class="a3 c30 c27 c28" colspan="4"><b>Observaciones: </b>' . $nomina->observacion . '.</td></tr>'
                        . '<tr><td class="c10 c26 c27 c28" colspan="4"> </td></tr>';
            }
            $html .= '<tr>'
                    . '<td class="c20 a2 c25 c26 c27 c28" rowspan="3"><br><br><br><br><br>___________________________<br>Firma empleado</td>'
                    . '<td class="c20 a2 c25 c26 c27 c28" rowspan="3"><br><br><br><br><br>___________________________<br>Firma y sello empresa</td>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total devengados (+)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total_devengado, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total deducidos (-)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total_deducido, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total percibido (=)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';
//
//// Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Nómina ' . $id_nomina_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'nomina/consultar/');
        }
    }

    function anular() {
        $data["tab"] = "anular_nomina";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "nomina/validar_anular";
        $data['action_crear'] = base_url() . "nomina/insertar_anular";
        $data['action_recargar'] = base_url() . "nomina/anular";
        $data['action_validar_transaccion_anular'] = base_url() . "nomina/validar_transaccion_anular";
        $this->parser->parse('nomina/anular', $data);
        $this->load->view('footer');
    }

    function validar_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('prefijo', 'Prefijo de sede', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Consecutivo', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
            if ($this->form_validation->run() == FALSE) {
                echo form_error('prefijo') . form_error('id') . form_error('observacion');
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
            $prefijo = $this->input->post('prefijo');
            $id = $this->input->post('id');
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $t_trans = '9'; //Nómina laboral
            $credito_debito = '0'; //Débito
            $vigente = '0'; //Anulado

            $data["tab"] = "anular_nomina";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "nomina/anular";
            $data['msn_recrear'] = "Anular otra nómina";
            $error = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito, $vigente);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->update_model->nomina_vigente($prefijo, $id, $vigente);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $conceptos_nomina = $this->nominam->concepto_nomina_prefijo_id($prefijo, $id);
                    foreach ($conceptos_nomina as $fila) {
                        //Los conceptos que sean de comision de escalas, se pasarán a pendientes para volver a hacer la nomina
                        if (($fila->t_concepto_nomina != '28') && ($fila->t_concepto_nomina != '29')) {
                            $est_concepto = '3'; //3: Anulado
                            $error2 = $this->update_model->concepto_nomina_estado($fila->id, $est_concepto);
                            if (isset($error2)) {
                                $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                                $this->parser->parse('trans_error', $data);
                                return;
                            }
                        } else {
                            $est_concepto = '2'; //2: Pendiente
                            $error2 = $this->update_model->concepto_nomina_estado($fila->id, $est_concepto);
                            if (isset($error2)) {
                                $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                                $this->parser->parse('trans_error', $data);
                                return;
                            }
                        }
                    }
                    //SI se intenta anular una factura 2 veces dará error por primary key duplicate
                    $error3 = $this->insert_model->anular_transaccion($t_trans, $prefijo, $id, $observacion, $id_responsable, $dni_responsable);
                    if (isset($error3)) {
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

    public function validar_transaccion_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $prefijo = $this->input->post('prefijo');
            $id = $this->input->post('id');
            $this->load->model('nominam');
            $nomina = $this->nominam->nomina_prefijo_id($prefijo, $id);
            if ($nomina == TRUE) {
                if ($nomina->vigente == 1) {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $nomina->empleado . '</td>
                            <td class="text-center">Del ' . $nomina->fecha_inicio . ' al ' . $nomina->fecha_fin . '</td>                                
                            <td class="text-center">$' . number_format($nomina->total, 2, '.', ',') . '</td>
                            <td class="text-center">' . $nomina->sede_caja . '-' . $nomina->tipo_caja . '</td>
                            <td class="text-center">$' . number_format($nomina->efectivo_retirado, 2, '.', ',') . '</td>
                            <td class="text-center">' . $nomina->cuenta_origen . '</td>
                            <td class="text-center">$' . number_format($nomina->valor_retirado, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $nomina->responsable . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($nomina->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>La nómina, ya se encuentra anulada.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La nómina, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
