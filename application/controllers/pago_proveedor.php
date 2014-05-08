<?php

class Pago_proveedor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

//Crear: Egreso
    function crear() {
        $data["tab"] = "crear_pago_proveedor";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['proveedor'] = $this->select_model->proveedor();
        $data['action_validar'] = base_url() . "pago_proveedor/validar";
        $data['action_crear'] = base_url() . "pago_proveedor/insertar";
        $data['action_llena_cuenta_responsable'] = base_url() . "pago_proveedor/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "pago_proveedor/llena_caja_responsable";
        $this->parser->parse('pago_proveedor/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('proveedor', 'Proveedor', 'required|callback_select_default');
            $this->form_validation->set_rules('factura', 'Código de factura', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('total', 'Valor del pago', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

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

            if (($this->form_validation->run() == FALSE) || ($error_valores != "")) {
                echo form_error('proveedor') . form_error('factura') . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion');
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
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_pago_proveedor = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_pago_proveedor = ($this->select_model->nextId_pago_proveedor($prefijo_pago_proveedor)->id) + 1;
            $t_trans = 11; //Pago a proveedor
            $credito_debito = 0; //Debito            

            $data["tab"] = "crear_pago_proveedor";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "pago_proveedor/crear";
            $data['msn_recrear'] = "Crear otro pago";
            $data['url_imprimir'] = base_url() . "pago_proveedor/consultar_pdf/" . $prefijo_pago_proveedor . "_" . $id_pago_proveedor . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_pago_proveedor, $id_pago_proveedor, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->pago_proveedor($prefijo_pago_proveedor, $id_pago_proveedor, $id_proveedor, $dni_proveedor, $factura, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $observacion, $id_responsable, $dni_responsable);
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
        $data["tab"] = "consultar_pago_proveedor";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['action_crear'] = base_url() . "pago_proveedor/consultar_validar";
        $data['action_recargar'] = base_url() . "pago_proveedor/consultar";
        $this->parser->parse('pago_proveedor/consultar', $data);
        $this->load->view('footer');
    }

    public function consultar_validar() {
        $this->escapar($_POST);
        $pago_proveedor_prefijo_id = $this->input->post('prefijo_id_pago_proveedor');
        if (!empty($pago_proveedor_prefijo_id)) {
            try {
                list($prefijo, $id) = explode(" ", $pago_proveedor_prefijo_id);
                $pago_proveedor = $this->select_model->pago_proveedor_prefijo_id($prefijo, $id);
                if ($pago_proveedor == TRUE) {
//                    $this->consultar_pdf($prefijo . "_" . $id, "I");
                    redirect(base_url() . "pago_proveedor/consultar_pdf/" . $prefijo . "_" . $id . "/I");
                } else {
                    $data["tab"] = "consultar_pago_proveedor";
                    $this->isLogin($data["tab"]);
                    $data["error_consulta"] = "Pago a proveedor no encontrado.";
                    $this->load->view("header", $data);
                    $data['action_crear'] = base_url() . "pago_proveedor/consultar_validar";
                    $this->parser->parse('pago_proveedor/consultar', $data);
                    $this->load->view('footer');
                }
            } catch (Exception $e) {
                $data["tab"] = "consultar_pago_proveedor";
                $this->isLogin($data["tab"]);
                $data["error_consulta"] = "Error en el formato ingresado del pago: Prefijo + Espacio + Consecutivo.";
                $this->load->view("header", $data);
                $data['action_crear'] = base_url() . "pago_proveedor/consultar_validar";
                $this->parser->parse('pago_proveedor/consultar', $data);
                $this->load->view('footer');
            }
        } else {
            $data["tab"] = "consultar_pago_proveedor";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = "Antes de consultar, ingrese el consecutivo del pago a proveedor.";
            $this->load->view("header", $data);
            $data['action_crear'] = base_url() . "pago_proveedor/consultar_validar";
            $this->parser->parse('pago_proveedor/consultar', $data);
            $this->load->view('footer');
        }
    }

    function consultar_pdf($id_pago_proveedor, $salida_pdf) {
        $pago_proveedor_prefijo_id = $id_pago_proveedor;
        $id_pago_proveedor_limpio = str_replace("_", " ", $pago_proveedor_prefijo_id);
        list($prefijo, $id) = explode("_", $pago_proveedor_prefijo_id);
        $pago_proveedor = $this->select_model->pago_proveedor_prefijo_id($prefijo, $id);
        if ($pago_proveedor == TRUE) {
            $reponsable = $this->select_model->empleado($pago_proveedor->id_responsable, $pago_proveedor->dni_responsable);
            $proveedor = $this->select_model->proveedor_id_dni($pago_proveedor->id_proveedor, $pago_proveedor->dni_proveedor);
            $dni_abreviado_proveedor = $this->select_model->t_dni_id($proveedor->dni)->abreviacion;

            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Comprobante de pago a proveedor ' . $id_pago_proveedor_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Comprobante de pago a proveedor ' . $id_pago_proveedor_limpio . ' Sili S.A.S');
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
            $html .= 'td.c1{width:420px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:170px;}';
            $html .= 'td.c4{width:195px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:40px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:25px;}';
            $html .= 'td.c13{width:580px;}';
            $html .= 'td.c15{width:420px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
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
                    . '<td class="c24 a2" colspan="2">COMPROBANTE DE <br>PAGO A PROVEEDOR</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $id_pago_proveedor_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . date("Y-m-d", strtotime($pago_proveedor->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26  c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26  c27 c28 c12 c6">' . $reponsable->nombre1 . " " . $reponsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c15 c12"></td>'
                    . '<td class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor del pago:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($pago_proveedor->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '</table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Documento proveedor:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_proveedor . ' ' . $proveedor->id . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Código de factura:</b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $pago_proveedor->factura . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre proveedor:</b></td><td colspan="3" class="c23 c12 c25 c26 c27 c28">' . $proveedor->razon_social . '</td>'
                    . '</tr>';
            if (($pago_proveedor->observacion) != "") {
                $html .= '<tr>'
                        . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                        . '<table>'
                        . '<tr><td class="c10"> </td></tr><tr>'
                        . '<td><b>Observaciones del pago: </b>' . $pago_proveedor->observacion . '.</td>'
                        . '</tr><tr><td class="c10"> </td></tr>'
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td colspan="4" class="c25 c26 c27 c28"><br><br><p class="b5 b6">Firma del responsable: _________________________________________________________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';

            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            $nombre_archivo = utf8_decode('Comprobante de pago a proveedor ' . $id_pago_proveedor_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'pago_proveedor/consultar/');
        }
    }

}
