<?php

class Abono_adelanto extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Abono a Adelanto de nomina
    function crear() {
        $data["tab"] = "crear_abono_adelanto";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);

        $data['action_validar'] = base_url() . "abono_adelanto/validar";
        $data['action_crear'] = base_url() . "abono_adelanto/insertar";

        $data['action_llena_empleado_adelanto'] = base_url() . "abono_adelanto/llena_empleado_adelanto";
        $data['action_llena_adelanto_empleado'] = base_url() . "abono_adelanto/llena_adelanto_empleado";
        $data['action_llena_cuenta_responsable'] = base_url() . "abono_adelanto/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "abono_adelanto/llena_caja_responsable";

        $this->parser->parse('abono_adelanto/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            $this->form_validation->set_rules('adelanto', 'Adelanto a Abonar', 'required');
            $this->form_validation->set_rules('total', 'Valor del Abono', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
                list($prefijo_adelanto, $id_adelanto, $saldo) = explode("-", $this->input->post('adelanto'));
                if ($total > $saldo) {
                    $error_valores = "<p>El valor del abono no puede ser mayor que el saldo del adelanto: $" . number_format($saldo, 2, '.', ',') . ".</p>";
                } else {
                    if (!$this->input->post('valor_consignado')) {
                        $valor_consignado = 0;
                    } else {
                        $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
                    }
                    if (!$this->input->post('efectivo_ingresado')) {
                        $efectivo_ingresado = 0;
                    } else {
                        $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
                    }
                    if (round(($valor_consignado + $efectivo_ingresado), 2) != $total) {
                        $error_valores = "<p>La suma del valor consignado a la cuenta y el efectivo ingresado a la caja, deben sumar exactamente: $" . $this->input->post('total') . ", en vez de: $" . number_format(($valor_consignado + $efectivo_ingresado), 2, '.', ',') . ".</p>";
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('empleado') . form_error('adelanto') . form_error('total') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . form_error('observacion');
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
            list($prefijo_adelanto, $id_adelanto, $saldo) = explode("-", $this->input->post('adelanto'));
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            if (($this->input->post('cuenta')) && ($this->input->post('valor_consignado')) && ($this->input->post('valor_consignado') != 0)) {
                $cuenta_destino = $this->input->post('cuenta');
                $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
            } else {
                $cuenta_destino = NULL;
                $valor_consignado = NULL;
            }
            if (($this->input->post('caja')) && ($this->input->post('efectivo_ingresado')) && ($this->input->post('efectivo_ingresado') != 0)) {
                list($sede_caja_destino, $t_caja_destino) = explode("-", $this->input->post('caja'));
                $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
            } else {
                $sede_caja_destino = NULL;
                $t_caja_destino = NULL;
                $efectivo_ingresado = NULL;
            }
            $vigente = 1;
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_abono = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_abono = ($this->select_model->nextId_abono_adelanto($prefijo_abono)->id) + 1;
            $t_trans = 3; //Abono a adelanto
            $credito_debito = 1; //Credito

            $data["tab"] = "crear_abono_adelanto";
            $this->isLogin($data["tab"]);               
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "abono_adelanto/crear";
            $data['msn_recrear'] = "Crear otro abono";
            $data['url_imprimir'] = base_url() . "abono_adelanto/consultar_pdf/" . $prefijo_abono . "_" . $id_abono . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_abono, $id_abono, $credito_debito, $total, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->abono_adelanto($prefijo_abono, $id_abono, $prefijo_adelanto, $id_adelanto, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    //Si no hubo error entonces si el saldo es igual al total abonado, entonces lo colocamos paz y salvo
                    if ($total == $saldo) {
                        $new_estado = 3; //Paz y Salvo Voluntario   
                        $this->update_model->adelanto_estado($prefijo_adelanto, $id_adelanto, $new_estado);
                    }
                    $this->parser->parse('trans_success_print', $data);
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_adelanto() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_sedes_responsable_adelantos($id_responsable, $dni_responsable);
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
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

    public function llena_adelanto_empleado() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('empleado')) && ($this->input->post('empleado') != "default")) {
                list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
                $adelantos = $this->select_model->adelanto_vigente_empleado($id_empleado, $dni_empleado);
                if ($adelantos == TRUE) {
                    foreach ($adelantos as $fila) {
                        echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="adelanto" id="adelanto" value="' . $fila->prefijo_adelanto . "-" . $fila->id_adelanto . "-" . $fila->saldo . '"/></td>
                            <td class="text-center">$' . number_format($fila->total, 2, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->saldo, 2, '.', ',') . '</td>
                            <td class="text-center">' . $fila->sede . '</td>
                            <td class="text-center">' . $fila->autoriza . '</td>
                            <td class="text-center">' . $fila->motivo . '</td>
                            <td>' . $fila->forma_descuento . '</td>                                
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
    
    function consultar() {
        $data["tab"] = "consultar_abono_adelanto";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['action_crear'] = base_url() . "abono_adelanto/consultar_validar";
        $data['action_recargar'] = base_url() . "abono_adelanto/consultar";
        $this->parser->parse('abono_adelanto/consultar', $data);
        $this->load->view('footer');
    }

    public function consultar_validar() {
        $this->escapar($_POST);
        $abono_adelanto_prefijo_id = $this->input->post('prefijo_id_abono_adelanto');
        if (!empty($abono_adelanto_prefijo_id)) {
            try {
                list($prefijo, $id) = explode(" ", $abono_adelanto_prefijo_id);
                $abono_adelanto = $this->select_model->abono_adelanto_prefijo_id($prefijo, $id);
                if ($abono_adelanto == TRUE) {
                    redirect(base_url() . "abono_adelanto/consultar_pdf/" . $prefijo . "_" . $id . "/I");                    
                } else {
                    $data["tab"] = "consultar_abono_adelanto";
                    $this->isLogin($data["tab"]);
                    $data["error_consulta"] = "Abono a adelanto de nómina no encontrado.";
                    $this->load->view("header", $data);
                    $data['action_crear'] = base_url() . "abono_adelanto/consultar_validar";
                    $this->parser->parse('abono_adelanto/consultar', $data);
                    $this->load->view('footer');
                }
            } catch (Exception $e) {
                $data["tab"] = "consultar_abono_adelanto";
                $this->isLogin($data["tab"]);
                $data["error_consulta"] = "Error en el formato ingresado del abono: Prefijo + Espacio + Consecutivo.";
                $this->load->view("header", $data);
                $data['action_crear'] = base_url() . "abono_adelanto/consultar_validar";
                $this->parser->parse('abono_adelanto/consultar', $data);
                $this->load->view('footer');
            }
        } else {
            $data["tab"] = "consultar_abono_adelanto";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = "Antes de consultar, ingrese el consecutivo del abono.";
            $this->load->view("header", $data);
            $data['action_crear'] = base_url() . "abono_adelanto/consultar_validar";
            $this->parser->parse('abono_adelanto/consultar', $data);
            $this->load->view('footer');
        }
    }

    function consultar_pdf($id_abono_adelanto, $salida_pdf) {
        $abono_adelanto_prefijo_id = $id_abono_adelanto;
        $id_abono_adelanto_limpio = str_replace("_", " ", $abono_adelanto_prefijo_id);
        list($prefijo, $id) = explode("_", $abono_adelanto_prefijo_id);
        $abono_adelanto = $this->select_model->abono_adelanto_prefijo_id($prefijo, $id);
        $adelanto = $this->select_model->adelanto_prefijo_id($abono_adelanto->prefijo_adelanto, $abono_adelanto->id_adelanto);
        if ($abono_adelanto == TRUE) {
            $empleado = $this->select_model->empleado($adelanto->id_empleado, $adelanto->dni_empleado);
            $dni_abreviado_empleado = $this->select_model->t_dni_id($adelanto->dni_empleado)->abreviacion;            
            $reponsable = $this->select_model->empleado($abono_adelanto->id_responsable, $abono_adelanto->dni_responsable);
            
            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Abono adelanto de nómina ' . $id_abono_adelanto_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Abono adelanto de nómina ' . $id_abono_adelanto_limpio . ' Sili S.A.S');
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
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:24px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:9px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'p.b4{line-height:28px;}';
            $html .= 'p.b5{font-size:14px;}';
            $html .= 'p.b6{line-height:26px;}';
            $html .= 'td.c1{width:420px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:170px;}';
            $html .= 'td.c4{width:250px;}';
            $html .= 'td.c5{width:170px;}';
            $html .= 'td.c6{width:140px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:40px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';            
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:20px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;height:30px;line-height:25px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
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
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2>Sistema Integral Lectura Inteligente S.A.S</h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p><p class="b2">Resolución DIAN No. 110000497290 del 16/08/2012</p>'
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
                    . '<td class="c2 a2"  colspan="2"><img width="150px" height="80px" src="' . base_url() . 'images/logo.png"></td>'
                    . '<br>'
                    . '</tr><tr>'
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE ABONO A ADELANTO DE NÓMINA</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c23 c25 c26  c27 c28">' . $id_abono_adelanto_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28">' . date("Y-m-d", strtotime($abono_adelanto->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table width="100%" border="1">'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Empleado depositante:</b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $empleado->nombre1 . " " . $empleado->nombre2 . " " . $empleado->apellido1 . '</td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8" rowspan="2"><b> Valor del abono:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8"><b>$ ' . number_format($abono_adelanto->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Documento indentidad: </b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $dni_abreviado_empleado . ' ' . $adelanto->id_empleado . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c9 a2 c8"><b>DETALLES DEL ADELANTO</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c23">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr><tr>'
                    . '<td><b>Autorizó: </b>' . $adelanto->autoriza . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr><tr>'
                    . '<td><b>Motivo del adelanto: </b>' . $adelanto->motivo . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'                    
                    . '<tr><td><b>Forma de descuento: </b>' . $adelanto->forma_descuento . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'
                    . '<tr><td><b>Saldo pendiente del adelanto, después de éste abono: </b> $' . number_format($adelanto->saldo, 1, '.', ',') . '</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'                    
                    . '</table>'
                    . '</td>'
                    . '</tr>'
                    . '<tr><td colspan="2" class="c11 a3"><br>Autorizo a la empresa SILI S.A.S, para que en caso de retiro, descuenten de mis pretaciones sociales, el saldo pendiente de éste adelanto.<p class="b4 b5">Firma empleado: ______________________________________</p></td>'
                    . '<td colspan="2"><br><br><p class="b5 b6">Firma y sello empresa: ____________________</p></td></tr>'                    
                    . '</table><p class="b3">- Copia para el empleado -</p>';

            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->lastPage();
            $pdf->AddPage();
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:24px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:9px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'p.b4{line-height:28px;}';
            $html .= 'p.b5{font-size:14px;}';
            $html .= 'p.b6{line-height:26px;}';
            $html .= 'td.c1{width:420px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:170px;}';
            $html .= 'td.c4{width:250px;}';
            $html .= 'td.c5{width:170px;}';
            $html .= 'td.c6{width:140px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:40px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';            
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:20px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;height:30px;line-height:25px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
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
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2>Sistema Integral Lectura Inteligente S.A.S</h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p><p class="b2">Resolución DIAN No. 110000497290 del 16/08/2012</p>'
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
                    . '<td class="c2 a2"  colspan="2"><img width="150px" height="80px" src="' . base_url() . 'images/logo.png"></td>'
                    . '<br>'
                    . '</tr><tr>'
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE ABONO A ADELANTO DE NÓMINA</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c23 c25 c26  c27 c28">' . $id_abono_adelanto_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28">' . date("Y-m-d", strtotime($abono_adelanto->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table width="100%" border="1">'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Empleado depositante:</b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $empleado->nombre1 . " " . $empleado->nombre2 . " " . $empleado->apellido1 . '</td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8" rowspan="2"><b> Valor del abono:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8"><b>$ ' . number_format($abono_adelanto->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Documento indentidad: </b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $dni_abreviado_empleado . ' ' . $adelanto->id_empleado . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c9 a2 c8"><b>DETALLES DEL ADELANTO</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c23">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr><tr>'
                    . '<td><b>Autorizó: </b>' . $adelanto->autoriza . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr><tr>'
                    . '<td><b>Motivo del adelanto: </b>' . $adelanto->motivo . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'                    
                    . '<tr><td><b>Forma de descuento: </b>' . $adelanto->forma_descuento . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'
                    . '<tr><td><b>Saldo pendiente del adelanto, después de éste abono: </b> $' . number_format($adelanto->saldo, 1, '.', ',') . '</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'                    
                    . '</table>'
                    . '</td>'
                    . '</tr>'
                    . '<tr><td colspan="2" class="c11 a3"><br>Autorizo a la empresa SILI S.A.S, para que en caso de retiro, descuenten de mis pretaciones sociales, el saldo pendiente de éste adelanto.<p class="b4 b5">Firma empleado: ______________________________________</p></td>'
                    . '<td colspan="2"><br><br><p class="b5 b6">Firma y sello empresa: ____________________</p></td></tr>'                    
                    . '</table><p class="b3">- Copia para la empresa -</p>';
            
//
// Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');
            
            
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Abono adelanto de nómina ' . $id_abono_adelanto_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'abono_adelanto/consultar/');
        }
    }    

}
