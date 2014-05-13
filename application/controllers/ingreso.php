<?php

class Ingreso extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Ingreso
    function crear() {
        $data["tab"] = "crear_ingreso";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['t_ingreso'] = $this->select_model->t_ingreso();
        $data['t_depositante'] = $this->select_model->t_usuario_ingreso_egreso();
        $data['dni'] = $this->select_model->t_dni_todos();
        $data['action_validar'] = base_url() . "ingreso/validar";
        $data['action_crear'] = base_url() . "ingreso/insertar";
        $data['action_llena_cuenta_responsable'] = base_url() . "ingreso/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "ingreso/llena_caja_responsable";

        $this->parser->parse('ingreso/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('t_ingreso', 'Tipo de Ingreso', 'required|callback_select_default');
            $this->form_validation->set_rules('t_depositante', 'Tipo de Usuario Depositante', 'required|callback_select_default');
            $this->form_validation->set_rules('dni_depositante', 'Tipo Id. Depositante', 'required|callback_select_default');
            $this->form_validation->set_rules('id_depositante', 'Número Id. Depositante', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            if ($this->input->post('t_depositante') == '6') {
                $this->form_validation->set_rules('nombre_depositante', 'Nombre Depositante', 'required|trim|xss_clean|max_length[100]');
            }
            $this->form_validation->set_rules('total', 'Valor del Ingreso', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');

            //Validamos que los usuarios si existan
            $error_key_exists = "";
            if (($this->input->post('t_depositante') != "default") && ($this->input->post('dni_depositante') != "default") && $this->input->post('id_depositante')) {
                if ($this->input->post('t_depositante') == '1') {
                    $t_usuario = 1; //Empleado
                    $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $t_usuario);
                    if ($check_usuario != TRUE) {
                        $error_key_exists = "<p>El Empleado ingresado, no existe en la Base de Datos.</p>";
                    }
                } else {
                    if ($this->input->post('t_depositante') == '2') {
                        $t_usuario = 2; //Titular
                        $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $t_usuario);
                        if ($check_usuario != TRUE) {
                            $error_key_exists = "<p>El Titular ingresado, no existe en la Base de Datos.</p>";
                        }
                    } else {
                        if ($this->input->post('t_depositante') == '3') {
                            $t_usuario = 3; //Alumno
                            $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $t_usuario);
                            if ($check_usuario != TRUE) {
                                $error_key_exists = "<p>El Alumno ingresado, no existe en la Base de Datos.</p>";
                            }
                        } else {
                            if ($this->input->post('t_depositante') == '4') {
                                $t_usuario = 4; //Cliente Prestatario
                                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_depositante'), $this->input->post('dni_depositante'), $t_usuario);
                                if ($check_usuario != TRUE) {
                                    $error_key_exists = "<p>El Cliente Prestatario ingresado, no existe en la Base de Datos.</p>";
                                }
                            } else {
                                if ($this->input->post('t_depositante') == '5') {
                                    $check_usuario = $this->select_model->proveedor_id_dni($this->input->post('id_depositante'), $this->input->post('dni_depositante'));
                                    if ($check_usuario != TRUE) {
                                        $error_key_exists = "<p>El Proveedor ingresado, no existe en la Base de Datos.</p>";
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $error_valores = "";
            if ($this->input->post('total')) {
                $total = round(str_replace(",", "", $this->input->post('total')), 2);
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

            if ((($this->input->post('t_ingreso')) == "4") || (($this->input->post('t_ingreso')) == "5")) { //t_ingreso = 4: Otros
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[255]');
            } else {
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|max_length[255]');
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_key_exists != "")) {
                echo form_error('t_ingreso') . form_error('t_depositante') . form_error('dni_depositante') . form_error('id_depositante') . form_error('nombre_depositante') . $error_key_exists . form_error('total') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . form_error('descripcion');
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
            $t_ingreso = $this->input->post('t_ingreso');
            $t_depositante = $this->input->post('t_depositante');
            $dni_depositante = $this->input->post('dni_depositante');
            $id_depositante = $this->input->post('id_depositante');
            if ($dni_depositante != "6") {
                $d_v = NULL;
            } else {
                $d_v = $this->input->post('d_v');
            }
            if (($t_depositante == 1) || ($t_depositante == 2) || ($t_depositante == 3) || ($t_depositante == 4) || ($t_depositante == 5)) {
                $nombre_depositante = NULL;
            } else {
                $nombre_depositante = ucwords(strtolower($this->input->post('nombre_depositante')));
            }
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
            $descripcion = ucfirst(strtolower($this->input->post('descripcion')));

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_ingreso = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_ingreso = ($this->select_model->nextId_ingreso($prefijo_ingreso)->id) + 1;
            $t_trans = 5; //Ingreso
            $credito_debito = 1; //Credito

            $data["tab"] = "crear_ingreso";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "ingreso/crear";
            $data['msn_recrear'] = "Crear otro Ingreso";
            $data['url_imprimir'] = base_url() . "ingreso/consultar_pdf/" . $prefijo_ingreso . "_" . $id_ingreso . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_ingreso, $id_ingreso, $credito_debito, $total, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->ingreso($prefijo_ingreso, $id_ingreso, $t_ingreso, $t_depositante, $id_depositante, $dni_depositante, $d_v, $nombre_depositante, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $vigente, $descripcion, $id_responsable, $dni_responsable);
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
        $data["tab"] = "consultar_ingreso";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede();
        $data['error_consulta'] = "";        
        $data['action_crear'] = base_url() . "ingreso/consultar_validar";
        $data['action_recargar'] = base_url() . "ingreso/consultar";
        $this->parser->parse('ingreso/consultar', $data);
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
            $ingreso = $this->select_model->ingreso_prefijo_id($prefijo, $id);
            if ($ingreso == TRUE) {
                if ($ingreso->vigente == 0) {
                    $error_transaccion = "El ingreso, se encuentra anulado.";
                }
            } else {
                $error_transaccion = "El ingreso, no existe en la base de datos.";
            }            
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_ingreso";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede();
            $data['action_crear'] = base_url() . "ingreso/consultar_validar";
            $this->parser->parse('ingreso/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "ingreso/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_ingreso, $salida_pdf) {
        $ingreso_prefijo_id = $id_ingreso;
        $id_ingreso_limpio = str_replace("_", " ", $ingreso_prefijo_id);
        list($prefijo, $id) = explode("_", $ingreso_prefijo_id);
        $ingreso = $this->select_model->ingreso_prefijo_id($prefijo, $id);
        if ($ingreso == TRUE) {
            $reponsable = $this->select_model->empleado($ingreso->id_responsable, $ingreso->dni_responsable);
            $dni_abreviado_depositante = $this->select_model->t_dni_id($ingreso->dni_depositante)->abreviacion;
            if (($ingreso->d_v) == (NULL)) {
                $d_v = "";
            } else {
                $d_v = " - " . $ingreso->d_v;
            }
            $t_ingreso = $this->select_model->t_ingreso_id($ingreso->t_ingreso)->tipo;
            $t_depositante = $this->select_model->t_usuario_id($ingreso->t_depositante)->tipo;
            if ($ingreso->t_depositante == '1') {
                $depositante = $this->select_model->empleado($ingreso->id_depositante, $ingreso->dni_depositante);
                $nombre_depositante = $depositante->nombre1 . " " . $depositante->nombre2 . " " . $depositante->apellido1 . " " . $depositante->apellido2;
            } else {
                if ($ingreso->t_depositante == '2') {
                    $depositante = $this->select_model->titular($ingreso->id_depositante, $ingreso->dni_depositante);
                    $nombre_depositante = $depositante->nombre1 . " " . $depositante->nombre2 . " " . $depositante->apellido1 . " " . $depositante->apellido2;
                } else {
                    if ($ingreso->t_depositante == '3') {
                        $depositante = $this->select_model->alumno($ingreso->id_depositante, $ingreso->dni_depositante);
                        $nombre_depositante = $depositante->nombre1 . " " . $depositante->nombre2 . " " . $depositante->apellido1 . " " . $depositante->apellido2;
                    } else {
                        if ($ingreso->t_depositante == '4') {
                            $depositante = $this->select_model->cliente_id_dni($ingreso->id_depositante, $ingreso->dni_depositante);
                            $nombre_depositante = $depositante->nombre1 . " " . $depositante->nombre2 . " " . $depositante->apellido1 . " " . $depositante->apellido2;
                        } else {
                            if ($ingreso->t_depositante == '5') {
                                $nombre_depositante = $this->select_model->proveedor_id_dni($ingreso->id_depositante, $ingreso->dni_depositante)->razon_social;
                            } else {
                                $nombre_depositante = $ingreso->nombre_depositante;
                            }
                        }
                    }
                }
            }


            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Comprobante de ingreso ' . $id_ingreso_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Comprobante de ingreso ' . $id_ingreso_limpio . ' Sili S.A.S');
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
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:10px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'p.b4{line-height:23px;}';
            $html .= 'p.b5{font-size:14px;}';
            $html .= 'p.b6{line-height:26px;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:85px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:150px;}';
            $html .= 'td.c4{width:270px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:50px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:25px;}';
            $html .= 'td.c13{width:580px;}';
            $html .= 'td.c14{width:365px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;line-height:35px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p><p class="b2">Resolución DIAN No. 110000497290 del 16/08/2012</p>'
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
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE INGRESO</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_ingreso_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($ingreso->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor del ingreso:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($ingreso->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Tipo depositante:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $t_depositante . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Tipo de ingreso:</b></td><td  colspan="3" class="c23 c25 c26  c27 c28 c12 c13">' . $t_ingreso . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre depositante:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nombre_depositante . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Documento depositante:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_depositante . ' ' . $ingreso->id_depositante . $d_v . '</td>'
                    . '</tr>';
            if (($ingreso->descripcion) != "") {
                $html .= '<tr>'
                        . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                        . '<table>'
                        . '<tr><td class="c10"> </td></tr><tr>'
                        . '<td><b>Descripción del ingreso: </b>' . $ingreso->descripcion . '.</td>'
                        . '</tr><tr><td class="c10"> </td></tr>'
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma depositante: _____________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para el depositante -</p>';

            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->lastPage();
            $pdf->AddPage();
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:22px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:10px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'p.b4{line-height:23px;}';
            $html .= 'p.b5{font-size:14px;}';
            $html .= 'p.b6{line-height:26px;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:85px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:150px;}';
            $html .= 'td.c4{width:270px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:50px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:25px;}';
            $html .= 'td.c13{width:580px;}';
            $html .= 'td.c14{width:365px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;line-height:35px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p><p class="b2">Resolución DIAN No. 110000497290 del 16/08/2012</p>'
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
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE INGRESO</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_ingreso_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($ingreso->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor del ingreso:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($ingreso->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Tipo depositante:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $t_depositante . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Tipo de ingreso:</b></td><td  colspan="3" class="c23 c25 c26  c27 c28 c12 c13">' . $t_ingreso . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre depositante:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nombre_depositante . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Documento depositante:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_depositante . ' ' . $ingreso->id_depositante . $d_v . '</td>'
                    . '</tr>';
            if (($ingreso->descripcion) != "") {
                $html .= '<tr>'
                        . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                        . '<table>'
                        . '<tr><td class="c10"> </td></tr><tr>'
                        . '<td><b>Descripción del ingreso: </b>' . $ingreso->descripcion . '.</td>'
                        . '</tr><tr><td class="c10"> </td></tr>'
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma depositante: _____________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';
//
// Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Comprobante de ingreso ' . $id_ingreso_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'ingreso/consultar/');
        }
    }

}
