<?php

class Adelanto extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

//Crear: Adelanto de nomina
    function crear() {
        $data["tab"] = "crear_adelanto";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_activo_sedes_responsable($id_responsable, $dni_responsable);
        $data['t_ausencia'] = $this->select_model->t_ausencia();
        $data['action_validar'] = base_url() . "adelanto/validar";
        $data['action_crear'] = base_url() . "adelanto/insertar";
        $data['action_llena_cuenta_responsable'] = base_url() . "adelanto/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "adelanto/llena_caja_responsable";

        $this->parser->parse('adelanto/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            //coloca maxlength en 15, para incluir los puntos de miles 888.777.666.273
            $this->form_validation->set_rules('total', 'Valor del Adelanto', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('autoriza', 'Quién autoriza', 'required|trim|xss_clean|max_length[50]');
            $this->form_validation->set_rules('motivo', 'Motivo del adelanto', 'required|trim|xss_clean|max_length[255]');
            $this->form_validation->set_rules('forma_descuento', 'Forma de descuento', 'required|trim|xss_clean|max_length[210]');
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
                echo form_error('empleado') . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('autoriza') . form_error('motivo') . form_error('forma_descuento');
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
            $autoriza = ucfirst(mb_strtolower($this->input->post('autoriza')));
            $motivo = ucfirst(mb_strtolower($this->input->post('motivo')));
            $forma_descuento = ucfirst(mb_strtolower($this->input->post('forma_descuento')));

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_adelanto = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_adelanto = ($this->select_model->nextId_adelanto($prefijo_adelanto)->id) + 1;
            $t_trans = 1; //Adelanto
            $credito_debito = 0; //Debito

            $data["tab"] = "crear_adelanto";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "adelanto/crear";
            $data['msn_recrear'] = "Crear otro Adelanto";
            $data['url_imprimir'] = base_url() . "adelanto/consultar_pdf/" . $prefijo_adelanto . "_" . $id_adelanto . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_adelanto, $id_adelanto, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->adelanto($prefijo_adelanto, $id_adelanto, $id_empleado, $dni_empleado, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $autoriza, $motivo, $forma_descuento, $id_responsable, $dni_responsable);
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
        $data["tab"] = "consultar_adelanto";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "adelanto/consultar_validar";
        $data['action_recargar'] = base_url() . "adelanto/consultar";
        $this->parser->parse('adelanto/consultar', $data);
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
            $adelanto = $this->select_model->adelanto_prefijo_id($prefijo, $id);
            if ($adelanto == TRUE) {
                if ($adelanto->vigente == 0) {
                    $error_transaccion = "El adelanto de nómina, se encuentra anulado.";
                }
            } else {
                $error_transaccion = "El adelanto de nómina, no existe en la base de datos.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_adelanto";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
            $data['action_crear'] = base_url() . "adelanto/consultar_validar";
            $this->parser->parse('adelanto/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "adelanto/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_adelanto, $salida_pdf) {
        $adelanto_prefijo_id = $id_adelanto;
        $id_adelanto_limpio = str_replace("_", " ", $adelanto_prefijo_id);
        list($prefijo, $id) = explode("_", $adelanto_prefijo_id);
        $adelanto = $this->select_model->adelanto_prefijo_id($prefijo, $id);
        if ($adelanto == TRUE) {
            $empleado = $this->select_model->empleado($adelanto->id_empleado, $adelanto->dni_empleado);
            $dni_abreviado_empleado = $this->select_model->t_dni_id($adelanto->dni_empleado)->abreviacion;
            $responsable = $this->select_model->empleado($adelanto->id_responsable, $adelanto->dni_responsable);

            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Adelanto de nómina ' . $id_adelanto_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Adelanto de nómina ' . $id_adelanto_limpio . ' Sili S.A.S');
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
            $html .= 'td.c3{width:170px;}';
            $html .= 'td.c4{width:250px;}';
            $html .= 'td.c5{width:150px;}';
            $html .= 'td.c6{width:160px;}';
            $html .= 'td.c7{font-size:16px;}';
            $html .= 'td.c8{line-height:40px;}';
            $html .= 'td.c9{background-color:#F5F5F5;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c11{font-size:12px;}';
            $html .= 'td.c12{line-height:20px;}';
            $html .= 'td.c13{line-height:30px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
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
            $html .= '<table width="100%">'
                    . '<tr>'
                    . '<td class="c1 a2" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '</td>'
                    . '<td class="c2 a2 c1000"  colspan="2"></td>'
                    . '<br>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c1 c24 a2 c28" rowspan="3">COMPROBANTE DE<br> ADELANTO DE NÓMINA</td>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $id_adelanto_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Fecha de emisión:</b></td><td class="c6 c23 c25 c26  c27 c28">' . date("Y-m-d", strtotime($adelanto->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Responsable empresa:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table width="100%" border="1">'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Empleado beneficiario:</b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $empleado->nombre1 . " " . $empleado->nombre2 . " " . $empleado->apellido1 . '</td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9" rowspan="2"><b> Valor adelanto:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($adelanto->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Documento indentidad: </b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $dni_abreviado_empleado . ' ' . $adelanto->id_empleado . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c9 a2 c13"><b>DETALLES DEL ADELANTO</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c23">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr><tr>'
                    . '<td class="a3"><b>Autorizó: </b>' . $adelanto->autoriza . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr><tr>'
                    . '<td class="a3"><b>Motivo del adelanto: </b>' . $adelanto->motivo . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr><tr>'
                    . '<td class="a3"><b>Forma de descuento: </b>' . $adelanto->forma_descuento . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'
                    . '</table>'
                    . '</td>'
                    . '</tr>'
                    . '<tr><td colspan="2" class="c11 a3">Recibí éste dinero de la empresa SILI S.A.S en calidad de anticipo, autorizando que me sea descontado de la forma especificada. Así mismo, autorizo a la empresa SILI S.A.S, para que en caso de retiro, descuenten de mis pretaciones sociales, el saldo pendiente de éste adelanto.<p class="b4 b5">Firma empleado: ______________________________________</p></td>'
                    . '<td colspan="2"><br><br><br><p class="b5 b6">Firma y sello empresa: ____________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para el empleado -</p>';

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
            $html .= 'td.c13{line-height:30px;}';
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
            $html .= '<table width="100%">'
                    . '<tr>'
                    . '<td class="c1 a2" colspan="2"><h2></h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '</td>'
                    . '<td class="c2 a2 c1000"  colspan="2"></td>'
                    . '<br>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c1 c24 a2 c28" rowspan="3">COMPROBANTE DE<br> ADELANTO DE NÓMINA</td>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $id_adelanto_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Fecha de emisión:</b></td><td class="c6 c23 c25 c26  c27 c28">' . date("Y-m-d", strtotime($adelanto->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Responsable empresa:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table width="100%" border="1">'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Empleado beneficiario:</b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $empleado->nombre1 . " " . $empleado->nombre2 . " " . $empleado->apellido1 . '</td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9" rowspan="2"><b> Valor adelanto:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($adelanto->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Documento indentidad: </b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $dni_abreviado_empleado . ' ' . $adelanto->id_empleado . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c9 a2 c13"><b>DETALLES DEL ADELANTO</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c23">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr><tr>'
                    . '<td><b>Autorizó: </b>' . $adelanto->autoriza . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr><tr>'
                    . '<td><b>Motivo del adelanto: </b>' . $adelanto->motivo . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr><tr>'
                    . '<td><b>Forma de descuento: </b>' . $adelanto->forma_descuento . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'
                    . '</table>'
                    . '</td>'
                    . '</tr>'
                    . '<tr><td colspan="2" class="c11 a3">Recibí éste dinero de la empresa SILI S.A.S en calidad de anticipo, autorizando que me sea descontado de la forma especificada. Así mismo, autorizo a la empresa SILI S.A.S, para que en caso de retiro, descuenten de mis pretaciones sociales, el saldo pendiente de éste adelanto.<p class="b4 b5">Firma empleado: ______________________________________</p></td>'
                    . '<td colspan="2"><br><br><br><p class="b5 b6">Firma y sello empresa: ____________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';
//
// Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Adelanto de nómina ' . $id_adelanto_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'adelanto/consultar/');
        }
    }

    function anular() {
        $data["tab"] = "anular_adelanto";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "adelanto/validar_anular";
        $data['action_crear'] = base_url() . "adelanto/insertar_anular";
        $data['action_recargar'] = base_url() . "adelanto/anular";
        $data['action_validar_transaccion_anular'] = base_url() . "adelanto/validar_transaccion_anular";
        $this->parser->parse('adelanto/anular', $data);
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
            $t_trans = '1'; //Adelanto de nómina    
            $credito_debito = '0'; //Débito
            $vigente = '0'; //Anulado

            $data["tab"] = "anular_adelanto";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "adelanto/anular";
            $data['msn_recrear'] = "Anular otro adelanto de nómina";
            $error = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito, $vigente);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->update_model->adelanto_vigente($prefijo, $id, $vigente);
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
            $this->load->model('adelantom');
            $adelanto = $this->adelantom->adelanto_prefijo_id($prefijo, $id);
            if ($adelanto == TRUE) {
                if ($adelanto->vigente == 1) {
                    $this->load->model('abono_adelantom');
                    $abonos_vigentes = $this->abono_adelantom->abono_adelanto_vigente_id_adelanto($prefijo, $id);
                    if ($abonos_vigentes != TRUE) {
                        $response = array(
                            'respuesta' => 'OK',
                            'filasTabla' => ''
                        );
                        $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $adelanto->beneficiario . '</td>
                            <td class="text-center">$' . number_format($adelanto->total, 2, '.', ',') . '</td>
                            <td class="text-center">' . $adelanto->sede_caja . '-' . $adelanto->tipo_caja . '</td>
                            <td class="text-center">$' . number_format($adelanto->efectivo_retirado, 2, '.', ',') . '</td>
                            <td class="text-center">' . $adelanto->cuenta_origen . '</td>
                            <td class="text-center">$' . number_format($adelanto->valor_retirado, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $adelanto->responsable . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($adelanto->fecha_trans)) . '</td>
                        </tr>';
                        echo json_encode($response);
                        return false;
                    } else {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p><strong><center>El adelanto tiene abonos vigentes. <br>Si desea anular éste adelanto, anule primero todos sus adelantos vigentes: '
                        );
                        foreach ($abonos_vigentes as $fila) {
                            $response['mensaje'] .= $fila->prefijo . '-' . $fila->id . ' ';
                        }
                        $response['mensaje'] .= '</center></strong></p>';
                        echo json_encode($response);
                        return false;
                    }
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>El adelanto de nómina, ya se encuentra anulado.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>El adelanto de nómina, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
