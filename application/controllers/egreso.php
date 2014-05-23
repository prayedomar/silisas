<?php

class Egreso extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

//Crear: Egreso
    function crear() {
        $data["tab"] = "crear_egreso";
        $this->isLogin($data["tab"]);        
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['t_egreso'] = $this->select_model->t_egreso();
        $data['t_beneficiario'] = $this->select_model->t_usuario_ingreso_egreso();
        $data['dni'] = $this->select_model->t_dni_todos();
        $data['action_validar'] = base_url() . "egreso/validar";
        $data['action_crear'] = base_url() . "egreso/insertar";
        $data['action_llena_cuenta_responsable'] = base_url() . "egreso/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "egreso/llena_caja_responsable";
        $this->parser->parse('egreso/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('t_egreso', 'Tipo de Egreso', 'required|callback_select_default');
            $this->form_validation->set_rules('t_beneficiario', 'Tipo de Usuario Beneficiario', 'required|callback_select_default');
            $this->form_validation->set_rules('dni_beneficiario', 'Tipo Id. Beneficiario', 'required|callback_select_default');
            $this->form_validation->set_rules('id_beneficiario', 'Número Id. Beneficiario', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            if ($this->input->post('t_beneficiario') == '6') {
                $this->form_validation->set_rules('nombre_beneficiario', 'Nombre Beneficiario', 'required|trim|xss_clean|max_length[100]');
            }
            $this->form_validation->set_rules('total', 'Valor del Egreso', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');

            //Validamos que los usuarios si existan
            $error_key_exists = "";
            if (($this->input->post('t_beneficiario') != "default") && ($this->input->post('dni_beneficiario') != "default") && $this->input->post('id_beneficiario')) {
                if ($this->input->post('t_beneficiario') == '1') {
                    $t_usuario = 1; //Empleado
                    $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $t_usuario);
                    if ($check_usuario != TRUE) {
                        $error_key_exists = "<p>El Empleado ingresado, no existe en la Base de Datos.</p>";
                    }
                } else {
                    if ($this->input->post('t_beneficiario') == '2') {
                        $t_usuario = 2; //Titular
                        $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $t_usuario);
                        if ($check_usuario != TRUE) {
                            $error_key_exists = "<p>El Titular ingresado, no existe en la Base de Datos.</p>";
                        }
                    } else {
                        if ($this->input->post('t_beneficiario') == '3') {
                            $t_usuario = 3; //Alumno
                            $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $t_usuario);
                            if ($check_usuario != TRUE) {
                                $error_key_exists = "<p>El Alumno ingresado, no existe en la Base de Datos.</p>";
                            }
                        } else {
                            if ($this->input->post('t_beneficiario') == '4') {
                                $t_usuario = 4; //Cliente Prestatario
                                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'), $t_usuario);
                                if ($check_usuario != TRUE) {
                                    $error_key_exists = "<p>El Cliente Prestatario ingresado, no existe en la Base de Datos.</p>";
                                }
                            } else {
                                if ($this->input->post('t_beneficiario') == '5') {
                                    $check_usuario = $this->select_model->proveedor_id_dni($this->input->post('id_beneficiario'), $this->input->post('dni_beneficiario'));
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

            if ((($this->input->post('t_egreso')) == "8") || (($this->input->post('t_egreso')) == "9")) { //t_egreso = 8: Otros
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|trim|xss_clean|max_length[255]');
            } else {
                $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|max_length[255]');
            }

            if (($this->form_validation->run() == FALSE) || ($error_valores != "") || ($error_key_exists != "")) {
                echo form_error('t_egreso') . form_error('t_beneficiario') . form_error('dni_beneficiario') . form_error('id_beneficiario') . form_error('nombre_beneficiario') . $error_key_exists . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('descripcion');
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
            $t_egreso = $this->input->post('t_egreso');
            $t_beneficiario = $this->input->post('t_beneficiario');
            $dni_beneficiario = $this->input->post('dni_beneficiario');
            $id_beneficiario = $this->input->post('id_beneficiario');
            if ($dni_beneficiario != "6") {
                $d_v = NULL;
            } else {
                $d_v = $this->input->post('d_v');
            }
            if (($t_beneficiario == 1) || ($t_beneficiario == 2) || ($t_beneficiario == 3) || ($t_beneficiario == 4) || ($t_beneficiario == 5)) {
                $nombre_beneficiario = NULL;
            } else {
                $nombre_beneficiario = ucwords(strtolower($this->input->post('nombre_beneficiario')));
            }
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
            $descripcion = ucfirst(strtolower($this->input->post('descripcion')));
            
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_egreso = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_egreso = ($this->select_model->nextId_egreso($prefijo_egreso)->id) + 1;
            $t_trans = 6; //Egreso
            $credito_debito = 0; //Debito            

            $data["tab"] = "crear_egreso";
            $this->isLogin($data["tab"]);               
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "egreso/crear";
            $data['msn_recrear'] = "Crear otro Egreso";
            $data['url_imprimir'] = base_url() . "egreso/consultar_pdf/" . $prefijo_egreso . "_" . $id_egreso . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_egreso, $id_egreso, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->egreso($prefijo_egreso, $id_egreso, $t_egreso, $t_beneficiario, $id_beneficiario, $dni_beneficiario, $d_v, $nombre_beneficiario, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, 1, $descripcion, $id_responsable, $dni_responsable);
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
        $data["tab"] = "consultar_egreso";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede();
        $data['error_consulta'] = "";        
        $data['action_crear'] = base_url() . "egreso/consultar_validar";
        $data['action_recargar'] = base_url() . "egreso/consultar";
        $this->parser->parse('egreso/consultar', $data);
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
            $egreso = $this->select_model->egreso_prefijo_id($prefijo, $id);
            if ($egreso == TRUE) {
                if ($egreso->vigente == 0) {
                    $error_transaccion = "El egreso, se encuentra anulado.";
                }
            } else {
                $error_transaccion = "El egreso, no existe en la base de datos.";
            }            
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_egreso";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede();
            $data['action_crear'] = base_url() . "egreso/consultar_validar";
            $this->parser->parse('egreso/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "egreso/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_egreso, $salida_pdf) {
        $egreso_prefijo_id = $id_egreso;
        $id_egreso_limpio = str_replace("_", " ", $egreso_prefijo_id);
        list($prefijo, $id) = explode("_", $egreso_prefijo_id);
        $egreso = $this->select_model->egreso_prefijo_id($prefijo, $id);
        if ($egreso == TRUE) {
            $reponsable = $this->select_model->empleado($egreso->id_responsable, $egreso->dni_responsable);
            $dni_abreviado_beneficiario = $this->select_model->t_dni_id($egreso->dni_beneficiario)->abreviacion;
            if (($egreso->d_v) == (NULL)) {
                $d_v = "";
            } else {
                $d_v = " - " . $egreso->d_v;
            }
            $t_egreso = $this->select_model->t_egreso_id($egreso->t_egreso)->tipo;
            $t_beneficiario = $this->select_model->t_usuario_id($egreso->t_beneficiario)->tipo;
            if ($egreso->t_beneficiario == '1') {
                $beneficiario = $this->select_model->empleado($egreso->id_beneficiario, $egreso->dni_beneficiario);
                $nombre_beneficiario = $beneficiario->nombre1 . " " . $beneficiario->nombre2 . " " . $beneficiario->apellido1 . " " . $beneficiario->apellido2;
            } else {
                if ($egreso->t_beneficiario == '2') {
                    $beneficiario = $this->select_model->titular($egreso->id_beneficiario, $egreso->dni_beneficiario);
                    $nombre_beneficiario = $beneficiario->nombre1 . " " . $beneficiario->nombre2 . " " . $beneficiario->apellido1 . " " . $beneficiario->apellido2;
                } else {
                    if ($egreso->t_beneficiario == '3') {
                        $beneficiario = $this->select_model->alumno($egreso->id_beneficiario, $egreso->dni_beneficiario);
                        $nombre_beneficiario = $beneficiario->nombre1 . " " . $beneficiario->nombre2 . " " . $beneficiario->apellido1 . " " . $beneficiario->apellido2;
                    } else {
                        if ($egreso->t_beneficiario == '4') {
                            $beneficiario = $this->select_model->cliente_id_dni($egreso->id_beneficiario, $egreso->dni_beneficiario);
                            $nombre_beneficiario = $beneficiario->nombre1 . " " . $beneficiario->nombre2 . " " . $beneficiario->apellido1 . " " . $beneficiario->apellido2;
                        } else {
                            if ($egreso->t_beneficiario == '5') {
                                $nombre_beneficiario = $this->select_model->proveedor_id_dni($egreso->id_beneficiario, $egreso->dni_beneficiario)->razon_social;
                            } else {
                                $nombre_beneficiario = $egreso->nombre_beneficiario;
                            }
                        }
                    }
                }
            }


            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Comprobante de egreso ' . $id_egreso_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Comprobante de egreso ' . $id_egreso_limpio . ' Sili S.A.S');
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
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE EGRESO</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_egreso_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($egreso->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor del egreso:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($egreso->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Tipo beneficiario:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $t_beneficiario . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Tipo de egreso:</b></td><td  colspan="3" class="c23 c25 c26  c27 c28 c12 c13">' . $t_egreso . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre beneficiario:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nombre_beneficiario . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Documento beneficiario:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_beneficiario . ' ' . $egreso->id_beneficiario . $d_v . '</td>'
                    . '</tr>';
            if (($egreso->descripcion) != "") {
                $html .= '<tr>'
                        . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                        . '<table>'
                        . '<tr><td class="c10"> </td></tr><tr>'
                        . '<td><b>Descripción del egreso: </b>' . $egreso->descripcion . '.</td>'
                        . '</tr><tr><td class="c10"> </td></tr>'
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma beneficiario: _____________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para el beneficiario -</p>';

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
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE EGRESO</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_egreso_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($egreso->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor del egreso:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($egreso->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Tipo beneficiario:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $t_beneficiario . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Tipo de egreso:</b></td><td  colspan="3" class="c23 c25 c26  c27 c28 c12 c13">' . $t_egreso . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre beneficiario:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nombre_beneficiario . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Documento beneficiario:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_beneficiario . ' ' . $egreso->id_beneficiario . $d_v . '</td>'
                    . '</tr>';
            if (($egreso->descripcion) != "") {
                $html .= '<tr>'
                        . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                        . '<table>'
                        . '<tr><td class="c10"> </td></tr><tr>'
                        . '<td><b>Descripción del egreso: </b>' . $egreso->descripcion . '.</td>'
                        . '</tr><tr><td class="c10"> </td></tr>'
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma beneficiario: _____________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';
//
// Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Comprobante de egreso ' . $id_egreso_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'egreso/consultar/');
        }
    }
    

}
