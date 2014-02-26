<?php

class Password extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('update_model');
    }

    function cambiar() {
        $data["tab"] = "cambiar_password";
        $this->isLogin($data["tab"]);
        $this->load->view("header", $data);
        $data['base_url'] = base_url();
        $data['id_responsable'] = $this->session->userdata('idResponsable');
        $data['dni_responsable'] = $this->session->userdata('dniResponsable');

        $data['action_validar'] = base_url() . "password/validar";
        $data['action_crear'] = base_url() . "password/editar";
        $this->parser->parse('password/cambiar', $data);
        $this->load->view('footer');
    }

    function validar() {
        if ($this->input->is_ajax_request()) {
            $this->escapar($_POST);
            $this->form_validation->set_rules('password_old', 'Contraseña actual', 'required|trim|min_length[1]|max_length[30]|xss_clean');
            $this->form_validation->set_rules('password_new_1', 'Nueva contraseña', 'required|trim|min_length[8]|max_length[30]|xss_clean');
            $this->form_validation->set_rules('password_new_2', 'Confirmar nueva contraseña', 'required|trim|min_length[8]|max_length[30]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                echo form_error('password_old') . form_error('password_new_1') . form_error('password_new_2');
            } else {
                $check_user = $this->select_model->login_user($_SESSION["idResponsable"], $_SESSION["dniResponsable"], $_SESSION["t_usuario"], $this->input->post('password_old'));
                if ($check_user != TRUE) {
                    echo "<p>Contraseña actual incorrecta.</p>";
                } else {
                    if ($this->input->post('password_new_1') != $this->input->post('password_new_2')) {
                        echo "<p>Las nuevas contraseñas no coinciden.</p>";
                    } else {
                        echo "OK";
                    }
                }
            }
        } else {
            redirect(base_url());
        }
    }

    function editar() {
        if ($this->input->post('submit')) {
            $this->escapar($_POST);
            $password = $this->encrypt->encode($this->input->post('password_new_1'));

            $data["tab"] = "cambiar_password";
            $this->isLogin($data["tab"]);
            $this->load->view("header", $data);
            $data['url_recrear'] = base_url() . "password/cambiar";
            $data['msn_recrear'] = "Cambiar de nuevo la contraseña";

            $error = $this->update_model->cambiar_contraseña($_SESSION["idResponsable"], $_SESSION["dniResponsable"], $_SESSION["t_usuario"], $password);
            if (isset($error)) {
                $data['trans_error'] = $error;
                $this->parser->parse('trans_error', $data);
            } else {
                //Enviamos email
                $email = $this->session->userdata('email');
                $genero = $this->session->userdata('genero');
                $nombres = $this->session->userdata('nombres');
                $asunto = "Notificación de cambio de contraseña";
                if ($genero == 'M') {
                    $prefijo = "Sr.";
                } else {
                    $prefijo = "Sra.";
                }
                $mensaje = '<p>' . $prefijo . ' ' . $nombres . '</p>'
                        . '<p>Le notificamos que su contraseña de ingreso al sistema, ha sido modificada.<br/>'
                        . '<center>'
                        . '<table>'
                        . '<tr>'
                        . '<td style="width:170px;"><b>Nueva contraseña: </b></td>'
                        . '<td>' . $this->input->post('password_new_1') . '</td>'
                        . '</tr>'
                        . '</table>'
                        . '</center>'
                        . '<br/><p>Para garantizar la seguridad de su cuenta, recuerde que nunca le solicitaremos datos personales a traves de correo electrónico, para que tenga cuidado y no vaya a caer en la trampa de personas malintencionadas.</p>'
                        . '<center><br/>¡Gracias por estar con nosostros!</center>';
                $this->sendEmail("silisascolombia@gmail.com", $email, $asunto, $mensaje);
                //mostramos mensaje ok
                $this->parser->parse('trans_success', $data);
            }
        } else {
            redirect(base_url());
        }
    }

}
