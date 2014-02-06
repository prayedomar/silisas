<?php

if (!defined('BASEPATH'))
    exit('No Direct script acces allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
    }

    public function index() {
        $data['token'] = $this->token();
        $data['dni'] = $this->select_model->t_dni();
        $data['t_usuario'] = $this->select_model->t_usuario_login();
        $data['base_url'] = base_url();
        $data['action_validar'] = base_url() . "login/validar_user";
        $data['action_crear'] = base_url() . "login/new_user";
        //para recuperar los datos del formulario anterior.
        switch ($this->session->userdata('perfil')) {
            case '':
                //Este case se ejecuta en el caso en que no exista una variable de session llamada perfil.
                $this->parser->parse('login_view', $data);
                break;
            case 'admon_sede':
                redirect(base_url() . 'index_admon_sede');
                break;
            case 'admon_sistema':
                redirect(base_url() . 'index_admon_sistema');
                break;
            case 'analista_cartera':
                redirect(base_url() . 'index_analista_cartera');
                break;
            case 'aux_admon':
                redirect(base_url() . 'index_aux_admon');
                break;
            case 'contador':
                redirect(base_url() . 'index_contador');
                break;
            case 'directivo':
                redirect(base_url() . 'index_directivo');
                break;
            case 'docente':
                redirect(base_url() . 'index_docente');
                break;
            case 'empleado_admon':
                redirect(base_url() . 'index_empleado_admon');
                break;
            case 'empleado_rrpp':
                redirect(base_url() . 'index_empleado_rrpp');
                break;
            case 'jefe_cartera':
                redirect(base_url() . 'index_jefe_cartera');
                break;
            case 'jefe_enseñanza':
                redirect(base_url() . 'index_jefe_ensenanza');
                break;
            case 'secretaria':
                redirect(base_url() . 'index_secretaria');
                break;
            case 'titular':
                redirect(base_url() . 'index_titular');
                break;
            case 'alumno':
                redirect(base_url() . 'index_alumno');
                break;
            case 'cliente':
                redirect(base_url() . 'index_cliente');
                break;
            default:
                $this->parser->parse('login_view', $data);
                break;
        }
    }

//new_user se ejecuta cuando la vista manda los datos por post aqui.
    public function validar_user() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('token') && ($this->input->post('token') == $this->session->userdata('token'))) {
                $this->form_validation->set_rules('t_usuario', 'T.U', 'required|callback_select_default');
                $this->form_validation->set_rules('dni', 'T.I', 'required|callback_select_default');
                $this->form_validation->set_rules('id', 'Id de usuario', 'required|trim|min_length[1]|max_length[13]|xss_clean');
                $this->form_validation->set_rules('password', 'Contraseña', 'required|trim|min_length[1]|max_length[30]|xss_clean');
                //lanzamos mensajes de error si es que los hay
                if ($this->form_validation->run() == FALSE) {
                    echo form_error('t_usuario') . form_error('dni') . form_error('id') . form_error('password');
                } else {
                    $id = $this->input->post('id');
                    $dni = $this->input->post('dni');
                    $t_usuario = $this->input->post('t_usuario');
                    $password = $this->input->post('password');
                    $check_user = $this->select_model->login_user($id, $dni, $t_usuario, $password);
                    if ($check_user == TRUE) {
                        echo "OK";
                    } else {
                        echo "<p>Los datos son Incorrectos<p>";
                    }
                }
            } else {
                echo "<p>Token de seguridad incorrecto. Recargue la página.<p>";
            }
        } else {
            redirect(base_url() . 'login');
        }
    }

    public function new_user() {
        if (($this->input->post('token') && $this->input->post('token') == $this->session->userdata('token')) && ($this->input->post('submit'))) {
            $id = $this->input->post('id');
            $dni = $this->input->post('dni');
            $t_usuario = $this->input->post('t_usuario');
            $password = $this->input->post('password');
            $check_user = $this->select_model->login_user($id, $dni, $t_usuario, $password);
            if ($check_user == TRUE) {
                if (file_exists("images/photos/" . $check_user->id . $check_user->dni . ".jpg")) {
                    $rutaImg = base_url() . "images/photos/" . $check_user->id . $check_user->dni . ".jpg";
                } else {
                    if ($check_user->genero == 'M') {
                        $rutaImg = base_url() . "images/photos/default_men.png";
                    } else {
                        $rutaImg = base_url() . "images/photos/default_woman.png";
                    }
                }
                if ($check_user->genero == 'M') {
                    $msnBienvenida = 'Bienvenido ' . $check_user->nombres . '!';
                } else {
                    $msnBienvenida = 'Bienvenida ' . $check_user->nombres . '!';
                }
                if (($check_user->perfil == 'titular') || ($check_user->perfil == 'alumno') || ($check_user->perfil == 'cliente')) {
                    $textoBienvenida = "A través de éste aplicación web, usted podrá disfrutar de todas las herramientas diseñadas para que usted interactúe con nuestra empresa desde cualquier lugar del mundo."
                            . "<br><br>Así que... ¡Siéntase como en casa!";
                } else {
                    $textoBienvenida = "Esta aplicación web es uno de los muhos símbolos, que demuestran el crecimiento exponencial que SILI S.A.S ha mantenido desde sus inicios."
                            . "<br>Ha sido desarrollada con el único fin de facilitar sus funciones laborales al interior de nuestra compañía, por medio de la creación de un ambiente laboral más cómodo y eficiente para usted."
                            . "<br><br>Así que... ¡Siéntase como en casa!";
                }
                $data = array(
                    'is_logued_in' => TRUE,
                    'perfil' => $check_user->perfil,
                    'idResponsable' => $check_user->id,
                    'dniResponsable' => $check_user->dni,
                    'rutaImg' => $rutaImg,
                    'msnBienvenida' => $msnBienvenida,
                    'textoBienvenida' => $textoBienvenida
                );
                $this->session->set_userdata($data);
            }
            redirect(base_url() . 'login');
        } else {
            redirect(base_url() . 'login');
        }
    }

    public function token() {
        $token = md5(uniqid(rand(), true));
        $this->session->set_userdata('token', $token);
        return $token;
    }

    public function logout_ci() {
        $this->session->sess_destroy();
        $this->index();
        redirect(base_url('login', 'refresh'));
    }

    //callback de form_validation
    function select_default($campo) {
        if ($campo == "default") {
            $this->form_validation->set_message('select_default', 'El Campo %s, es obligatorio.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
