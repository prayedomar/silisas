<?php

class MAtricula extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    //Crear: Matrícula
    function crear() {
        $data["tab"] = "crear_matricula";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['dni_titular'] = $this->select_model->t_dni_titular();
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
        $data['sede_ppal'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['action_llena_plan_comercial'] = base_url() . "matricula/llena_plan_comercial";
        $data['action_llena_ejecutivo'] = base_url() . "matricula/llena_empleado_rrpp_sedePpal";
        $data['action_validar'] = base_url() . "matricula/validar";
        $data['action_crear'] = base_url() . "matricula/insertar";

        $this->parser->parse('matricula/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('contrato', 'Número de Contrato Físico', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('dni_titular', 'Tipo de Id. del Titular', 'required|callback_select_default');
            $this->form_validation->set_rules('id_titular', 'Número de Id. del Titular', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('fecha_matricula', 'Fecha de Inicio', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('ejecutivo', 'Ejecutivo', 'required|callback_select_default');
            $this->form_validation->set_rules('sede_ppal', 'Sede Principal', 'required|callback_select_default');
            $this->form_validation->set_rules('plan', 'Plan Comercial', 'required');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            //Validamos que el número de contrato físico exista en dicha sede
            $error_contrato = "";
            if (($this->input->post('contrato')) && ($this->input->post('sede_ppal') != "default")) {
                $contrato = $this->input->post('contrato');
                $id_responsable = $this->session->userdata('idResponsable');
                $dni_responsable = $this->session->userdata('dniResponsable');
                $sede = $this->input->post('sede_ppal');
                $check_contrato = $this->select_model->contrato_matricula_id($contrato);
                if ($check_contrato != TRUE) {
                    $error_contrato = "<p>El contrato físico ingresado, no existe en la base de datos.</p>";
//                } else {
//                    $check_contrato = $this->select_model->contrato_matricula_id_sede($contrato, $sede);
//                    if ($check_contrato != TRUE) {
//                        $error_contrato = "<p>El contrato físico ingresado, no se encuentra en la sede principal escogida.</p>";
                    } else {
                        $check_contrato = $this->select_model->contrato_matricula_vacio_id($contrato);
                        if ($check_contrato != TRUE) {
                            $error_contrato = "<p>El contrato físico ingresado, no se encuentra vacío.</p>";
                        }
                    }
//                }
            }
            $error_titular = "";
            if (($this->input->post('id_titular')) && ($this->input->post('dni_titular'))) {
                $t_usuario = 2; //Titular
                $check_usuario = $this->select_model->usuario_id_dni_t_usuario($this->input->post('id_titular'), $this->input->post('dni_titular'), $t_usuario);
                if ($check_usuario != TRUE) {
                    $error_titular = "<p>El Titular ingresado, no existe en la Base de Datos.</p>";
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_contrato != "") || ($error_titular != "")) {
                echo form_error('contrato') . $error_contrato . form_error('fecha_matricula') . form_error('dni_titular') . form_error('id_titular') . $error_titular . form_error('ejecutivo') . form_error('sede_ppal') . form_error('plan') . form_error('observacion');
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
            $contrato = $this->input->post('contrato');
            $fecha_matricula = $this->input->post('fecha_matricula');
            $id_titular = $this->input->post('id_titular');
            $dni_titular = $this->input->post('dni_titular');
            list($id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo) = explode("-", $this->input->post('ejecutivo'));
            $plan = $this->input->post('plan');
            //La cantidad de alumnos y materiales es la misma que la que se describe en el plan comercial seleccionado.
            $cant_alumnos_disponibles = $this->select_model->t_plan_id($plan)->cant_alumnos;
            $cant_materiales_disponibles = $cant_alumnos_disponibles;
            $datacredito = 1;
            $juridico = 0;
            $liquidacion_escalas = 0;  //Hasta el moemento no se han creados las comisiones de las escalas
            $estado = 2; //2: Activo            
            $observacion = ucfirst(strtolower($this->input->post('observacion')));

            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->input->post('sede_ppal');

            $data["tab"] = "crear_matricula";
            $this->isLogin($data["tab"]);

            $error = $this->insert_model->matricula($contrato, $fecha_matricula, $id_titular, $dni_titular, $id_ejecutivo, $dni_ejecutivo, $cargo_ejecutivo, $plan, $cant_alumnos_disponibles, $cant_materiales_disponibles, $datacredito, $juridico, $liquidacion_escalas, $sede, $estado, $observacion, $id_responsable, $dni_responsable);

            if (isset($error)) {
                $data["tab"] = "crear_matricula";
                $this->load->view("header", $data);
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $data['url_recrear'] = base_url() . "matricula/crear";
                $data['msn_recrear'] = "Crear otra Matrícula";
                $this->parser->parse('trans_error', $data);
            } else {
                //Si todo salió bien, entonces cambiamos el estado del contrato fisico, de 1:vacío a 2:Activo
                $new_estado = 2;
                $error1 = $this->update_model->contrato_matricula_estado($contrato, $new_estado);
                if (isset($error1)) {
                    $data["tab"] = "crear_matricula";
                    $this->load->view("header", $data);
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $data['url_recrear'] = base_url() . "matricula/crear";
                    $data['msn_recrear'] = "Crear otra Matrícula";
                    $this->parser->parse('trans_error', $data);
                    return;
                }
                //Sí todo salió bien, Enviamos al formulario de liquidar_matricula
//                redirect(base_url() . 'liquidar_comisiones/crear/' . $contrato);
                //Temporalemnte mejor mostraremos el ok y listo. Boorar todo el parrafo siguiente y listo
                $data["tab"] = "crear_matricula";
                $this->load->view("header", $data);
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $data['url_recrear'] = base_url() . "matricula/crear";
                $data['msn_recrear'] = "Crear otra Matrícula";
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

    //Crear: Matrícula
    function editar_plan() {
        $data["tab"] = "editar_plan_matricula";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['dni_titular'] = $this->select_model->t_dni_titular();
        $id_responsable = $this->session->userdata('idResponsable');
        $dni_responsable = $this->session->userdata('dniResponsable');
        $data['empleado'] = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
        $data['action_recargar'] = base_url() . "matricula/editar_plan";
        $data['action_llena_t_plan_old'] = base_url() . "matricula/llena_t_plan_old";
        $data['action_llena_plan_comercial'] = base_url() . "matricula/llena_plan_comercial";
        $data['action_validar'] = base_url() . "matricula/validar_editar_plan";
        $data['action_crear'] = base_url() . "matricula/insertar_editar_plan";

        $this->parser->parse('matricula/editar_plan', $data);
        $this->load->view('footer');
    }

    function validar_editar_plan() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('id_matricula', 'Número de matrícula', 'required|trim|min_length[3]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('plan_old', 'Tipo de plan actual', 'required');
            $this->form_validation->set_rules('plan_new', 'Nuevo tipo de plan', 'required');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            if ($this->form_validation->run() == FALSE) {
                echo form_error('id_matricula') . form_error('plan_old') . form_error('plan_new') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar_editar_plan() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $id_matricula = $this->input->post('id_matricula');
            $plan_old = $this->input->post('plan_old');
            $plan_new = $this->input->post('plan_new');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');

            $data["tab"] = "editar_plan_matricula";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "matricula/editar_plan";
            $data['msn_recrear'] = "Editar otro tipo de plan de una matrícula";

            $error = $this->update_model->cambio_plan_matricula($id_matricula, $plan_new);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->cambio_plan_matricula($id_matricula, $plan_old, $plan_new, $observacion, $id_responsable, $dni_responsable);
                if (isset($error1)) {
                    $data['trans_error'] = $error1 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                    $this->parser->parse('trans_error', $data);
                } else {
                    $this->parser->parse('trans_success', $data);
                }
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_t_plan_old() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $id_matricula = $this->input->post('matricula');
            $matricula = $this->select_model->matricula_titular_idMatricula($id_matricula);
            if ($matricula == TRUE) {
                $id_responsable = $this->session->userdata('idResponsable');
                $dni_responsable = $this->session->userdata('dniResponsable');
                $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
                $check_matricula_sede = $this->select_model->matricula_id_sedes_responsable($id_matricula, $id_responsable, $dni_responsable);
//                $check_matricula_sede = TRUE; //Por el momento colocamos que puedan editar matriculas de cualquier sede.
                if ($check_matricula_sede == TRUE) {
                    $response = array(
                        'respuesta' => 'OK',
                        'nombreTitular' => $matricula->titular,
                        'plan_old' => $matricula->id,
                        'filasTablaOld' => '',
                        'filasTablaNew' => ''
                    );
                    $response['filasTablaOld'] = '<tr>
                            <td class="text-center">' . $matricula->nombre . '</td>
                            <td class="text-center">' . $matricula->anio . '</td>                                
                            <td class="text-center">' . $matricula->cant_alumnos . '</td>
                            <td class="text-center">$' . number_format($matricula->valor_total, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($matricula->valor_inicial, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($matricula->valor_cuota, 0, '.', ',') . '</td>                                
                            <td class="text-center">' . $matricula->cant_cuotas . '</td>                              
                        </tr>';
                    //Llenamos los nuevos planes que tengan el mismo numero de alumnos
                    $planes = $this->select_model->t_plan_igual_cantAlumnos($id_matricula);
                    if ($planes == TRUE) {
                        foreach ($planes as $fila) {
                            $response['filasTablaNew'] .= '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="plan_new" id="plan_new" value="' . $fila->id . '"/></td>
                            <td class="text-center">' . $fila->nombre . '</td>
                            <td class="text-center">' . $fila->anio . '</td>                                
                            <td class="text-center">' . $fila->cant_alumnos . '</td>
                            <td class="text-center">$' . number_format($fila->valor_total, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_inicial, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_cuota, 0, '.', ',') . '</td>                                
                            <td class="text-center">' . $fila->cant_cuotas . '</td>
                        </tr>';
                        }
                    } else {
                        $response['filasTablaNew'] = "";
                    }
                    echo json_encode($response);
                    return false;
                } else {
                    $response = array(
                        'respuesta' => 'error',
                        'mensaje' => '<p><strong>La matrícula no pertenece a sus sedes autorizadas.</strong></p>'
                    );
                    echo json_encode($response);
                    return false;
                }
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong>La matrícula no existe en la base de datos.</strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_plan_comercial() {
        if ($this->input->is_ajax_request()) {
            $planes = $this->select_model->t_plan_activo();
            if ($planes == TRUE) {
                foreach ($planes as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="plan" id="plan" value="' . $fila->id . '"/></td>
                            <td class="text-center">' . $fila->nombre . '</td>
                            <td class="text-center">' . $fila->anio . '</td>                                
                            <td class="text-center">' . $fila->cant_alumnos . '</td>
                            <td class="text-center">$' . number_format($fila->valor_total, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_inicial, 0, '.', ',') . '</td>
                            <td class="text-center">$' . number_format($fila->valor_cuota, 0, '.', ',') . '</td>                                
                            <td class="text-center">' . $fila->cant_cuotas . '</td>
                        </tr>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_empleado_rrpp_sedePpal() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idResposable')) && ($this->input->post('dniResposable'))) {
                $id_responsable = $this->input->post('idResposable');
                $dni_responsable = $this->input->post('dniResposable');
                $empleados = $this->select_model->empleado_RRPP_sede_ppal($id_responsable, $dni_responsable);
                //Validamos que la consulta devuelva algo
                if ($empleados == TRUE) {
                    foreach ($empleados as $fila) {
                        echo '<option value="' . $fila->id . "-" . $fila->dni . "-" . $fila->cargo . '">' . $fila->nombre1 . " " . $fila->nombre2 . " " . $fila->apellido1 . " " . $fila->apellido2 . '</option>';
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

    public function consultar() {
        $this->load->model('matriculam');
        $this->load->model('t_cargom');
        $this->load->model('t_planm');
        $this->load->model('est_alumnom');
        $this->load->model('sedem');
        $this->load->model('t_cursom');
        $this->load->model('alumnom');
        $data["tab"] = "consultar_matricula";
        $this->isLogin($data["tab"]);
        $data['lista_cargos'] = $this->t_cargom->listar_todas_los_cargos_relaciones_publicas();
        $data['lista_planes'] = $this->t_planm->listar_todas_los_planes();
        $data['tipos_cursos'] = $this->t_cursom->listar_todas_los_tipos_curso();
        $data['estados_alumnos'] = $this->est_alumnom->listar_todas_los_estados_de_alumno();
        $data['lista_sedes'] = $this->sedem->listar_todas_las_sedes_sin_resposanble();
        if (!empty($_GET["depto"])) {
            $this->load->model('t_cargom');
            $data['lista_cargos'] = $this->t_cargom->listar_todas_los_cargos_por_depto($_GET['depto']);
        }
        $filasPorPagina = 20;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $cantidad = $this->matriculam->cantidad_matriculas($_GET, $inicio, $filasPorPagina);
        $cantidad = $cantidad[0]->cantidad;
        $data['cantidad'] = $cantidad;
        $data['cantidad_paginas'] = ceil($cantidad / $filasPorPagina);
        $data["lista"] = $this->matriculam->listar_matriculas($_GET, $inicio, $filasPorPagina);
        foreach ($data["lista"] as $row) {
            $alumnos = $this->alumnom->obtenerAlumnosPorMatricula($row->contrato);
            $lista = "";
            foreach ($alumnos as $row2) {
                $lista .= $row2->dni_alumno . " " . $row2->id . " " . $row2->nombre1 . " " . $row2->nombre2 . " " . $row2->apellido1 . " " . $row2->apellido2 . " <br>";
            }
            $row->lista_alumnos = $lista;
        }
        $this->load->view("header", $data);
        $this->load->view("matricula/consultar");
        $this->load->view("footer");
    }

    public function excel() {
        $this->load->model('matriculam');
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=reporte_matricula_" . date("Y-m-d") . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $lista = $this->matriculam->listar_matriculas_excel($_GET);
        ?>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <th>Contrato</th>
                <th>Fecha matricula</th>
                <th>ID titular</th>
                <th>Nombre titular</th>
                <th>Plan</th>
                <th>Sede</th>
            </tr>
            <?php foreach ($lista as $row) { ?>
                <tr>
                    <td><?= $row->contrato ?></td>
                    <td><?= $row->fecha_matricula ?></td>
                    <td><?= $row->nombre_dni . " " . $row->id_titular ?></td>
                    <td><?= utf8_decode($row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2) ?></td>
                    <td><?= utf8_decode($row->nombre_plan) ?></td>
                    <td><?= utf8_decode($row->nombre_sede) ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php
    }

    function consultar_plan_pagos() {
        $data["tab"] = "consultar_plan_pagos";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['error_consulta'] = "";
        $data['action_crear'] = base_url() . "matricula/validar_plan_pagos";
        $data['action_recargar'] = base_url() . "matricula/consultar_plan_pagos";
        $this->parser->parse('matricula/consultar_plan_pagos', $data);
        $this->load->view('footer');
    }

    public function validar_plan_pagos() {
        $this->escapar($_POST);
        $this->form_validation->set_rules('id', 'Número de Matrícula', 'required|trim|max_length[13]|integer|callback_valor_positivo');
        $id = $this->input->post('id');
        $error_transaccion = "";
        if ($this->input->post('id')) {
            $matricula = $this->select_model->matricula_id($id);
            if ($matricula == TRUE) {
                if ($matricula->estado == 5) {
                    $error_transaccion = "La matrícula, se encuentra anulada.";
                }
            } else {
                $error_transaccion = "La matrícula, no existe en la base de datos.";
            }
        }
        if (($this->form_validation->run() == FALSE) || ($error_transaccion != "")) {
            $data["tab"] = "consultar_plan_pagos";
            $this->isLogin($data["tab"]);
            $data["error_consulta"] = form_error('id') . $error_transaccion;
            $data["id"] = $id;
            $this->load->view("header", $data);
            $data['action_crear'] = base_url() . "matricula/validar_plan_pagos";
            $this->parser->parse('matricula/consultar_plan_pagos', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . "matricula/consultar_pdf/" . $id . "/I");
        }
    }

    function consultar_pdf($id, $salida_pdf) {
        $this->load->model('matriculam');
        $matricula = $this->matriculam->matricula_id($id);
        if ($matricula == TRUE) {
            $dni_abreviado_ejecutivo = $this->select_model->t_dni_id($matricula->dni_ejecutivo)->abreviacion;
            $dni_abreviado_titular = $this->select_model->t_dni_id($matricula->dni_titular)->abreviacion;

            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Factura de Venta ' . $id . ' Sili S.A.S');
            $pdf->SetSubject('Factura de Venta ' . $id . ' Sili S.A.S');
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
            $html .= 'h2{line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:10px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:15px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:100px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:130px;}';
            $html .= 'td.c4{width:235px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c9{width:115px;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c20{width:240px;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c21{width:140px;height:33px;line-height:32px;font-weight: bold;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c22{width:110px;height:33px;line-height:32px;font-weight: bold;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;line-height:25px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;height:30px;line-height:20px;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.c29{background-color:#F5F5F5;}';
            $html .= 'td.c30{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'td.a3{text-align:justify;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'th.a3{background-color:#F5F5F5;}';
            $html .= 'th.d1{width:80px;}';
            $html .= 'th.d2{width:110px;}';
            $html .= 'th.d3{width:320px;}';
            $html .= 'th.d4{width:110px;}';
            $html .= 'th.d5{width:110px;}';
            $html .= 'td.d8{width:365px;}';            
            $html .= 'td.d10{width:730px;}';    
            $html .= 'th.d6{height:30px;line-height:25px;}';
            $html .= 'th.d9{height:30px;line-height:30px;}';
            $html .= 'th.d7{border-top-color:#000000;border-bottom-color:#000000;border-left-color:#000000;border-right-color:#000000;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="4" colspan="2"><h2> </h2><br><br><br><br><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '<p class="b1"><br><br><br><br>Medellín: Calle 47D # 77 AA - 67  (Floresta)  / Tels.: 4114107 – 4126800<br>'
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
                    . '<td class="a2 c24" colspan="2">PLAN DE PAGOS DE MATRÍCULA<br></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Contrato:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . $id . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . $matricula->responsable . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Sede de ingreso:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $matricula->sede_ppal . '</td>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Fecha de ingreso:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . date("Y-m-d", strtotime($matricula->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Nombre del plan:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $matricula->nombre_plan . '</td>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Ejecutivo de venta:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $matricula->ejecutivo . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Titular:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $matricula->titular . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Documento titular:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $dni_abreviado_titular . ' ' . $matricula->id_titular . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Dirección:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $matricula->direccion . '</td>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Telefonos:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $matricula->telefono . ' ' . $matricula->celular . '</td>'
                    . '</tr>'
                    . '</table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<th class="d1 c23 d6 a2 d7 a3"><b># cuota</b></th>'
                    . '<th class="d2 c23 d6 a2 d7 a3"><b>Fecha límite</b></th>'
                    . '<th class="d3 c23 d6 a2 d7 a3"><b>Descripción </b></th>'
                    . '<th class="d4 c23 d6 a2 d7 a3"><b>Valor</b></th>'
                    . '<th class="d5 c23 d6 a2 d7 a3"><b>saldo</b></th>'
                    . '</tr>';
            if ($matricula->cant_cuotas == 0) { //Plan contado
                $t_detalle = "Pago Total modulo enseñanza lectora"; //T_detalle: pago total 
            } else {
                $t_detalle = "Pago inicial modulo enseñanza lectora"; //T_detalle: pago inicial
            }
            //Ingresamos la primera cuota manual
            $saldo = $matricula->valor_total - $matricula->valor_inicial;
            $html .= '<tr>'
                    . '<td class="d1 a2 c30 c27 c28">0</td>'
                    . '<td class="d2 a2 c30 c27 c28">' . date("Y-m-d", strtotime($matricula->fecha_trans)) . '</td>'
                    . '<td class="d3 c30 c27 c28">' . $t_detalle . '</td>'
                    . '<td class="d4 a2 c30 c27 c28">$' . number_format($matricula->valor_inicial, 0, '.', ',') . '</td>'
                    . '<td class="d5 a2 c30 c27 c28">$' . number_format($saldo, 0, '.', ',') . '</td>'
                    . '</tr>';

            $cont_filas = 1;
            for ($i = 1; $i <= $matricula->cant_cuotas; $i++) {
                $saldo = $saldo - $matricula->valor_cuota;
                $cont_filas ++;
                $html .= '<tr>'
                        . '<td class="d1 a2 c30 c27 c28">' . $i . '</td>'
                        . '<td class="d2 a2 c30 c27 c28">' . date("Y-m-d", strtotime("$matricula->fecha_trans +$i month")) . '</td>'
                        . '<td class="d3 c30 c27 c28">Abono modulo enseñanza lectora</td>'
                        . '<td class="d4 a2 c30 c27 c28">$' . number_format($matricula->valor_cuota, 0, '.', ',') . '</td>'
                        . '<td class="d5 a2 c30 c27 c28">$' . number_format($saldo, 0, '.', ',') . '</td>'
                        . '</tr>';
            }
            for ($i = $cont_filas; $i < 16; $i++) {
                $html .= '<tr><td class="d1 c27 c28 c30"></td><td class="d2 c27 c28 c30"></td><td class="d3 c27 c28 c30"></td><td class="d4 c27 c28 c30"></td><td class="d5 c27 c28 c30"></td></tr>';
            }
            $html .= '<tr>'
                    . '<td colspan="3" class="c25 c28"></td>'
                    . '<th class="d4 c23 d9 a2 d7 a3"><b>Total pagado</b></th>'
                    . '<th class="d5 c23 d9 a2 d7 a3"><b>$' . number_format($matricula->valor_total, 0, '.', ',') . '</b></th>'                    
                    . '</tr></table><br><br><table>'
                    . '<tr>'
                    . '<td colspan="2" class="d10 c20 a2 c25 c26 c27 c28 a2"><br><br><b>"Transcurridos 4 días después de la fecha límite de pago, el sistema empezará a generar intereses por mora en base a la tasa maxíma de usura definida por la superintendencia financiera de Colombia, por lo cual le invitamos a dar cumplimiento oportuno al compromiso comercial adquirido con nuestra compañía"</b><br></td>'
                    . '</tr>'                    
                    . '<tr>'
                    . '<td class="d8 c20 a2 c25 c26 c27 c28">Certifico que estoy de acuerdo con éste plan de pagos.<br><br><br><br><br>______________________________________<br>Firma y documento titular</td>'
                    . '<td class="d8 c20 a2 c25 c26 c27 c28"><br><br><br><br><br><br>______________________________________<br>Firma y sello empresa</td>'
                    . '</tr></table><p class="b3">- Copia para el titular -</p>';
            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode('Factura de Venta ' . $id . ' Sili S.A.S.pdf');
            $pdf->Output($nombre_archivo, $salida_pdf);
        } else {
            redirect(base_url() . 'matricula/consultar_plan_pagos/');
        }
    }

}
