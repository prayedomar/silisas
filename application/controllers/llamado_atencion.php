<?php

class Llamado_atencion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_llamado_atencion";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['t_sancion'] = $this->select_model->t_sancion();

        $data['action_validar'] = base_url() . "llamado_atencion/validar";
        $data['action_crear'] = base_url() . "llamado_atencion/insertar";

        $data['action_llenar_faltas'] = base_url() . "llamado_atencion/llena_falta_laboral";

        $this->parser->parse('llamado_atencion/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('empleado', 'Empleado', 'required|callback_select_default');
            $this->form_validation->set_rules('t_falta_laboral', 'Falta Laboral', 'required');
            $this->form_validation->set_rules('t_sancion', 'Sanción a Imponer', 'required|callback_select_default');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required|trim|xss_clean|max_length[255]');

            $error_entre_fechas = "";
            //Si escogió suspencion laboral, valido los dos campos.
            if ($this->input->post('t_sancion') == '2') {
                $this->form_validation->set_rules('fecha_inicio', 'Fecha Inicial de la Suspensión', 'required|xss_clean|callback_fecha_valida');
                $this->form_validation->set_rules('fecha_fin', 'Fecha Final de la Suspensión', 'required|xss_clean|callback_fecha_valida');
                if (($this->fecha_valida($this->input->post('fecha_inicio'))) && ($this->fecha_valida($this->input->post('fecha_fin')))) {
                    if (($this->dias_entre_fechas($this->input->post('fecha_inicio'), $this->input->post('fecha_fin'))) < 0) {
                        $error_entre_fechas = "<p>La fecha final no puede ser menor que la fecha inicial.</p>";
                    }
                }
            }
            if (($this->form_validation->run() == FALSE) || ($error_entre_fechas != "")) {
                echo form_error('empleado') . form_error('t_falta_laboral') . form_error('t_sancion') . form_error('fecha_inicio') . form_error('fecha_fin') . $error_entre_fechas . form_error('descripcion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        if ($this->input->post('submit')) {
            list($id_empleado, $dni_empleado) = explode("-", $this->input->post('empleado'));
            $t_falta_laboral = $this->input->post('t_falta_laboral');
            $t_sancion = $this->input->post('t_sancion');
            $descripcion = ucfirst(strtolower($this->input->post('descripcion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $id_llamado_atencion = ($this->select_model->nextId_llamado_atencion()->id) + 1;

            $error1 = $this->insert_model->llamado_atencion($id_llamado_atencion, $id_empleado, $dni_empleado, $t_falta_laboral, $t_sancion, 1, $descripcion, $fecha_trans, $id_responsable, $dni_responsable);

            $data["tab"] = "crear_llamado_atencion";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "llamado_atencion/crear";
            $data['msn_recrear'] = "Crear otro llamado de atención";
            if (isset($error1)) {
                $data['trans_error'] = $error1;
                $this->parser->parse('trans_error', $data);
            } else {
                //Si se va a realizar una suspension laboral.
                if ($t_sancion == '2') {
                    $fecha_inicio = $this->input->post('fecha_inicio');
                    $fecha_fin = $this->input->post('fecha_fin');
                    $error2 = $this->insert_model->suspension_laboral($id_empleado, $dni_empleado, $id_llamado_atencion, $fecha_inicio, $fecha_fin, 1);
                    if (isset($error2)) {
                        $data['trans_error'] = $error2;
                        $this->parser->parse('trans_error', $data);
                        return;
                    }
                } else {
                    //En el caso en que halla escogido anular el contrato
                    if ($t_sancion == '3') {
                        $error3 = $this->update_model->contrato_laboral_estado($id_empleado, $dni_empleado, 2);
                        if (isset($error3)) {
                            $data['trans_error'] = $error3;
                            $this->parser->parse('trans_error', $data);
                            return;
                        }
                    }
                }
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }
    
    public function llena_falta_laboral() {
        if ($this->input->is_ajax_request()) {
            $faltas = $this->select_model->t_falta_laboral();
            if (($faltas == TRUE)) {
                foreach ($faltas as $fila) {
                    echo '<tr>
                            <td class="text-center"><input type="radio" class="exit_caution" name="t_falta_laboral"  value="' . $fila->id . '"/></td>
                            <td>' . $fila->falta . '</td>
                            <td class="text-center">' . $fila->gravedad . '</td>
                        </tr>';
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url());
        }
    }    

    //Metodos para Consultar    
    
}
