<?php

class Proveedor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_proveedor";
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['dni'] = $this->select_model->t_dni_proveedor();
        $data['pais'] = $this->select_model->pais();
        $data['provincia'] = $this->select_model->provincia();
        $data['ciudad'] = $this->select_model->ciudad();
        $data['t_domicilio'] = $this->select_model->t_domicilio();
        $data['action_validar'] = base_url() . "proveedor/validar";
        $data['action_crear'] = base_url() . "proveedor/insertar";
        $data['action_llena_provincia'] = base_url() . "proveedor/llena_provincia";
        $data['action_llena_ciudad'] = base_url() . "proveedor/llena_ciudad";
        $this->parser->parse('proveedor/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('dni', 'Tipo de Identificación', 'required|callback_select_default');
            $this->form_validation->set_rules('id', 'Número de Identificación', 'required|trim|min_length[5]|max_length[13]|integer|callback_valor_positivo');
            $this->form_validation->set_rules('razon_social', 'Razón Social', 'required|trim|xss_clean|max_length[100]');
            $this->form_validation->set_rules('pais', 'País', 'required|callback_select_default');
            $this->form_validation->set_rules('direccion', 'Direccion', 'trim|xss_clean|max_length[80]');
            $this->form_validation->set_rules('telefono', 'Telefono', 'trim|xss_clean|min_length[7]|max_length[40]');
            $this->form_validation->set_rules('observacion', 'Observación', 'trim|xss_clean|max_length[255]');

            //Validamos que la clave primaria no este repetida
            $duplicate_key = "";
            if (($this->input->post('id')) && ($this->input->post('dni'))) {
                $check_usuario = $this->select_model->proveedor_id_dni($this->input->post('id'), $this->input->post('dni'), $this->input->post('d_v'));
                if ($check_usuario == TRUE) {
                    $duplicate_key = "<p>La Identificación ingresada ya existe en la Base de Datos.</p>";
                }
            }
            if ($this->form_validation->run() == FALSE) {
                echo form_error('dni') . form_error('id') . $duplicate_key . form_error('razon_social') . form_error('pais') . form_error('direccion') . form_error('telefono') . form_error('observacion');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url());
        }
    }

    function insertar() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $id = $this->input->post('id');
            $dni = $this->input->post('dni');
            $d_v = $this->input->post('d_v');
            if ($dni != "6") {
                $d_v = NULL;
            }
            $razon_social = strtoupper($this->input->post('razon_social'));
            $pais = $this->input->post('pais');
            $provincia = $this->input->post('provincia');
            if ($provincia == "default") {
                $provincia = NULL;
            }
            $ciudad = $this->input->post('ciudad');
            if ($ciudad == "default") {
                $ciudad = NULL;
            }
            $t_domicilio = $this->input->post('t_domicilio');
            if ($t_domicilio == "default") {
                $t_domicilio = NULL;
            }
            $direccion = ucwords(strtolower($this->input->post('direccion')));
            $telefono = strtolower($this->input->post('telefono'));
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');

            $error = $this->insert_model->new_proveedor($id, $dni, $d_v, $razon_social, $pais, $provincia, $ciudad, $t_domicilio, $direccion, $telefono, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data["tab"] = "crear_proveedor";
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "proveedor/crear";
            $data['msn_recrear'] = "Crear otro Proveedor";
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

    public function llena_provincia() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('pais')) && ($this->input->post('pais') != '{id}') && ($this->input->post('pais') != 'default')) {
                $pais = $this->input->post('pais');
                $provincias = $this->select_model->provincia_pais($pais);
                if ($provincias == TRUE) {
                    foreach ($provincias as $fila) {
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

    public function llena_ciudad() {
        if ($this->input->is_ajax_request()) {
            if (($this->input->post('provincia')) && ($this->input->post('provincia') != '{id}') && ($this->input->post('provincia') != 'default')) {
                $provincia = $this->input->post('provincia');
                $ciudades = $this->select_model->ciudad_provincia($provincia);
                if ($ciudades == TRUE) {
                    foreach ($ciudades as $fila) {
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