<?php

class Salario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function crear() {
        $data["tab"] = "crear_salario";
        $data['rutaImg'] = $this->session->userdata('rutaImg');
        $this->parser->parse("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');
        $data['t_salario'] = $this->select_model->t_salario();
        $data['action_validar'] = base_url() . "salario/validar";
        $data['action_crear'] = base_url() . "salario/insertar";

        $data['action_llena_t_concepto_salario'] = base_url() . "salario/llena_t_concepto_salario";

        $this->parser->parse('salario/crear', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[40]');
            $this->form_validation->set_rules('t_salario', 'Tipo de Salario', 'required|callback_select_default');

            if ($this->form_validation->run() == FALSE) {
                echo form_error('nombre') . form_error('t_salario');
            } else {
                echo "OK";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    function insertar() {
        //si se ha pulsado el botón submit validamos el formulario con codeIgniter
        //Esto es muy importante, porq de lo contrario, podrian haber accedido aqui por la url directamente y daria error porq no vienen datos.
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $nombre = ucwords(strtolower($this->input->post('nombre')));
            $t_salario = $this->input->post('t_salario');
            $observacion = ucfirst(strtolower($this->input->post('observacion')));
            $fecha_trans = date('Y-m-d') . " " . date("H:i:s");
            $id_responsable = $this->input->post('id_responsable');
            $dni_responsable = $this->input->post('dni_responsable');
            //se calcula con el ultimo id
            $id_salario = ($this->select_model->nextId_salario()->id) + 1;

            $error1 = $this->insert_model->new_salario($id_salario, $nombre, $t_salario, 1, $observacion, $fecha_trans, $id_responsable, $dni_responsable);

            $data['rutaImg'] = $this->session->userdata('rutaImg');
            $data['msnBienvenida'] = $this->session->userdata('msnBienvenida');
            $data['textoBienvenida'] = $this->session->userdata('textoBienvenida');
            $data['base_url'] = base_url();
            $this->parser->parse('header', $data);            
            
            $data['url_recrear'] = base_url() . "salario/crear";
            $data['msn_recrear'] = "Crear otro Salario";
            if (isset($error1)) {
                $data['trans_error'] = $error1;
                $this->parser->parse('trans_error', $data);
            } else {
                //los siguientes son arrays de los inputs dinamicos
                if (($this->input->post('values_conceptos')) && ($this->input->post('conceptos'))) {
                    $values_conceptos = $this->input->post('values_conceptos');
                    $conceptos = $this->input->post('conceptos');
                    $i = 0;
                    foreach ($conceptos as $fila) {
                        $error2 = $this->insert_model->new_concepto_base($id_salario, $values_conceptos[$i], round(str_replace(",", "", $fila), 2));
                        if (isset($error2)) {
                            $data['trans_error'] = $error2;
                            $this->parser->parse('trans_error', $data);
                            $this->parser->parse('welcome', $data);
                            $this->load->view('footer');
                            return;
                        }
                        $i++;
                    }
                }
                $this->parser->parse('trans_success', $data);
            }
            $this->parser->parse('welcome', $data);
            $this->load->view('footer');
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

    public function llena_t_concepto_salario() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('t_salario')) {
                $t_salario = $this->input->post('t_salario');
                $conceptos = $this->select_model->t_concepto_nomina_base($t_salario);
                if ($conceptos == TRUE) {
                    foreach ($conceptos as $fila) {
                        echo '<div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="margin_label">' . $fila->tipo . '</label>   
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input name="values_conceptos[]" type="hidden" value="' . $fila->id . '">
                                        <input type="text" name="conceptos[]" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        } else {
            redirect(base_url() . 'index_admon_sistema');
        }
    }

//A continuación: Metodos para consultar
    
}
