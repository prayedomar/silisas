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
            $this->escapar($_POST);
            $this->form_validation->set_rules('dni', 'Tipo de Identificación', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Número de Identificación', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');            
            $this->form_validation->set_rules('prefijo_factura', 'Prefijo de factura', 'required|callback_select_default');
            $this->form_validation->set_rules('id_factura', 'Número de factura', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('total', 'Valor de la retención en la fuente', 'required|trim|xss_clean|max_length[18]|callback_miles_numeric|callback_mayor_cero');
            $this->form_validation->set_rules('valor_retirado', 'Valor Retirado de la Cuenta Bancaria', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('efectivo_retirado', 'Valor Retirado de la Caja de Efectivo', 'trim|xss_clean|max_length[18]|callback_miles_numeric|callback_valor_positivo');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');
            
            if ($this->form_validation->run() == FALSE) {
                echo form_error('dni') . form_error('id') . form_error('total') . form_error('valor_retirado') . form_error('efectivo_retirado') . $error_valores . form_error('observacion');
            } else {
                echo "OK";
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
                $response = array(
                    'respuesta' => 'OK',
                    'nombre_alumno' => $alumno->nombre_alumno,
                    'tipo_curso' => $alumno->tipo_curso,
                    't_curso' => $alumno->t_curso
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
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $id_responsable = $this->session->userdata('idResponsable');
            $dni_responsable = $this->session->userdata('dniResponsable');
            $sede = $this->select_model->empleado($id_responsable, $dni_responsable)->sede_ppal;
            $prefijo_reporte_alumno = $this->select_model->sede_id($sede)->prefijo_trans;
            $id_reporte_alumno = ($this->select_model->nextId_reporte_alumno($prefijo_reporte_alumno)->id) + 1;
            $t_trans = 14; //Retencion en la fuente ventas
            $credito_debito = 0; //Débito

            $data["tab"] = "crear_reporte_alumno";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "reporte_alumno/crear";
            $data['msn_recrear'] = "Crear otra retención";
            $data['url_imprimir'] = base_url() . "reporte_alumno/consultar_pdf/" . $prefijo_reporte_alumno . "_" . $id_reporte_alumno . "/I";

            $error = $this->insert_model->movimiento_transaccion($t_trans, $prefijo_reporte_alumno, $id_reporte_alumno, $credito_debito, $total, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $cuenta_origen, $valor_retirado, 1, $sede, $id_responsable, $dni_responsable);
            if (isset($error)) {
                $data['trans_error'] = $error . "<p>Comuníque éste error al departamento de sistemas.</p>";
                $this->parser->parse('trans_error', $data);
            } else {
                $error1 = $this->insert_model->reporte_alumno($prefijo_reporte_alumno, $id_reporte_alumno, $prefijo_factura, $id_factura, $total, $cuenta_origen, $valor_retirado, $sede_caja_origen, $t_caja_origen, $efectivo_retirado, $sede, $observacion, $id_responsable, $dni_responsable);
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

}
