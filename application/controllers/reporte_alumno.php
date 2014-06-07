<?php

class Reporte_alumno extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

//Crear: Nomina
    function crear() {
        $data["tab"] = "crear_reporte_alumno";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['dni'] = $this->select_model->t_dni_alumno();
        $data['action_validar'] = base_url() . "reporte_alumno/validar";
        $data['action_crear'] = base_url() . "reporte_alumno/insertar";
        $data['action_recargar'] = base_url() . "reporte_alumno/crear";
        $data['action_validar_alumno'] = base_url() . "reporte_alumno/validar_alumno";
        $this->parser->parse('reporte_alumno/crear', $data);
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
    
    public function validar_alumno() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $dni_alumno = $this->input->post('dni');
            $id_alumno = $this->input->post('id');
            $alumno = $this->select_model->alumno($id_alumno, $dni_alumno);
            if ($alumno == TRUE) {
                $response = array(
                    'respuesta' => 'OK',
                    'nombre1' => $alumno->nombre1,
                    'nombre2' => $alumno->nombre2,
                    'apellido1' => $alumno->apellido1,
                    'apellido2' => $alumno->apellido2,
                    'genero' => $alumno->genero,
                    'fecha_nacimiento' => $alumno->fecha_nacimiento,
                    'pais' => $alumno->pais,
                    'provincia' => $alumno->provincia,
                    'ciudad' => $alumno->ciudad,
                    't_domicilio' => $alumno->t_domicilio,
                    'direccion' => $alumno->direccion,
                    'barrio' => $alumno->barrio,
                    'telefono' => $alumno->telefono,
                    'celular' => $alumno->celular,
                    'email' => $alumno->email,
                    'matricula' => $alumno->matricula,
                    'velocidad_ini' => $alumno->velocidad_ini,
                    'comprension_ini' => $alumno->comprension_ini,
                    't_curso' => $alumno->t_curso,
                    'cant_clases' => $alumno->cant_clases,
                    'estado' => $alumno->estado,
                    'observacion' => $alumno->observacion
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
