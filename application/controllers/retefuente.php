<?php

class Retefuente extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_retefuente";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['proveedor'] = $this->select_model->proveedor();
        $data['action_validar'] = base_url() . "retefuente/validar";
        $data['action_crear'] = base_url() . "retefuente/insertar";
        $data['action_llena_cuenta_responsable'] = base_url() . "retefuente/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "retefuente/llena_caja_responsable";
        $this->parser->parse('retefuente/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('proveedor', 'Proveedor', 'required|callback_select_default');
            $this->form_validation->set_rules('factura', 'Código de factura', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('total', 'Valor de la retención en la fuente', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_consignado', 'Valor Consignado a la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_ingresado', 'Efectivo Ingresado a la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
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

            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('proveedor') . form_error('factura') . form_error('total') . form_error('valor_consignado') . form_error('efectivo_ingresado') . $error_valores . form_error('observacion');
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
            list($id_proveedor, $dni_proveedor) = explode("_", $this->input->post('proveedor'));
            $factura = $this->input->post('factura');
            $total = round(str_replace(",", "", $this->input->post('total')), 2);
            if (($this->input->post('caja')) && ($this->input->post('efectivo_ingresado')) && ($this->input->post('efectivo_ingresado') != 0)) {
                list($sede_caja_destino, $t_caja_destino) = explode("-", $this->input->post('caja'));
                $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
            } else {
                $sede_caja_destino = NULL;
                $t_caja_destino = NULL;
                $efectivo_ingresado = NULL;
            }
            if (($this->input->post('cuenta')) && ($this->input->post('valor_consignado')) && ($this->input->post('valor_consignado') != 0)) {
                $cuenta_destino = $this->input->post('cuenta');
                $valor_consignado = round(str_replace(",", "", $this->input->post('valor_consignado')), 2);
            } else {
                $cuenta_destino = NULL;
                $valor_consignado = NULL;
            }
            $vigente = 1;
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_retefuente = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_retefuente = ($this->select_model->nextId_retefuente($prefijo_retefuente)->id) + 1;
            $t_trans = 12; //Retencion en la fuente
            $credito_debito = 1; //Credito            

            $data["tab"] = "crear_retefuente";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "retefuente/crear";
            $data['msn_recrear'] = "Crear otra retención";
            $data['url_imprimir'] = base_url() . "retefuente/consultar_pdf/" . $prefijo_retefuente . "_" . $id_retefuente . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_retefuente, $id_retefuente, $credito_debito, $total, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->retefuente($prefijo_retefuente, $id_retefuente, $id_proveedor, $dni_proveedor, $factura, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $observacion, $id_responsable, $dni_responsable);
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
        $data["tab"] = "consultar_recibo_caja";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['action_crear'] = base_url() . "recibo_caja/consultar_validar";
        $data['action_recargar'] = base_url() . "recibo_caja/consultar";
        $this->parser->parse('recibo_caja/consultar', $data);
        $this->load->view('footer');
    }

    public function consultar_validar() {
        $this->escapar($_POST);
        $recibo_caja_prefijo_id = $this->input->post('prefijo_id_recibo_caja');
        if (!empty($recibo_caja_prefijo_id)) {
            try {
                list($prefijo, $id) = explode(" ", $recibo_caja_prefijo_id);
                $recibo_caja = $this->select_model->recibo_caja_prefijo_id($prefijo, $id);
                $detalles_recibo_caja = $this->select_model->detalle_recibo_caja_prefijo_id($prefijo, $id);
                if (($recibo_caja == TRUE) && ($detalles_recibo_caja == TRUE)) {
//                    $this->consultar_pdf($prefijo . "_" . $id, "I");
                    redirect(base_url() . "recibo_caja/consultar_pdf/" . $prefijo . "_" . $id . "/I");
                } else {
                    $data["tab"] = "consultar_recibo_caja";
                    $this->isLogin($data["tab"]);
                    $data["error_consulta"] = "Recibo de caja no encontrado.";
                    $this->load->view("header", $data);
                    $data['action_crear'] = base_url() . "recibo_caja/consultar_validar";
                    $this->parser->parse('recibo_caja/consultar', $data);
                    $this->load->view('footer');
                }
            } catch (Exception $e) {
                $data["tab"] = "consultar_recibo_caja";
                $this->isLogin($data["tab"]);
                $data["error_consulta"] = "Error en el formato ingresado del recibo de caja: Prefijo + Espacio + Consecutivo.";
                $this->load->view("header", $data);
                $data['action_crear'] = base_url() . "recibo_caja/consultar_validar";
                $this->parser->parse('recibo_caja/consultar', $data);
                $this->load->view('footer');
            }
        } else {
            $data["tab"] = "consultar_recibo_caja";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = "Antes de consultar, ingrese el consecutivo del recibo de caja.";
            $this->load->view("header", $data);
            $data['action_crear'] = base_url() . "recibo_caja/consultar_validar";
            $this->parser->parse('recibo_caja/consultar', $data);
            $this->load->view('footer');
        }
    }

    function consultar_pdf($id_recibo_caja, $salida_pdf) {
        $recibo_caja_prefijo_id = $id_recibo_caja;
        $id_recibo_caja_limpio = str_replace("_", " ", $recibo_caja_prefijo_id);
        list($prefijo, $id) = explode("_", $recibo_caja_prefijo_id);
        $recibo_caja = $this->select_model->recibo_caja_prefijo_id($prefijo, $id);
        $detalles_recibo_caja = $this->select_model->detalle_recibo_caja_prefijo_id($prefijo, $id);
        if (($recibo_caja == TRUE) && ($detalles_recibo_caja == TRUE)) {
            $reponsable = $this->select_model->empleado($recibo_caja->id_responsable, $recibo_caja->dni_responsable);
            $dni_abreviado = $this->select_model->t_dni_id($recibo_caja->dni_a_nombre_de)->abreviacion;
            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Recibo de caja ' . $id_recibo_caja_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Recibo de caja ' . $id_recibo_caja_limpio . ' Sili S.A.S');
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
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:7px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'td.c1{width:418px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:112px;}';
            $html .= 'td.c4{width:306px;}';
            $html .= 'td.c9{width:177px;}';
            $html .= 'td.c5{width:128px;}';
            $html .= 'td.c6{height:200px;}';
            $html .= 'td.c7{border-top-color:#FFFFFF;border-left-color:#000000;font-family: helvetica, sans-serif;font-size:12px;}';
            $html .= 'td.c8{border-top-color:#FFFFFF;border-left-color:#000000;border-right-color:#000000;font-family: helvetica, sans-serif;font-size:12px;}';
            $html .= 'td.c20{width:418px;height:20px;line-height:20px;line-height:19px;}';
            $html .= 'td.c21{width:190px;height:20px;line-height:20px;font-weight: bold;}';
            $html .= 'td.c22{width:120px;height:20px;line-height:20px;font-weight: bold;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;height:30px;line-height:25px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;border-bottom-color:#000000;border-left-color:#000000;border-right-color:#000000;}';
            $html .= 'td.c26{background-color:#F5F5F5;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'th.c26{background-color:#F5F5F5;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'th.d1{width:263px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d2{width:105px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d3{width:85px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d4{width:105px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d5{width:120px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d6{width:50px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= 'table.t1{text-align:left;}';
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
                    . '<td class="c24 a2" colspan="2">RECIBO DE CAJA</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Número:</b></td><td class="c23 c25">' . $id_recibo_caja_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Fecha de emisión:</b></td><td class="c23 c25">' . date("Y-m-d", strtotime($recibo_caja->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Responsable empresa:</b></td><td class="c23 c25">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25"><b>Cliente:</b></td><td class="c4 c23 c25">' . $recibo_caja->a_nombre_de . '</td>'
                    . '<td class="c23 c25"><b>Documento cliente:</b></td><td class="c23 c25">' . $dni_abreviado . ' ' . $recibo_caja->id_a_nombre_de . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Dirección:</b></td><td class="c23 c25">' . $recibo_caja->direccion_a_nombre_de . '</td>'
                    . '<td class="c23 c25"><b>Número de matrícula:</b></td><td class="c23 c25">' . $recibo_caja->matricula . '</td>'
                    . '</tr>'
                    . '</table><br>'
                    . '<table border="1" class="t1">'
                    . '<tr>'
                    . '<th class="d1 c23 a2 c26">Detalle</th>'
                    . '<th class="d6 c23 a2 c26">Cuota</th>'
                    . '<th class="d2 c23 a2 c26">Abono</th>'
                    . '<th class="d3 c23 a2 c26">Dias Mora</th>'
                    . '<th class="d4 c23 a2 c26">Int. Mora</th>'
                    . '<th class="d5 c23 a2 c26">Subtotal</th>'
                    . '</tr>';
            $cont_filas = 0;
            foreach ($detalles_recibo_caja as $fila) {
                $cont_filas ++;
                $html .= '<tr>'
                        . '<td class="c7">' . $this->select_model->t_detalle($fila->t_detalle)->tipo . '</td>'
                        . '<td class="c8 a2">' . $fila->num_cuota . '</td>'
                        . '<td class="c8 a2">$' . number_format($fila->subtotal, 1, '.', ',') . '</td>'
                        . '<td class="c8 a2">' . $fila->cant_dias_mora . '</td>'
                        . '<td class="c8 a2">$' . number_format($fila->int_mora, 1, '.', ',') . '</td>'
                        . '<td class="c8 a2">$' . number_format((($fila->subtotal) + $fila->int_mora), 1, '.', ',') . '</td>'
                        . '</tr>';
            }
            for ($i = $cont_filas; $i < 13; $i++) {
                $html .= '<tr><td class="c7"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td></tr>';
            }
            $html .= '</table>'
                    . '<table border="1" class="t1">'
                    . '<tr>'
                    . '<td class="c20" rowspan="4"><br><br><br><br>Firma y sello empresa: ______________________________</td>'
                    . '<td class="c21 c23 c26">Total Abonos (+)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->subtotal, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total Int. Mora (+)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->int_mora, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total Descuento (-)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->descuento, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total a Pagar (=)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format((($recibo_caja->subtotal + $recibo_caja->int_mora) - ($recibo_caja->descuento)), 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '</table><p class="b3">- Copia para el cliente -</p>';

            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            // reset pointer to the last page
            $pdf->lastPage();

            $pdf->AddPage();
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:22px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:7px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'td.c1{width:418px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:112px;}';
            $html .= 'td.c4{width:306px;}';
            $html .= 'td.c9{width:177px;}';
            $html .= 'td.c5{width:128px;}';
            $html .= 'td.c6{height:200px;}';
            $html .= 'td.c7{border-top-color:#FFFFFF;border-left-color:#000000;font-family: helvetica, sans-serif;font-size:12px;}';
            $html .= 'td.c8{border-top-color:#FFFFFF;border-left-color:#000000;border-right-color:#000000;font-family: helvetica, sans-serif;font-size:12px;}';
            $html .= 'td.c20{width:418px;height:20px;line-height:20px;line-height:19px;}';
            $html .= 'td.c21{width:190px;height:20px;line-height:20px;font-weight: bold;}';
            $html .= 'td.c22{width:120px;height:20px;line-height:20px;font-weight: bold;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;height:30px;line-height:25px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $html .= 'td.c25{border-top-color:#000000;border-bottom-color:#000000;border-left-color:#000000;border-right-color:#000000;}';
            $html .= 'td.c26{background-color:#F5F5F5;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'th.c26{background-color:#F5F5F5;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'th.d1{width:263px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d2{width:105px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d3{width:85px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d4{width:105px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d5{width:120px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'th.d6{width:50px;font-weight: bold;height:22px;line-height:20px;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= 'table.t1{text-align:left;}';
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
                    . '<td class="c24 a2" colspan="2">RECIBO DE CAJA</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Número:</b></td><td class="c23 c25">' . $id_recibo_caja_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Fecha de emisión:</b></td><td class="c23 c25">' . date("Y-m-d", strtotime($recibo_caja->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Responsable empresa:</b></td><td class="c23 c25">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25"><b>Cliente:</b></td><td class="c4 c23 c25">' . $recibo_caja->a_nombre_de . '</td>'
                    . '<td class="c23 c25"><b>Documento cliente:</b></td><td class="c23 c25">' . $dni_abreviado . ' ' . $recibo_caja->id_a_nombre_de . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25"><b>Dirección:</b></td><td class="c23 c25">' . $recibo_caja->direccion_a_nombre_de . '</td>'
                    . '<td class="c23 c25"><b>Número de matrícula:</b></td><td class="c23 c25">' . $recibo_caja->matricula . '</td>'
                    . '</tr>'
                    . '</table><br>'
                    . '<table border="1" class="t1">'
                    . '<tr>'
                    . '<th class="d1 c23 a2 c26">Detalle</th>'
                    . '<th class="d6 c23 a2 c26">Cuota</th>'
                    . '<th class="d2 c23 a2 c26">Abono</th>'
                    . '<th class="d3 c23 a2 c26">Dias Mora</th>'
                    . '<th class="d4 c23 a2 c26">Int. Mora</th>'
                    . '<th class="d5 c23 a2 c26">Subtotal</th>'
                    . '</tr>';
            $cont_filas = 0;
            foreach ($detalles_recibo_caja as $fila) {
                $cont_filas ++;
                $html .= '<tr>'
                        . '<td class="c7">' . $this->select_model->t_detalle($fila->t_detalle)->tipo . '</td>'
                        . '<td class="c8 a2">' . $fila->num_cuota . '</td>'
                        . '<td class="c8 a2">$' . number_format($fila->subtotal, 1, '.', ',') . '</td>'
                        . '<td class="c8 a2">' . $fila->cant_dias_mora . '</td>'
                        . '<td class="c8 a2">$' . number_format($fila->int_mora, 1, '.', ',') . '</td>'
                        . '<td class="c8 a2">$' . number_format((($fila->subtotal) + $fila->int_mora), 1, '.', ',') . '</td>'
                        . '</tr>';
            }
            for ($i = $cont_filas; $i < 13; $i++) {
                $html .= '<tr><td class="c7"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td><td class="c8"></td></tr>';
            }
            $html .= '</table>'
                    . '<table border="1" class="t1">'
                    . '<tr>'
                    . '<td class="c20" rowspan="4"><br><br><br><br>Firma y sello empresa: ______________________________</td>'
                    . '<td class="c21 c23 c26">Total Abonos (+)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->subtotal, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total Int. Mora (+)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->int_mora, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total Descuento (-)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format($recibo_caja->descuento, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c23 c26">Total a Pagar (=)</td>'
                    . '<td class="c22 c23 a2 c26">$' . number_format((($recibo_caja->subtotal + $recibo_caja->int_mora) - ($recibo_caja->descuento)), 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';

// Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Recibo de caja ' . $id_recibo_caja_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'recibo_caja/consultar/');
        }
    }

}
