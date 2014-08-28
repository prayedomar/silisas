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

        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_activo_sedes_responsable($id_responsable, $dni_responsable);

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
            if (($this->input->post('caja')) && ($this->input->post('efectivo_ingresado'))) {
                list($sede_caja_destino, $t_caja_destino) = explode("-", $this->input->post('caja'));
                $efectivo_ingresado = round(str_replace(",", "", $this->input->post('efectivo_ingresado')), 2);
            } else {
                $sede_caja_destino = NULL;
                $t_caja_destino = NULL;
                $efectivo_ingresado = NULL;
            }
            $vigente = 1;
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_abono = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_abono = ($this->select_model->nextId_abono_adelanto($prefijo_abono)->id) + 1;
            $t_trans = 3; //Abono a adelanto
            $credito_debito = 1; //Credito
            //Para tirar array a json utilizar comillas dobles, decodificar en utf8 
            $adelanto = $this->select_model->adelanto_prefijo_id($prefijo_adelanto, $id_adelanto);
            $empleado = $this->select_model->empleado($adelanto->id_empleado, $adelanto->dni_empleado);
            $detalle_array = array(
                "Empleado_Depositante" => $empleado->nombre1 . " " . $empleado->nombre2 . " " . $empleado->apellido1,
                "Id_Empleado" => $adelanto->id_empleado,
                "Código_Adelanto" => $prefijo_adelanto . $id_adelanto,
                "Saldo_Pendiente" => "$" . number_format($adelanto->saldo, 1, '.', ','),
                "Observación" => $observacion
            );
            $detalle_json = json_encode($detalle_array);

            $data["tab"] = "crear_abono_adelanto";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "abono_adelanto/crear";
            $data['msn_recrear'] = "Crear otro abono";
            $data['url_imprimir'] = base_url() . "abono_adelanto/consultar_pdf/" . $prefijo_abono . "_" . $id_abono . "/I";
            $error3 = $this->update_model->adelanto_saldo($prefijo_adelanto, $id_adelanto, $total);
            if (isset($error3)) {
                $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_abono, $id_abono, $credito_debito, $total, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $detalle_json, $sede, $id_responsable, $dni_responsable);
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
                $cuentas = $this->select_model->cuenta_banco_responsable_ingresar($id_responsable, $dni_responsable);
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
        $data["tab"] = "consultar_abono_adelanto";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "abono_adelanto/consultar_validar";
        $data['action_recargar'] = base_url() . "abono_adelanto/consultar";
        $this->parser->parse('abono_adelanto/consultar', $data);
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
            $abono_adelanto = $this->select_model->abono_adelanto_prefijo_id($prefijo, $id);
            if ($abono_adelanto == TRUE) {
                if ($abono_adelanto->vigente == 0) {
                    $error_transaccion = "El abono a adelanto, se encuentra anulado.";
                }
            } else {
                $error_transaccion = "El abono a adelanto, existe en la base de datos.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_abono_adelanto";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
            $data['action_crear'] = base_url() . "abono_adelanto/consultar_validar";
            $this->parser->parse('abono_adelanto/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "abono_adelanto/consultar_pdf/" . $prefijo . "_" . $id . "/I");
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
            $responsable = $this->select_model->empleado($abono_adelanto->id_responsable, $abono_adelanto->dni_responsable);

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
            $css_html = '';
            $css_html .= '<style type=text/css>';
            $css_html .= 'h2{font-family: "times new roman", times, serif;font-size:22px;font-weight: bold;font-style: italic;line-height:20px;}';
            $css_html .= 'p.b1{font-family: helvetica, sans-serif;font-size:9px;}';
            $css_html .= 'p.b2{font-family: helvetica, sans-serif;font-size:13px;font-weight: bold;line-height:0px;text-align:center;}';
            $css_html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $css_html .= 'p.b4{line-height:28px;}';
            $css_html .= 'p.b5{font-size:14px;}';
            $css_html .= 'p.b6{line-height:26px;}';
            $css_html .= 'td.c1{width:420px;line-height:20px;}';
            $css_html .= 'td.c2{width:310px;}';
            $css_html .= 'td.c3{width:170px;}';
            $css_html .= 'td.c4{width:250px;}';
            $css_html .= 'td.c5{width:170px;}';
            $css_html .= 'td.c6{width:140px;}';
            $css_html .= 'td.c7{font-size:16px;}';
            $css_html .= 'td.c8{line-height:40px;}';
            $css_html .= 'td.c9{background-color:#F5F5F5;}';
            $css_html .= 'td.c10{font-size:4px;line-height:5px;}';
            $css_html .= 'td.c11{font-size:12px;}';
            $css_html .= 'td.c12{line-height:20px;}';
            $css_html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;}';
            $css_html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;height:30px;line-height:25px;border-top-color:#FFFFFF;border-left-color:#FFFFFF;border-right-color:#FFFFFF;}';
            $css_html .= 'td.c25{border-top-color:#000000;}';
            $css_html .= 'td.c26{border-bottom-color:#000000;}';
            $css_html .= 'td.c27{border-left-color:#000000;}';
            $css_html .= 'td.c28{border-right-color:#000000;}';
            $css_html .= 'td.a1{text-align:left;}';
            $css_html .= 'td.a2{text-align:center;}';
            $css_html .= 'td.a3{text-align:justify;}';
            $css_html .= 'th.a1{text-align:left;}';
            $css_html .= 'th.a2{text-align:center;}';
            $css_html .= 'table{border-spacing: 0;}';
            $css_html .= '</style>';
            $html = $css_html;
            $html .= '<table width="100%">'
                    . '<tr>'
                    . '<td class="c1 a2" colspan="2"><h2>Sistema Integral Lectura Inteligente S.A.S</h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '</td>'
                    . '<td class="c2 a2" colspan="2"><img src="' . base_url() . 'images/logo.png" class="img-responsive"  width="180" height="100"/></td>'
                    . '<br>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c1 c24 a2 c28" rowspan="3">COMPROBANTE DE ABONO A<br> ADELANTO DE NÓMINA</td>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $id_abono_adelanto_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Fecha de emisión:</b></td><td class="c6 c23 c25 c26  c27 c28">' . date("Y-m-d", strtotime($abono_adelanto->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Responsable empresa:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table width="100%" border="1">'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Empleado depositante:</b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $empleado->nombre1 . " " . $empleado->nombre2 . " " . $empleado->apellido1 . '</td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9" rowspan="2"><b> Valor del abono:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($abono_adelanto->total, 1, '.', ',') . '</b></td>'
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
                    . '<td><b>Código del adelanto: </b>' . $adelanto->id . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'
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
            $html = $css_html;
            $html .= '<table width="100%">'
                    . '<tr>'
                    . '<td class="c1 a2" colspan="2"><h2>Sistema Integral Lectura Inteligente S.A.S</h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '</td>'
                    . '<td class="c2 a2" colspan="2"><img src="' . base_url() . 'images/logo.png" class="img-responsive"  width="180" height="100"/></td>'
                    . '<br>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c1 c24 a2 c28" rowspan="3">COMPROBANTE DE ABONO A<br> ADELANTO DE NÓMINA</td>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $id_abono_adelanto_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Fecha de emisión:</b></td><td class="c6 c23 c25 c26  c27 c28">' . date("Y-m-d", strtotime($abono_adelanto->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Responsable empresa:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table width="100%" border="1">'
                    . '<tr>'
                    . '<td class="c3 c23 c12"><b>Empleado depositante:</b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $empleado->nombre1 . " " . $empleado->nombre2 . " " . $empleado->apellido1 . '</td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9" rowspan="2"><b> Valor del abono:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($abono_adelanto->total, 1, '.', ',') . '</b></td>'
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
                    . '<td><b>Código del adelanto: </b>' . $adelanto->id . '.</td>'
                    . '</tr><tr><td class="c10"> </td></tr>'
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

    function anular() {
        $data["tab"] = "anular_abono_adelanto";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        //Validamos si el responsable necesita cod de autorizacion o no. 
        if ($_SESSION["perfil"] != "admon_sistema" && $_SESSION["perfil"] != "directivo") {
            $data['cod_required'] = '1';
        }
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "abono_adelanto/validar_anular";
        $data['action_crear'] = base_url() . "abono_adelanto/insertar_anular";
        $data['action_recargar'] = base_url() . "abono_adelanto/anular";
        $data['action_validar_transaccion_anular'] = base_url() . "abono_adelanto/validar_transaccion_anular";
        $this->parser->parse('abono_adelanto/anular', $data);
        $this->load->view('footer');
    }

    function validar_anular() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('prefijo', 'Prefijo de sede', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Consecutivo', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'required|trim|xss_clean|max_length[255]');
            if ($_SESSION["perfil"] != "admon_sistema" && $_SESSION["perfil"] != "directivo") {
                $this->form_validation->set_rules('cod_autorizacion', 'Código de autorización', 'required|trim|max_length[13]|integer|callback_valor_positivo');
            }
            $error_cod = "";
            if (($this->input->post('id')) && ($this->input->post('cod_autorizacion'))) {
                $cod_autorizacion = $this->input->post('cod_autorizacion');
                $this->load->model('cod_autorizacionm');
                $check_codigo = $this->cod_autorizacionm->cod_autorizacion_id($cod_autorizacion);
                if ($check_codigo != TRUE) {
                    $error_cod = "<p>El código de autorización, No existe en la Base de Datos.</p>";
                } else {
                    $check_vigente = $this->cod_autorizacionm->cod_autorizacion_id_vigente($cod_autorizacion);
                    if ($check_vigente != TRUE) {
                        $error_cod = "<p>El código de autorización, No se encuentra vigente.</p>";
                    } else {
                        $check_autorizado = $this->cod_autorizacionm->cod_autorizacion_id_vigente_empleado_autorizado($cod_autorizacion);
                        if ($check_autorizado != TRUE) {
                            $error_cod = "<p>Usted no es el empleado autorizado para utilizar éste código de autorización.</p>";
                        } else {
                            $tabla_autorizada = '4'; //Abono a delanto                      
                            $check_tabla = $this->cod_autorizacionm->cod_autorizacion_id_vigente_tabla($cod_autorizacion, $tabla_autorizada);
                            if ($check_tabla != TRUE) {
                                $error_cod = "<p>El código de autorización, no fue creado para éste tipo de transacción.</p>";
                            } else {
                                $check_tabla = $this->cod_autorizacionm->cod_autorizacion_id_vigente_registro($cod_autorizacion, $this->input->post('id'));
                                if ($check_tabla != TRUE) {
                                    $error_cod = "<p>El código de autorización, no fue creado para éste código de factura.</p>";
                                }
                            }
                        }
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_cod != "")) {
                echo form_error('prefijo') . form_error('id') . $error_cod . form_error('cod_autorizacion') . form_error('observacion');
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
            $this->load->model('abono_adelantom');
            $prefijo = $this->input->post('prefijo');
            $id = $this->input->post('id');
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $t_trans = '3'; //Abono a adelanto de nómina      
            $credito_debito = '1'; //credito
            $vigente = '0'; //Anulado

            $data["tab"] = "anular_abono_adelanto";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "abono_adelanto/anular";
            $data['msn_recrear'] = "Anular otro abono a adelanto de nómina";

            if ($this->input->post('cod_autorizacion')) {
                $this->update_model->concepto_cod_autorizacion($this->input->post('cod_autorizacion'), '0');
            }
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


            //Debemos sumarle al saldo del adelanto el abono
            $abono_adelanto = $this->abono_adelantom->abono_adelanto_prefijo_id($prefijo, $id);
            $error3 = $this->update_model->adelanto_saldo($abono_adelanto->prefijo_adelanto, $abono_adelanto->id_adelanto, ($abono_adelanto->total * -1));
            if (isset($error3)) {
                $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito, $vigente, $detalle_json);
                if (isset($error)) {
                    $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $error1 = $this->update_model->abono_adelanto_vigente($prefijo, $id, $vigente);
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
            $this->load->model('abono_adelantom');
            $abono_adelanto = $this->abono_adelantom->abono_adelanto_prefijo_id($prefijo, $id);
            if ($abono_adelanto == TRUE) {
                if ($abono_adelanto->vigente == 1) {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">$' . number_format($abono_adelanto->total_adelanto, 2, '.', ',') . '</td>
                            <td class="text-center">' . $abono_adelanto->beneficiario . '</td>
                            <td class="text-center">$' . number_format($abono_adelanto->total, 2, '.', ',') . '</td>
                            <td class="text-center">' . $abono_adelanto->sede_caja . '-' . $abono_adelanto->tipo_caja . '</td>
                            <td class="text-center">$' . number_format($abono_adelanto->efectivo_ingresado, 2, '.', ',') . '</td>
                            <td class="text-center">' . $abono_adelanto->cuenta_destino . '</td>
                            <td class="text-center">$' . number_format($abono_adelanto->valor_consignado, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $abono_adelanto->responsable . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($abono_adelanto->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>El abono a adelanto de nómina, ya se encuentra anulado.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>El abono a adelanto de nómina, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
