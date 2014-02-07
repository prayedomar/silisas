<?php

class Cuenta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
    }

    function crear() {
        $data["tab"] = "crear_cuenta";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $id_responsable = $data['id_responsable'];
        $dni_responsable = $data['dni_responsable'];
        $data['empleado'] = $this->select_model->empleado_sedes_responsable($id_responsable, $dni_responsable);
        $data['sede'] = $this->select_model->sede_activa_responsable($id_responsable, $dni_responsable);
        $data['t_cuenta'] = $this->select_model->t_cuenta();
        $data['pais'] = $this->select_model->pais();

        $data['action_validar'] = base_url() . "cuenta/validar";
        $data['action_crear'] = base_url() . "cuenta/insertar";
        $data['action_llena_banco_pais'] = base_url() . "cuenta/llena_banco_pais";
        $this->parser->parse('cuenta/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('cuenta', 'Cuenta Bancaria', 'required|trim|min_length[12]|max_length[12]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('t_cuenta', 'Tipo de Cuenta', 'required|callback_select_default');
            $this->form_validation->set_rules('pais', 'País del Banco', 'required|callback_select_default');
            $this->form_validation->set_rules('banco', 'Banco', 'required|callback_select_default');
            $this->form_validation->set_rules('nombre_cuenta', 'Nombre de la Cuenta', 'required|trim|xss_clean|max_length[60]');

            //Validamos que la clave primaria no este repetida
            $duplicate_key = "";
            if ($this->input->post('cuenta')) {
                $check_usuario = $this->select_model->cuenta_banco_id($this->input->post('cuenta'));
                if ($check_usuario == TRUE) {
                    $duplicate_key = "<p>La Cuenta ingresada ya existe en la Base de Datos.</p>";
                }
            }

            if (($this->form_validation->run() == FALSE) || ($duplicate_key != "")) {
                echo form_error('cuenta') . $duplicate_key . form_error('t_cuenta') . form_error('pais') . form_error('banco') . form_error('nombre_cuenta');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        if ($this->input->post('submit')) {
            $cuenta = $this->input->post('cuenta');
            $t_cuenta = $this->input->post('t_cuenta');
            $banco = $this->input->post('banco');
            $nombre_cuenta = ucwords(strtolower($this->input->post('nombre_cuenta')));
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->cuenta($cuenta, $t_cuenta, $banco, $nombre_cuenta, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data["tab"] = "crear_cuenta";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "cuenta/crear";
            $data['msn_recrear'] = "Crear otra Cuenta";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //Cuando cree una cuenta, automaticamente se la debe asignar al sistema, para que tenga acceso a todo.
                //Colocamos vigente en cero porq al momento de crear una cuenta logicamente no esta asignada a ninguna sede.
                $this->insert_model->cuenta_x_sede($cuenta, 1, 0);
                $this->insert_model->cuenta_x_sede_x_empleado($cuenta, 1, 1, 1, 1);
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }
    
    public function llena_banco_pais() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('pais')) && ($this->input->post('pais') != '{id}') && ($this->input->post('pais') != 'default')) {
                $pais = $this->input->post('pais');
                $bancos = $this->select_model->banco_pais($pais);
                if ($bancos == TRUE) {
                    foreach ($bancos as $fila) {
                        echo '<option value="' . $fila->id . '">' . $fila->nombre . '</option>';
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

}
