<?php

class Retefuente_compras extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_retefuente_compras";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);

        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['proveedor'] = $this->select_model->proveedor();
        $data['action_validar'] = base_url() . "retefuente_compras/validar";
        $data['action_crear'] = base_url() . "retefuente_compras/insertar";
        $data['action_llena_cuenta_responsable'] = base_url() . "retefuente_compras/llena_cuenta_responsable";
        $data['action_llena_caja_responsable'] = base_url() . "retefuente_compras/llena_caja_responsable";
        $this->parser->parse('retefuente_compras/crear', $data);
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
            $this->form_validation->set_rules('observacion', 'Descripción', 'required|trim|xss_clean|max_length[255]');
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
            if (($this->input->post('caja')) && ($this->input->post('efectivo_ingresado'))) {
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
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_retefuente_compras = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_retefuente_compras = ($this->select_model->nextId_retefuente_compras($prefijo_retefuente_compras)->id) + 1;
            $t_trans = 12; //Retencion en la fuente
            $credito_debito = 1; //Credito  
            //Para tirar array a json utilizar comillas dobles, decodificar en utf8         
            $proveedor = $this->select_model->proveedor_id_dni($id_proveedor, $dni_proveedor);
            $detalle_array = array(
                "Proveedor" => $proveedor->razon_social,
                "Id_Proveedor" => $proveedor->id . "-" . $proveedor->d_v,
                "Código_Factura" => $factura,
                "Descripsión" => $observacion
            );
            $detalle_json = json_encode($detalle_array);

            $data["tab"] = "crear_retefuente_compras";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "retefuente_compras/crear";
            $data['msn_recrear'] = "Crear otra retención";
            $data['url_imprimir'] = base_url() . "retefuente_compras/consultar_pdf/" . $prefijo_retefuente_compras . "_" . $id_retefuente_compras . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_retefuente_compras, $id_retefuente_compras, $credito_debito, $total, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $cuenta_destino, $valor_consignado, 1, $detalle_json, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->retefuente_compras($prefijo_retefuente_compras, $id_retefuente_compras, $id_proveedor, $dni_proveedor, $factura, $total, $cuenta_destino, $valor_consignado, $sede_caja_destino, $t_caja_destino, $efectivo_ingresado, $sede, $observacion, $id_responsable, $dni_responsable);
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
        $data["tab"] = "consultar_retefuente_compras";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "retefuente_compras/consultar_validar";
        $data['action_recargar'] = base_url() . "retefuente_compras/consultar";
        $this->parser->parse('retefuente_compras/consultar', $data);
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
            $retefuente_compras = $this->select_model->retefuente_compras_prefijo_id($prefijo, $id);
            if ($retefuente_compras == TRUE) {
                if ($retefuente_compras->vigente == 0) {
                    $error_transaccion = "La retención en la fuente, se encuentra anulada.";
                }
            } else {
                $error_transaccion = "La retención en la fuente, no existe en la base de datos.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_retefuente_compras";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('prefijo') . form_error('id') . $error_transaccion;
            $data["prefijo"] = $prefijo;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
            $data['action_crear'] = base_url() . "retefuente_compras/consultar_validar";
            $this->parser->parse('retefuente_compras/consultar', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "retefuente_compras/consultar_pdf/" . $prefijo . "_" . $id . "/I");
        }
    }

    function consultar_pdf($id_retefuente_compras, $salida_pdf) {
        $retefuente_compras_prefijo_id = $id_retefuente_compras;
        $id_retefuente_compras_limpio = str_replace("_", " ", $retefuente_compras_prefijo_id);
        list($prefijo, $id) = explode("_", $retefuente_compras_prefijo_id);
        $retefuente_compras = $this->select_model->retefuente_compras_prefijo_id($prefijo, $id);
        if ($retefuente_compras == TRUE) {
            $responsable = $this->select_model->empleado($retefuente_compras->id_responsable, $retefuente_compras->dni_responsable);
            $proveedor = $this->select_model->proveedor_id_dni($retefuente_compras->id_proveedor, $retefuente_compras->dni_proveedor);
            $dni_abreviado_proveedor = $this->select_model->t_dni_id($proveedor->dni)->abreviacion;
            if (($proveedor->d_v) == (NULL)) {
                $d_v = "";
            } else {
                $d_v = " - " . $proveedor->d_v;
            }

            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Comprobante de devolución por retención ' . $id_retefuente_compras_limpio . ' Sili S.A.S');
            $pdf->SetSubject('Comprobante de devolución por retención ' . $id_retefuente_compras_limpio . ' Sili S.A.S');
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
            $html .= 'td.c1{width:420px;line-height:20px;}';
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
            $html .= '<table width="100%">'
                    . '<tr>'
                    . '<td class="c1 a2" colspan="2"><h2>Sistema Integral Lectura Inteligente S.A.S</h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '</td>'
                    . '<td class="c2 a2" colspan="2"><img src="' . base_url() . 'images/logo.png" class="img-responsive"  width="180" height="100"/></td>'
                    . '<br>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c1 c24 a2 c28" rowspan="3">RETENCIÓN EN LA FUENTE <br>POR COMPRAS O SERVICIOS</td>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Número:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $id_retefuente_compras_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Fecha de emisión:</b></td><td class="c6 c23 c25 c26  c27 c28">' . date("Y-m-d", strtotime($retefuente_compras->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c5 c23 c25 c26  c27 c28"><b>Responsable empresa:</b></td><td class="c6 c23 c25 c26  c27 c28">' . $responsable->nombre1 . " " . $responsable->apellido1 . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c15 c12"></td>'
                    . '<td class="c23 c7 c5 c8 c9 c25 c26  c27 c28" rowspan="2"><b> Valor retenido:</b></td><td rowspan="2" class="c23 c25 c26  c27 c28 c7 c6 c8 c9"><b>$ ' . number_format($retefuente_compras->total, 1, '.', ',') . '</b></td>'
                    . '</tr>'
                    . '</table>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Documento proveedor:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $dni_abreviado_proveedor . ' ' . $proveedor->id . $d_v . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28 c12"><b>Código de factura:</b></td><td class="c4 c23 c25 c26  c27 c28 c12">' . $retefuente_compras->factura . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre proveedor:</b></td><td colspan="3" class="c23 c12 c25 c26 c27 c28">' . $proveedor->razon_social . '</td>'
                    . '</tr>';
            if (($retefuente_compras->observacion) != "") {
                $html .= '<tr>'
                        . '<td colspan="4" class="c23 c25 c26 c27 c28">'
                        . '<table>'
                        . '<tr><td class="c10"> </td></tr><tr>'
                        . '<td><b>Descripción de la retención: </b>' . $retefuente_compras->observacion . '.</td>'
                        . '</tr><tr><td class="c10"> </td></tr>'
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            }
            $html .= '<tr><td colspan="4" class="c25 c26 c27 c28"><br><br><p class="b5 b6">Firma del responsable: _________________________________________________________________________</p></td></tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';

            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            $nombre_archivo = utf8_decode('Comprobante de devolución por retención ' . $id_retefuente_compras_limpio . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'retefuente_compras/consultar/');
        }
    }

    function anular() {
        $data["tab"] = "anular_retefuente_compras";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        //Validamos si el responsable necesita cod de autorizacion o no. 
        if ($_SESSION["perfil"] != "admon_sistema" && $_SESSION["perfil"] != "directivo") {
            $data['cod_required'] = '1';
        }
        $data['sede'] = $this->select_model->sede_activa_responsable($_SESSION["idResponsable"], $_SESSION["dniResponsable"]);
        $data['action_validar'] = base_url() . "retefuente_compras/validar_anular";
        $data['action_crear'] = base_url() . "retefuente_compras/insertar_anular";
        $data['action_recargar'] = base_url() . "retefuente_compras/anular";
        $data['action_validar_transaccion_anular'] = base_url() . "retefuente_compras/validar_transaccion_anular";
        $this->parser->parse('retefuente_compras/anular', $data);
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
                            $tabla_autorizada = '5'; //Retefuente compras                    
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
            $prefijo = $this->input->post('prefijo');
            $id = $this->input->post('id');
            $observacion = ucfirst(mb_strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $t_trans = '12'; //Retencion por compras    
            $credito_debito = '1'; //credito
            $vigente = '0'; //Anulado

            $data["tab"] = "anular_retefuente_compras";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "retefuente_compras/anular";
            $data['msn_recrear'] = "Anular otra retención por compras";
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

            $error = $this->update_model->movimiento_transaccion_vigente($t_trans, $prefijo, $id, $credito_debito, $vigente, $detalle_json);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->update_model->retefuente_compras_vigente($prefijo, $id, $vigente);
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
            $this->load->model('retefuente_comprasm');
            $retefuente_compras = $this->retefuente_comprasm->retefuente_compras_prefijo_id($prefijo, $id);
            if ($retefuente_compras == TRUE) {
                if ($retefuente_compras->vigente == 1) {
                    $response = array(
                        'respuesta' => 'OK',
                        'filasTabla' => ''
                    );
                    $response['filasTabla'] .= '<tr>
                            <td class="text-center">' . $retefuente_compras->razon_social . '</td>
                            <td class="text-center">' . $retefuente_compras->factura . '</td>
                            <td class="text-center">$' . number_format($retefuente_compras->total, 2, '.', ',') . '</td>
                            <td class="text-center">' . $retefuente_compras->sede_caja . '-' . $retefuente_compras->tipo_caja . '</td>
                            <td class="text-center">$' . number_format($retefuente_compras->efectivo_ingresado, 2, '.', ',') . '</td>
                            <td class="text-center">' . $retefuente_compras->cuenta_destino . '</td>
                            <td class="text-center">$' . number_format($retefuente_compras->valor_consignado, 2, '.', ',') . '</td> 
                            <td class="text-center">' . $retefuente_compras->responsable . '</td>                                
                            <td class="text-center">' . date("Y-m-d", strtotime($retefuente_compras->fecha_trans)) . '</td>
                        </tr>';
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong><center>La retención en la fuente, ya se encuentra anulada.</center></strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>La retencion en la fuente, no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

}
