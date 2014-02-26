<?php

class Nomina extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_nomina";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sede_ppal_responsable($id_responsable, $dni_responsable);
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
                    if (($valor_unitario[$i] <= '0') || ($valor_unitario[$i] == '')) {
                        $error_conceptos .= "<p>El campo Valor Unitario, debe ser mayor a cero.</p>";
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
            $t_periodicidad = $this->input->post('periodicidad');
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
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
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
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

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_nomina, $id_nomina, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->nomina($prefijo_nomina, $id_nomina, $id_empleado, $dni_empleado, $t_periodicidad, $fecha_inicio, $fecha_fin, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1;
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
                                    $data['trans_error'] = $error2;
                                    $this->parser->parse('trans_error', $data);
                                    return;
                                }
                                //en el caso en que hayan que crear los conceptos
                            } else {
                                $t_concepto_temp = $t_concepto_nomina[$i];
                                $detalle_temp = strtolower($detalle[$i]);
                                $cantidad_temp = $cantidad[$i];
                                $valor_unitario_temp = round(str_replace(",", "", $valor_unitario[$i]), 2);
                                $error3 = $this->insert_model->concepto_nomina($id_empleado, $dni_empleado, $prefijo_nomina, $id_nomina, $t_concepto_temp, $cantidad_temp, NULL, NULL, NULL, NULL, $cantidad_temp, $valor_unitario_temp, 1, $sede, $id_responsable, $dni_responsable);
                                if (isset($error3)) {
                                    $data['trans_error'] = $error3;
                                    $this->parser->parse('trans_error', $data);
                                    return;
                                }
                            }
                            $i++;
                        }
                        $this->parser->parse('trans_success', $data);
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
                    if ($contrato->cant_meses == NULL) {
                        $duracion = "Indefinido";
                    } else {
                        if ($contrato->cant_meses == 1) {
                            $duracion = $contrato->cant_meses . " mes";
                        } else {
                            $duracion = $contrato->cant_meses . " meses";
                        }
                    }
                    echo '<div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha Inicio</th>
                                                <th class="text-center">Tipo Contrato</th>
                                                <th class="text-center">Duración</th>
                                                <th class="text-center">Cargo</th>                                                
                                                <th class="text-center">Salario</th>
                                                <th class="text-center">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>                         
                                            <td class="text-center">' . $contrato->fecha_inicio . '</td>
                                            <td class="text-center">' . $contrato->tipo_contrato . '</td>
                                            <td class="text-center">' . $duracion . '</td> 
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
                $cuentas = $this->select_model->cuenta_banco_responsable($id_responsable, $dni_responsable);
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
                $cuentas = $this->select_model->caja_responsable($id_responsable, $dni_responsable);
                if (($cuentas == TRUE)) {
                    foreach ($cuentas as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="caja" id="caja" value="' . $fila->sede . "-" . $fila->t_caja . '"/></td>
                            <td class="text-center">' . $fila->name_sede . '</td>
                            <td>' . $fila->name_t_caja . '</td>  
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

}
