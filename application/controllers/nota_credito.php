<?php

class Nota_credito extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

//Crear: Egreso
    function crear() {
        $data["tab"] = "crear_nota_credito";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['action_validar'] = base_url() . "nota_credito/validar";
        $data['action_crear'] = base_url() . "nota_credito/insertar";
        $data['action_recargar'] = base_url() . "nota_credito/crear";
        $data['action_validar_matricula'] = base_url() . "nota_credito/validar_matricula";
        $data['action_llena_cuenta_responsable'] = base_url() . "nota_credito/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "nota_credito/llena_caja_responsable";
        $this->parser->parse('nota_credito/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('matricula', 'Número de matrícula', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('autoriza', 'Quién autoriza', 'required|trim|xss_clean|max_length[50]');
            $this->form_validation->set_rules('motivo', 'Motivo de la devolución', 'required|trim|xss_clean|max_length[255]');
            $this->form_validation->set_rules('total', 'Valor de la devolución', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            $this->form_validation->set_rules('total_abonos', 'Total de abonos', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $error_total = "";
            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                $total_abonos = round(str_replace(",", "", $this->input->post('total_abonos')), 2);
                if ($total > $total_abonos) {
                    $error_total = "<p>El valor de la devolución, No puede superar el total de abonos realizados a la matrícula: $" . number_format(($total_abonos), 2, '.', ',') . ".</p>";
                } else {
                    if ($this->input->post('total')) {
                        $total = round(str_replace(",", "", $this->input->post('total')), 2);
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
                            $error_valores = "<p>La suma del valor retirado de una cuenta y el efectivo retirado de una caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_retirado + $efectivo_retirado), 2, '.', ',') . ".</p>";
                        }
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_total != "") || ($error_valores != "")) {
                echo form_error('matricula') . form_error('autoriza') . form_error('motivo') . form_error('total') . $error_total . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion') . form_error('total_abonos');
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
            $matricula = $this->input->post('matricula');
            $autoriza = $this->input->post('autoriza');
            $motivo = $this->input->post('motivo');
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
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
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_nota_credito = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_nota_credito = ($this->select_model->nextId_nota_credito($prefijo_nota_credito)->id) + 1;
            $t_trans = 10; //Nota credito
            $credito_debito = 0; //Debito  
            //Para tirar array a json utilizar comillas dobles, decodificar en utf8  
            $matricula_completa = $this->select_model->matricula_id($matricula);
            $titular = $this->select_model->titular($matricula_completa->id_titular, $matricula_completa->dni_titular);
            $detalle_array = array(
                "Matrícula" => $matricula,
                "Titular" => $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1,
                "Id_Titular" => $titular->id,
                "Autorizó" => $autoriza,
                "Motivo" => $motivo,        
                "Observación" => $observacion
            );
            $detalle_json = json_encode($detalle_array);                         

            $data["tab"] = "crear_nota_credito";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "nota_credito/crear";
            $data['msn_recrear'] = "Crear otra nota crédito";
            $data['url_imprimir'] = base_url() . "nota_credito/consultar_pdf/" . $prefijo_nota_credito . "_" . $id_nota_credito . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_nota_credito, $id_nota_credito, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $detalle_json, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->nota_credito($prefijo_nota_credito, $id_nota_credito, $matricula, $autoriza, $motivo, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $this->parser->parse('trans_success_print', $data);
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function validar_matricula() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $id_matricula = $this->input->post('matricula');
            $matricula = $this->select_model->matricula_id($id_matricula);
            if ($matricula == TRUE) {
                $total_abonos = $this->select_model->total_abonos_matricula($id_matricula);
                $titular = $this->select_model->titular($matricula->id_titular, $matricula->dni_titular);
                $plan_matricula = $this->select_model->t_plan_id($matricula->plan);
                $response = array(
                    'respuesta' => 'OK',
                    'nombreTitular' => $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . " " . $titular->apellido2,
                    'totalAbonos' => number_format($total_abonos->total, 2, '.', ','),
                    'nombrePlan' => $plan_matricula->nombre . " " . $plan_matricula->anio,
                    'valorPlan' => $plan_matricula->valor_total
                );
                echo json_encode($response);
                return false;
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><center><strong>La matrícula no existe en la base de datos.</strong></center></p>'
                );
                echo json_encode($response);
                return false;
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
        $data["tab"] = "consultar_nota_credito";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "nota_credito/consultar_validar";
        $data['action_recargar'] = base_url() . "nota_credito/consultar";
        $this->parser->parse('nota_credito/consultar', $data);
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
            $nota_credito = $this->select_model->nota_credito_prefijo_id($prefijo, $id);
            if ($nota_credito == TRUE) {
                if ($nota_credito->vigente == 0) {
                    $error_transaccion = "La nota crédito, se encuentra anulada.";
                }
            } else {
                $error_transaccion = "La nota crédito, no existe en la base de datos.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_nota_credito";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
            $data['action_crear'] = base_url() . "nota_credito/consultar_validar";
            $this->parser->parse('nota_credito/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "nota_credito/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_nota_credito, $salida_pdf) {
        $nota_credito_prefijo_id = $id_nota_credito;
        $id_nota_credito_limpio = str_replace("_", " ", $nota_credito_prefijo_id);
        list($prefijo, $id) = explode("_", $nota_credito_prefijo_id);
        $nota_credito = $this->select_model->nota_credito_prefijo_id($prefijo, $id);
        if ($nota_credito == TRUE) {
            $responsable = $this->select_model->empleado($nota_credito->id_responsable, $nota_credito->dni_responsable);
            $matricula = $this->select_model->matricula_id($nota_credito->matricula);
            $dni_abreviado_titular = $this->select_model->t_dni_id($matricula->dni_titular)->abreviacion;
            $titular = $this->select_model->titular($matricula->id_titular, $matricula->dni_titular);

            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Nota crédito ' . $id_nota_credito_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Nota crédito ' . $id_nota_credito_limpio . ' Sili S.A.S');
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
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:22px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:9px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'p.b4{line-height:23px;}';
            $html .= 'p.b5{font-size:14px;}';
            $html .= 'p.b6{line-height:26px;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:100px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:150px;}';
            $html .= 'td.c4{width:270px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:40px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:20px;}';
            $html .= 'td.c13{line-height:25px;}';
            $html .= 'td.c14{width:365px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;line-height:35px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'td.a3{text-align:justify;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
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
                    . '<td class="c24 a2" colspan="2">NOTA CRÉDITO</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_nota_credito_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($nota_credito->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor devuelto:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($nota_credito->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Matrícula:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $matricula->contrato . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre titutlar:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Documento titular:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_titular . ' ' . $titular->id . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c9 a2 c13 c25 c26 c27 c28"><b>DETALLES DE LA NOTA CRÉDITO</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Autorizó: </b>' . $nota_credito->autoriza . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Motivo de la devolución: </b>' . $nota_credito->motivo . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>';
            if (($nota_credito->observacion) != "") {
                $html .= '<tr><td class="a3"><b>Observaciones: </b>' . $nota_credito->observacion . '.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>';
            }
            $html .= '</table>'
                    . '</td>'
                    . '</tr>'
                    . '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><p class="b5 b6">Firma titular: ___________________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para el titular -</p>';

            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->lastPage();
            $pdf->AddPage();
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:22px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:9px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'p.b4{line-height:23px;}';
            $html .= 'p.b5{font-size:14px;}';
            $html .= 'p.b6{line-height:26px;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:100px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:150px;}';
            $html .= 'td.c4{width:270px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:40px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:20px;}';
            $html .= 'td.c13{line-height:25px;}';
            $html .= 'td.c14{width:365px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;line-height:35px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'td.a3{text-align:justify;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
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
                    . '<td class="c24 a2" colspan="2">NOTA CRÉDITO</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_nota_credito_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($nota_credito->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor devuelto:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($nota_credito->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Matrícula:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $matricula->contrato . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre titutlar:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Documento titular:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_titular . ' ' . $titular->id . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c9 a2 c13 c25 c26 c27 c28"><b>DETALLES DE LA NOTA CRÉDITO</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Autorizó: </b>' . $nota_credito->autoriza . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Motivo de la devolución: </b>' . $nota_credito->motivo . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>';
            if (($nota_credito->observacion) != "") {
                $html .= '<tr><td class="a3"><b>Observaciones: </b>' . $nota_credito->observacion . '.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>';
            }
            $html .= '</table>'
                    . '</td>'
                    . '</tr>'
                    . '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><p class="b5 b6">Firma titular: ___________________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';
            $pdf->writeHTML($html, true, false, true, false, '');


            $nombre_archivo = utf8_decode('Nota crédito ' . $id_nota_credito_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'nota_credito/consultar/');
        }
    }

    function anular() {
        $data["tab"] = "anular_nota_credito";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "nota_credito/validar_anular";
        $data['action_crear'] = base_url() . "nota_credito/insertar_anular";
        $data['action_recargar'] = base_url() . "nota_credito/anular";
        $data['action_validar_transaccion_anular'] = base_url() . "nota_credito/validar_transaccion_anular";
        $this->parser->parse('nota_credito/anular', $data);
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
            $t_trans = '10'; //Nota Credito  
            $credito_debito = '0'; //Débito
            $vigente = '0'; //Anulado

            $data["tab"] = "anular_nota_credito";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "nota_credito/anular";
            $data['msn_recrear'] = "Anular otra nota crédito";
            
            $this->load->model('transaccionesm');            
            $movimiento_transaccion = $this->transaccionesm->movimiento_transaccion_id($t_trans, $prefijo, $id, $credito_debito);
            //Con el segundo argumento de jsondecode el true, convierto de objeto a array
            if (is_array(json_decode($movimiento_transaccion->detalle_json, true))) {
                $array_detalles = json_decode($movimiento_transaccion->detalle_json, true);
            }
            $responsable = $this->select_model->empleado($id_responsable, $dni_responsable);
            $array_detalles['Observación_Anulación'] = $observacion;
            $array_detalles['Responsable_Anulación'] = $responsable->nombre1 . " " . $responsable->nombre2 . " " . $responsable->apellido1;
            $array_detalles['Id_Responsable_Anulación'] = $id_responsable;
            $detalle_json = json_encode($array_detalles);

            $error = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito, $vigente, $detalle_json);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->update_model->nota_credito_vigente($prefijo, $id, $vigente);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $error2 = $this->insert_model->anular_transaccion($t_trans, $prefijo, $id, $observacion, $id_responsable, $dni_responsable);
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

    public function validar_transaccion_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $prefijo = $this->input->post('prefijo');
            $id = $this->input->post('id');
            $this->load->model('nota_creditom');
            $nota_credito = $this->nota_creditom->nota_credito_prefijo_id($prefijo, $id);
            if ($nota_credito == TRUE) {
                if ($nota_credito->vigente == 1) {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $nota_credito->matricula . '</td>
                            <td class="text-center">$' . number_format($nota_credito->total, 2, '.', ',') . '</td>
                            <td class="text-center">' . $nota_credito->sede_caja . '-' . $nota_credito->tipo_caja . '</td>
                            <td class="text-center">$' . number_format($nota_credito->efectivo_retirado, 2, '.', ',') . '</td>
                            <td class="text-center">' . $nota_credito->cuenta_origen . '</td>
                            <td class="text-center">$' . number_format($nota_credito->valor_retirado, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $nota_credito->responsable . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($nota_credito->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>La nota crédito, ya se encuentra anulada.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La nota crédito, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
