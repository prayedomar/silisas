<?php

class Upload extends CI_Controller {

    function __construct() {
        parent::__construct();
        //cargamos el helper form y url a la vez
        $this->load->helper(array('form', 'url'));
    }

    function index() {
        //cargamos nuestra vista del formulario
        $this->load->view('formulario_upload', array('mensaje' => ''));
    }

    function do_upload() {
        //especificamos a donde se va almacenar nuestra imagen
        //La ruta se coloca en base a la ruta relativa base_url o de forma .imgaes/tal_cosa
        $config['upload_path'] = 'images/photo_orig/';
        //indicamos que tipo de archivos están permitidos
        $config['allowed_types'] = 'gif|jpg|png';
        //indicamos el tamaño maximo permitido en este caso 1M
        $config['max_size'] = '5120';
//        //le indicamos el ancho maximo permitido
//        $config['max_width'] = '1024';
//        //le indicamos el alto maximo permitodo
//        $config['max_height'] = '768';
        $config['file_name'] = $_SESSION['idResponsable'] . $_SESSION['dniResponsable'] . '.jpg';
        $config['overwrite'] = TRUE;
        //cargamos nuestra libreria con nuestra configuracion
        $this->load->library('upload', $config);
        //verificamos si existe errores
        if (!$this->upload->do_upload()) {
            //almacenamos el error que existe
            $mensaje = array('mensaje' => $this->upload->display_errors());
            $this->load->view('formulario_upload', $mensaje);
        } else {
            $data = array('upload_data' => $this->upload->data());
            $img_full_path = $config['upload_path'] . $data['upload_data']['file_name'];
            echo $img_full_path . '<br>';
            // REDIMENSIONAMOS
            $config['image_library'] = 'gd2';
            $config['source_image'] = $img_full_path;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 160;
            $config['height'] = 160;
            $config['overwrite'] = TRUE;
            $config['new_image'] = 'images/photo_perfil/' . $data['upload_data']['file_name'];
            $img_redim1 = $config['new_image'];
            $this->load->library('image_lib', $config);
            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
                $mensaje = array('mensaje' => $this->image_lib->display_errors());
                $this->load->view('formulario_upload', $mensaje);
                exit();
            } else {
                //@unlink borra un archivo con php
//                @unlink($img_full_path);
                //si no hace la subida
                $mensaje = array('mensaje' => 'La subida se dió correctamente');
                $this->load->view('formulario_upload', $mensaje);
            }
        }
    }

}
