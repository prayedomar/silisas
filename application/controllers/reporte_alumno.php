<?php

class Reporte_alumno extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
        $this->load->model('alumnom');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_reporte_alumno";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['dni'] = $this->select_model->t_dni_alumno();
        $data['t_curso'] = $this->select_model->t_curso();
        $data['action_validar'] = base_url() . "reporte_alumno/validar";
        $data['action_crear'] = base_url() . "reporte_alumno/insertar";
        $data['action_recargar'] = base_url() . "reporte_alumno/crear";
        $data['action_validar_alumno'] = base_url() . "reporte_alumno/validar_alumno";
        $data['action_actualizar_t_curso'] = base_url() . "reporte_alumno/actualizar_t_curso";
        $data['action_llena_agregar_ejercicio'] = base_url() . "reporte_alumno/llena_agregar_ejercicio";
        $data['action_llena_ejercicio_habilidad'] = base_url() . "reporte_alumno/llena_ejercicio_habilidad";
        $this->parser->parse('reporte_alumno/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $data["tab"] = "crear_reporte_alumno";
            $this->isLogin($data["tab"]);
            $this->escapar($_POST);
            $this->form_validation->set_rules('dni', 'Tipo de Identificación', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Número de Identificación', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('fecha_clase', 'Fecha de la clase', 'required|xss_clean|callback_fecha_valida');
            $this->form_validation->set_rules('asistencia', '¿Asistió a la clase?', 'required|callback_select_default');
            $this->form_validation->set_rules('fase', 'Fase', 'trim|xss_clean|max_length[100]');
            $this->form_validation->set_rules('meta_v', 'Meta velocidad', 'trim|xss_clean|max_length[6]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('meta_c', 'Meta comprensión', 'trim|xss_clean|callback_miles_numeric|callback_porcentaje');
            $this->form_validation->set_rules('meta_r', 'Meta Retención', 'trim|xss_clean|callback_miles_numeric|callback_porcentaje');
            $this->form_validation->set_rules('cant_practicas', 'Cantidad de prácticas realizadas', 'trim|max_length[5]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('lectura', 'Lectura', 'trim|xss_clean|max_length[100]');
            $this->form_validation->set_rules('vlm', 'Velocidad mental actual', 'trim|xss_clean|max_length[6]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('vlv', 'Velocidad verbal actual', 'trim|xss_clean|max_length[6]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('c', 'Comprensión actual', 'trim|xss_clean|callback_miles_numeric|callback_porcentaje');
            $this->form_validation->set_rules('r', 'Retención actual', 'trim|xss_clean|callback_miles_numeric|callback_porcentaje');
            if ($this->input->post('asistencia') == "1") {
                $this->form_validation->set_rules('observacion_interna', 'Observación para manejo interno', 'required|trim|xss_clean|max_length[255]');
                $this->form_validation->set_rules('observacion_titular_alumno', 'Observación para el titular y/o el alumno', 'required|trim|xss_clean|max_length[255]');
            } else {
                $this->form_validation->set_rules('observacion_interna', 'Observación para manejo interno', 'trim|xss_clean|max_length[255]');
                $this->form_validation->set_rules('observacion_titular_alumno', 'Observación para el titular y/o el alumno', 'trim|xss_clean|max_length[255]');
            }
            //Validamos los conceptos de nomina
            $error_ejercicios = "";
            if ($this->input->post('t_habilidad')) {
                $t_habilidad = $this->input->post('t_habilidad');
                foreach ($t_habilidad as $fila) {
                    if ($fila == "default") {
                        $error_ejercicios .= "<p>El campo Nombre de habilidad, es obligatorio.</p>";
                    }
                }
            }
            if ($this->input->post('t_ejercicio')) {
                $t_ejercicio = $this->input->post('t_ejercicio');
                foreach ($t_ejercicio as $fila) {
                    if ($fila == "default") {
                        $error_ejercicios .= "<p>El campo Ejericio realizado, es obligatorio.</p>";
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_ejercicios != "")) {
                echo form_error('dni') . form_error('id') . form_error('fecha_clase') . form_error('asistencia') . form_error('fase') . form_error('meta_v') . form_error('meta_c') . form_error('meta_r') . form_error('cant_practicas') . form_error('lectura') . form_error('vlm') . form_error('vlv') . form_error('c') . form_error('r') . form_error('observacion_interna') . form_error('observacion_titular_alumno') . $error_ejercicios;
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        if ($this->input->post('submit')) {
            $data["tab"] = "crear_reporte_alumno";
            $this->isLogin($data["tab"]);
            $this->escapar($_POST);
            $id_alumno = $this->input->post('id');
            $dni_alumno = $this->input->post('dni');
            $fecha_clase = $this->input->post('fecha_clase');
            $asistencia = $this->input->post('asistencia');
            if ($this->input->post('etapa') == "default") {
                $etapa = NULL;
            } else {
                $etapa = $this->input->post('etapa');
            }
            $fase = ucfirst(mb_strtolower($this->input->post('fase')));
            $meta_v = $this->input->post('meta_v');
            $meta_c = $this->input->post('meta_c');
            $meta_r = $this->input->post('meta_r');
            $cant_practicas = $this->input->post('cant_practicas');
            $lectura = ucfirst(mb_strtolower($this->input->post('lectura')));
            $vlm = $this->input->post('vlm');
            $vlv = $this->input->post('vlv');
            $c = $this->input->post('c');
            $r = $this->input->post('r');
            $vigente = 1;
            $observacion_interna = ucfirst(mb_strtolower($this->input->post('observacion_interna')));
            $observacion_titular_alumno = ucfirst(mb_strtolower($this->input->post('observacion_titular_alumno')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $id_reporte = ($this->select_model->nextId_reporte_alumno()->id) + 1;
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "reporte_alumno/crear";
            $data['msn_recrear'] = "Crear otro reporte de enseñanza";

            $error = $this->insert_model->reporte_alumno($id_reporte, $id_alumno, $dni_alumno, $fecha_clase, $asistencia, $etapa, $fase, $meta_v, $meta_c, $meta_r, $cant_practicas, $lectura, $vlm, $vlv, $c, $r, $vigente, $observacion_interna, $observacion_titular_alumno, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                //traemos los campos de los ejercicios
                if (($this->input->post('t_habilidad')) && ($this->input->post('t_habilidad'))) {
                    $t_habilidad = $this->input->post('t_habilidad');
                    $t_ejercicio = $this->input->post('t_ejercicio');
                    $i = 0;
                    foreach ($t_habilidad as $fila) {
                        //En el caso que sean conceptos pendiente de nomina por ser actualizados a OK
                        $error3 = $this->insert_model->ejercicio_ensenanza($id_reporte, $fila, $t_ejercicio[$i]);
                        if (isset($error3)) {
                            $data['trans_error'] = $error3 . "<p>Comuníque éste error al departamento de sistemas.</p>";
                            $this->parser->parse('trans_error', $data);
                            return;
                        }
                        $i++;
                    }
                }
                //Enviamos el correo al titular y al alumno 
                $this->load->model('titularm');
                $alumno = $this->alumnom->alumno_id_dni($id_alumno, $dni_alumno);
                $titular = $this->titularm->titular_matricula($alumno->matricula);
                if (($alumno->email != "") || ($titular->email != "")) {
                    $responsable = $this->select_model->empleado($id_responsable, $dni_responsable);
                    if ($alumno->genero == "M") {
                        $de_quien = "del alumno: ";
                    } else {
                        $de_quien = "de la alumna: ";
                    }
                    if ($asistencia == 1) {
                        $asistio = "Si";
                    } else {
                        $asistio = "No";
                    }
                    $this->load->model('ejercicio_ensenanzam');
                    $ejercicios = $this->ejercicio_ensenanzam->ejercicio_reporte_ensenanza($id_reporte);
                    $lista_ejercicios = "";
                    foreach ($ejercicios as $row) {
                        $lista_ejercicios .= "<b>> " . $row->habilidad . ": </b>" . $row->ejercicio . ".<br>";
                    }
                    $asunto = "Reporte de clase - SILI S.A.S";
                    $mensaje = '<p><h2><b>Reporte de clase</b></h2></p>'
                            . '<p>A continuación encontrará el reporte de la clase de lectura ' . $de_quien . $alumno->nombre_alumno . ', del dia: ' . $fecha_clase . '.<br/>'
                            . '<center>'
                            . '<table>'
                            . '<tr>'
                            . '<td style="width:260px;"><b>¿Asistió a clase?: </b></td>'
                            . '<td>' . $asistio . '</td>'
                            . '</tr>';
                    if ($etapa != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Etapa al finalizar la clase: </b></td>'
                                . '<td>' . $etapa . '</td>'
                                . '</tr>';
                    }
                    if ($meta_v != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Meta velocidad: </b></td>'
                                . '<td>' . $meta_v . '</td>'
                                . '</tr>';
                    }
                    if ($meta_c != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Meta comprensión: </b></td>'
                                . '<td>' . $meta_c . '</td>'
                                . '</tr>';
                    }
                    if ($meta_r != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Meta retención: </b></td>'
                                . '<td>' . $meta_r . '</td>'
                                . '</tr>';
                    }
                    if ($cant_practicas != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Prácticas realizadas: </b></td>'
                                . '<td>' . $cant_practicas . '</td>'
                                . '</tr>';
                    }
                    if ($lectura != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Lectura vista en clase: </b></td>'
                                . '<td>' . $lectura . '</td>'
                                . '</tr>';
                    }
                    if ($vlv != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Velocidad verbal actual: </b></td>'
                                . '<td>' . $vlv . '</td>'
                                . '</tr>';
                    }
                    if ($c != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Comprensión actual: </b></td>'
                                . '<td>' . $c . '</td>'
                                . '</tr>';
                    }
                    if ($r != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Retención actual: </b></td>'
                                . '<td>' . $r . '</td>'
                                . '</tr>';
                    }
                    if ($lista_ejercicios != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Ejercicios realizados en clase: </b></td>'
                                . '<td>' . $lista_ejercicios . '</td>'
                                . '</tr>';
                    }
                    if ($observacion_titular_alumno != "") {
                        $mensaje .= '<tr>'
                                . '<td><b>Observaciones: </b></td>'
                                . '<td>' . $observacion_titular_alumno . '</td>'
                                . '</tr>';
                    }
                    $mensaje .= '<tr>'
                            . '<td><b>Responsable empresa: </b></td>'
                            . '<td>' . $responsable->nombre1 . ' ' . $responsable->nombre2 . ' ' . $responsable->apellido1 . '</td>'
                            . '</tr>'
                            . '</table>'
                            . '<center><br/><b>¡Gracias por darnos la oportunidad de servirle!</b></center>';
                    if ($titular->email != "") {
                        $this->sendEmail("silisascolombia@gmail.com", $titular->email, $asunto, $mensaje);
                    }
                    //VAlidamos que el alumno no sea el mismo titular
                    if (($alumno->email != "") && ($titular->email != $alumno->email)) {
                        $this->sendEmail("silisascolombia@gmail.com", $alumno->email, $asunto, $mensaje);
                    }
                }
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

    public function validar_alumno() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $dni_alumno = $this->input->post('dni');
            $id_alumno = $this->input->post('id');
            $alumno = $this->alumnom->alumno_id_dni($id_alumno, $dni_alumno);
            if ($alumno == TRUE) {
                $this->load->model('reporte_alumnom');
                $this->load->model('ejercicio_ensenanzam');
                $reportes_anteriores = $this->reporte_alumnom->reporte_alumno($id_alumno, $dni_alumno);
                if ($reportes_anteriores == TRUE) {
                    $html_reportes = '<div class="col-xs-12 separar_div"><legend>Reportes anteriores</legend><div class="overflow_tabla">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha de la clase</th>                                            
                                                <th class="text-center">¿Asistió?</th>
                                                <th class="text-center">Etapa</th>
                                                <th class="text-center">Fase</th>
                                                <th class="text-center"># Prácticas</th>
                                                <th class="text-center">lectura</th>
                                                <th class="text-center">V. Mental</th>
                                                <th class="text-center">V. Verbal</th>
                                                <th class="text-center">Comp.</th>
                                                <th class="text-center">Ret.</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    foreach ($reportes_anteriores as $fila) {
                        $ejercicios = $this->ejercicio_ensenanzam->ejercicio_reporte_ensenanza($fila->id);
                        $lista_ejercicios = "";
                        foreach ($ejercicios as $row) {
                            $lista_ejercicios .= "> " . $row->habilidad . ": " . $row->ejercicio . ".<br>";
                        }
                        $html_reportes .= '<tr>
                                <td class="text-center">' . date("Y-m-d", strtotime($fila->fecha_clase)) . '</td>                             
                                <td class="text-center">' . $fila->asistio . '</td>                            
                                <td class="text-center">' . $fila->etapa . '</td>
                                <td>' . $fila->fase . '</td>  
                                <td class="text-center">' . $fila->cant_practicas . '</td>                                
                                <td>' . $fila->lectura . '</td> 
                                <td class="text-center">' . $fila->vlm . ' p.p.m</td> 
                                <td class="text-center">' . $fila->vlv . ' p.p.m</td>
                                <td class="text-center">' . round($fila->c, 0) . '%</td> 
                                <td class="text-center">' . round($fila->r, 0) . '%</td> 
                                <td class="text-center"><button type="button" class="ver-detalles btn  btn-primary btn-sm" 
                                                    data-ejercicios="' . $lista_ejercicios . '"
                                                    data-meta_v="' . $fila->meta_v . ' p.p.m"
                                                    data-meta_c="' . round($fila->meta_c, 0) . '%"
                                                    data-meta_r="' . round($fila->meta_r, 0) . '%"
                                                    data-observacion_interna="' . $fila->observacion_interna . '"
                                                    data-observacion_titular_alumno="' . $fila->observacion_titular_alumno . '"
                                                    data-responsable="' . $fila->responsable . '"
                                                    data-fecha_trans="' . $fila->fecha_trans . '"
                                                    >Ver detalles</button></td>
                            </tr>';
                    }
                    $html_reportes .= '</tbody>
                        </table>
                    </div></div>';
                } else {
                    $html_reportes = "";
                }
                //Buscamos si tiene reportes vigente anteriores.
                $response = array(
                    'respuesta' => 'OK',
                    'nombre_alumno' => $alumno->nombre_alumno,
                    'tipo_curso' => $alumno->tipo_curso,
                    't_curso' => $alumno->t_curso,
                    'html_reportes' => $html_reportes
                );
                echo json_encode($response);
                return false;
            } else {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>El alumno no existe en la base de datos.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function actualizar_t_curso() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $dni_alumno = $this->input->post('dni');
            $id_alumno = $this->input->post('id');
            $t_curso = $this->input->post('tCurso');
            $error = $this->update_model->t_curso_alumno($id_alumno, $dni_alumno, $t_curso);
            //No se pudo crear el empleado
            if (isset($error)) {
                $response = array(
                    'respuesta' => 'error',
                    'mensaje' => '<p><strong><center>Hubo un problema al actualizar el tipo de curso. Recargue la página y vuelva a intentarlo.</center></strong></p>'
                );
                echo json_encode($response);
                return false;
            } else {
                $response = array(
                    'respuesta' => 'OK'
                );
                echo json_encode($response);
                return false;
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_agregar_ejercicio() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            if (($this->input->post('idUltimoEjercicio')) && ($this->input->post('id')) && ($this->input->post('dni'))) {
                $i = $this->input->post('idUltimoEjercicio') + 1;
                $this->load->model('t_habilidad_ensenanzam');
                $id = $this->input->post('id');
                $dni = $this->input->post('dni');
                $t_habilidad = $this->t_habilidad_ensenanzam->t_habilidad_t_curso_alumno($id, $dni);
                echo '<div class="div_input_group renglon_concepto renglon_nuevo" id="div_new_ejercicio_' . $i . '">
                                <div class="row">                                
                                    <div class="col-xs-4 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Nombre de habilidad<em class="required_asterisco">*</em></label>
                                            <select name="t_habilidad[]" id="t_habilidad" class="form-control exit_caution">
                                                <option value="default" selected="selected">Seleccione la habilidad</option>';
                if (($t_habilidad == TRUE)) {
                    foreach ($t_habilidad as $fila) {
                        echo '                  <option value="' . $fila->id . '">' . $fila->tipo . '</option>';
                    }
                }
                echo '                       </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-7 mermar_padding_div text-center">
                                        <div class="form-group sin_margin_bottom">
                                            <label>Ejericio realizado<em class="required_asterisco">*</em></label>
                                            <select name="t_ejercicio[]" id="t_ejercicio" class="form-control exit_caution" disabled="disabled">
                                                <option value="default">Selecciones primero el nombre de la habilidad</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-1  padding_remove">
                                        <label class="label_btn_remove">. </label>                                
                                        <div class="form-group" style="margin-left:-23px;">
                                            <button class="btn btn-default drop_new_ejercicio" id="' . $i . '" type="button"><span class="glyphicon glyphicon-remove"></span></button>  
                                        </div>
                                    </div>                                    
                                </div>
                            </div>';
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

    //Llenar elementos html dinamicamente
    public function llena_ejercicio_habilidad() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $id_alumno = $this->input->post('id');
            $dni_alumno = $this->input->post('dni');
            $id_habilidad = $this->input->post('habilidad');
            $this->load->model('t_ejercicio_ensenanzam');
            $ejercicios = $this->t_ejercicio_ensenanzam->t_ejercicio_t_cursoAlumno_t_habilidad($id_alumno, $dni_alumno, $id_habilidad);
            if ($ejercicios == TRUE) {
                foreach ($ejercicios as $fila) {
                    echo '<option value="' . $fila->id . '">' . $fila->tipo . '</option>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }

}
