<?php

class Retefuente_ventas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_retefuente_ventas";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['sede'] = $this->select_model->sede();
        $data['action_validar'] = base_url() . "retefuente_ventas/validar";
        $data['action_crear'] = base_url() . "retefuente_ventas/insertar";
        $data['action_recargar'] = base_url() . "retefuente_ventas/crear";
        $data['action_validar_factura'] = base_url() . "retefuente_ventas/validar_factura";
        $data['action_llena_cuenta_responsable'] = base_url() . "retefuente_ventas/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "retefuente_ventas/llena_caja_responsable";
        $this->parser->parse('retefuente_ventas/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('prefijo_factura', 'Prefijo de factura', 'required|callback_select_default');
            $this->form_validation->set_rules('id_factura', 'Número de factura', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('total', 'Valor de la retención en la fuente', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
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
                echo form_error('prefijo_factura') . form_error('id_factura') . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion');
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
            $prefijo_factura = $this->input->post('prefijo_factura');
            $id_factura = $this->input->post('id_factura');
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
            $prefijo_retefuente_ventas = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_retefuente_ventas = ($this->select_model->nextId_retefuente_ventas($prefijo_retefuente_ventas)->id) + 1;
            $t_trans = 14; //Retencion en la fuente ventas
            $credito_debito = 0; //Débito
            //Para tirar array a json utilizar comillas dobles, decodificar en utf8    
            $factura = $this->select_model->factura_prefijo_id($prefijo_factura, $id_factura);
            $matricula = $this->select_model->matricula_id($factura->matricula);
            $titular = $this->select_model->titular($matricula->id_titular, $matricula->dni_titular);          
            $detalle_array = array(
                "Factura" => $prefijo_factura . $id_factura,
                "Valor_Factura" => "$" . number_format($factura->subtotal, 1, '.', ','),
                "Titular" => $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . " " . $titular->apellido2,
                "Id_Titular" => $titular->id,
                "Observación_Factura" => $factura->observacion,
                "Observación_Retención" => $observacion
            );
            $detalle_json = json_encode($detalle_array);            

            $data["tab"] = "crear_retefuente_ventas";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "retefuente_ventas/crear";
            $data['msn_recrear'] = "Crear otra retención";
            $data['url_imprimir'] = base_url() . "retefuente_ventas/consultar_pdf/" . $prefijo_retefuente_ventas . "_" . $id_retefuente_ventas . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_retefuente_ventas, $id_retefuente_ventas, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $detalle_json, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->retefuente_ventas($prefijo_retefuente_ventas, $id_retefuente_ventas, $prefijo_factura, $id_factura, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $error2 = $this->update_model->factura_retefuente($prefijo_factura, $id_factura, 1);
                    if (isset($error2)) {
                        $data['trans_error'] = $error2 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                        $this->parser->parse('trans_error', $data);
                    } else {
                        $this->parser->parse('trans_success_print', $data);
                    }
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function validar_factura() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $prefijo_factura = $this->input->post('prefijo');
            $id_factura = $this->input->post('id');
            $factura = $this->select_model->factura_prefijo_id($prefijo_factura, $id_factura);
            if ($factura == TRUE) {
                if ($factura->vigente == 0) {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><center><strong>La factura de venta se encuentra anulada.</strong></center></p>'
                    );
                    echo json_encode($response);
                    return false;
                } else {
                    if ($factura->retefuente == 1) {
                        $response = array(
                            'respuesta' => 'error',
                            'mensaje' => '<p><center><strong>Ya se le realizó la retención en la fuente a ésta factura de venta.</strong></center></p>'
                        );
                        echo json_encode($response);
                        return false;
                    } else {
                        $response = array(
                            'respuesta' => 'OK',
                            'aNombreDe' => $factura->a_nombre_de,
                            'matricula' => $factura->matricula,
                            'subtotal' => number_format($factura->subtotal, 2, '.', ','),
                            'retefuente' => number_format((($factura->subtotal) * 0.11), 2, '.', ',')
                        );
                        echo json_encode($response);
                        return false;
                    }
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><center><strong>La factura de venta, no existe en la base de datos.</strong></center></p>'
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
        $data["tab"] = "consultar_retefuente_ventas";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "retefuente_ventas/consultar_validar";
        $data['action_recargar'] = base_url() . "retefuente_ventas/consultar";
        $this->parser->parse('retefuente_ventas/consultar', $data);
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
            $retefuente_ventas = $this->select_model->retefuente_ventas_prefijo_id($prefijo, $id);
            if ($retefuente_ventas != TRUE) {
                $error_transaccion = "Retención en la fuente no encontrada.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_retefuente_ventas";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
            $data['action_crear'] = base_url() . "retefuente_ventas/consultar_validar";
            $this->parser->parse('retefuente_ventas/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "retefuente_ventas/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_retefuente_ventas, $salida_pdf) {
        $retefuente_ventas_prefijo_id = $id_retefuente_ventas;
        $id_retefuente_ventas_limpio = str_replace("_", " ", $retefuente_ventas_prefijo_id);
        list($prefijo, $id) = explode("_", $retefuente_ventas_prefijo_id);
        $retefuente_ventas = $this->select_model->retefuente_ventas_prefijo_id($prefijo, $id);
        if ($retefuente_ventas == TRUE) {
            $responsable = $this->select_model->empleado($retefuente_ventas->id_responsable, $retefuente_ventas->dni_responsable);
            $factura = $this->select_model->factura_prefijo_id($retefuente_ventas->prefijo_factura, $retefuente_ventas->id_factura);
            $dni_abreviado_a_nombre = $this->select_model->t_dni_id($factura->dni_a_nombre_de)->abreviacion;
            $matricula = $this->select_model->matricula_id($factura->matricula);
            $titular = $this->select_model->titular($matricula->id_titular, $matricula->dni_titular);
            $dni_abreviado_titular = $this->select_model->t_dni_id($titular->dni)->abreviacion;
            if (($factura->d_v_a_nombre_de) == (NULL)) {
                $d_v = "";
            } else {
                $d_v = " - " . $factura->d_v_a_nombre_de;
            }

            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Comprobante de devolución por retención ' . $id_retefuente_ventas_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Comprobante de devolución por retención ' . $id_retefuente_ventas_limpio . ' Sili S.A.S');
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
            $html .= 'td.c1{width:420px;line-height:20px;}';
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
                    . '<td class="c1 a2" colspan="2"><h2>Sistema Integral Lectura Inteligente S.A.S</h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '</td>'
                    . '<td class="c2 a2" colspan="2"><img src="' . base_url() . 'images/logo.png" class="img-responsive"  width="180" height="100"/></td>'
                    . '<br>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c1 c24 a2 c28" rowspan="3">RETENCIÓN EN LA FUENTE <br> POR VENTAS</td>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $id_retefuente_ventas_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Fecha de emisión:</b></td><td class="c6 c23 c25 c26  c27 c28">' . date("Y-m-d", strtotime($retefuente_ventas->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Responsable empresa:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c12"></td><td class="c4 c12"></td>'
                    . '<td rowspan="2" class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor retención:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($retefuente_ventas->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Factura:</b></td><td class="c23 c25 c26  c27 c28 c12">' . $retefuente_ventas->prefijo_factura . " " . $retefuente_ventas->id_factura . '</td>'
                    . '</tr></table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>A nombre de:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $factura->a_nombre_de . '</td>'
                    . '<td class="c5 c23 c12 c25 c26 c27 c28"><b>Identificación:</b></td><td class="c6 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_a_nombre . ' ' . $factura->id_a_nombre_de . $d_v . '</td>'
                    . '</tr>';
            if (($retefuente_ventas->observacion) != "") {
                $html .= '<tr><td class="a3 c12 c23 c25 c26 c27 c28" colspan="4"><b>Observacion retención: </b>' . $retefuente_ventas->observacion . '.</td></tr>';
            }
            $html .= '<tr>'
                    . '<td colspan="4" class="c9 a2 c13 c25 c26 c27 c28"><b>DETALLES DE LA FACTURA DE VENTA</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                    . '<table>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Nombre titular: </b>' . $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . " " . $titular->apellido2 . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Indetificación titular: </b>' . $dni_abreviado_titular . ' ' . $titular->id . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>'
                    . '<tr><td class="a3"><b>Subtotal factura: </b>$' . number_format($factura->subtotal, 1, '.', ',') . '.</td></tr>'
                    . '<tr><td class="c10"> </td></tr>';
            if (($factura->observacion) != "") {
                $html .= '<tr><td class="a3"><b>Observacion factura: </b>' . $factura->observacion . '.</td></tr>'
                        . '<tr><td class="c10"> </td></tr>';
            }
            $html .= '</table>'
                    . '</td>'
                    . '</tr>'
                    . '<tr><td colspan="2" class="c14 c25 c26 c27 c28"><br><p class="b5 b6">Firma beneficiario: ______________________________</p></td>'
                    . '<td colspan="2" class="c14 c25 c26 c27 c28"><br><p class="b5 b6">Firma y sello empresa: __________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para el titular -</p>';

            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            $nombre_archivo = utf8_decode('Comprobante de devolución por retención ' . $id_retefuente_ventas_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'retefuente_ventas/consultar/');
        }
    }

    function anular() {
        $data["tab"] = "anular_retefuente_ventas";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "retefuente_ventas/validar_anular";
        $data['action_crear'] = base_url() . "retefuente_ventas/insertar_anular";
        $data['action_recargar'] = base_url() . "retefuente_ventas/anular";
        $data['action_validar_transaccion_anular'] = base_url() . "retefuente_ventas/validar_transaccion_anular";
        $this->parser->parse('retefuente_ventas/anular', $data);
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
            $t_trans = '14'; //Retefuente por ventas
            $credito_debito = '0'; //Débito
            $vigente = '0'; //Anulado

            $data["tab"] = "anular_retefuente_ventas";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "retefuente_ventas/anular";
            $data['msn_recrear'] = "Anular otra retefuente por ventas";
            
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
                $error1 = $this->update_model->retefuente_ventas_vigente($prefijo, $id, $vigente);
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
            $this->load->model('retefuente_ventasm');
            $retefuente_ventas = $this->retefuente_ventasm->retefuente_ventas_prefijo_id($prefijo, $id);
            if ($retefuente_ventas == TRUE) {
                if ($retefuente_ventas->vigente == 1) {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $retefuente_ventas->prefijo_factura . '-' . $retefuente_ventas->id_factura . '</td>
                            <td class="text-center">$' . number_format($retefuente_ventas->total, 2, '.', ',') . '</td>
                            <td class="text-center">' . $retefuente_ventas->sede_caja . '-' . $retefuente_ventas->tipo_caja . '</td>
                            <td class="text-center">$' . number_format($retefuente_ventas->efectivo_retirado, 2, '.', ',') . '</td>
                            <td class="text-center">' . $retefuente_ventas->cuenta_origen . '</td>
                            <td class="text-center">$' . number_format($retefuente_ventas->valor_retirado, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $retefuente_ventas->responsable . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($retefuente_ventas->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>La retefuente por ventas, ya se encuentra anulada.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La retefuente por ventas, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
