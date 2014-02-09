<?php

class Transacciones extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaccionesm');
    }

    public function consultar() {
        $this->load->model('t_dnim');
        $this->load->model('t_cajam');
        $this->load->model('sedem');
        $data["tab"] = "transacciones";
        $data['tipos_documentos'] = $this->t_dnim->listar_todas_los_tipos_de_documentos();
        $data['lista_sedes'] = $this->sedem->listar_todas_las_sedes();
        if (!empty($_GET["depto"])) {
            $this->load->model('t_cargom');
            $data['lista_cargos'] = $this->t_cargom->listar_todas_los_cargos_por_depto($_GET['depto']);
        }
        $filasPorPagina = 20;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $cantidad = $this->transaccionesm->cantidad_transacciones($_GET, $inicio, $filasPorPagina);
        $cantidad = $cantidad[0]->cantidad;
        $data['cantidad'] = $cantidad;
        $data['lista_sedes'] = $this->sedem->listar_todas_las_sedes();
        $data["listar_cajas"]=$this->t_cajam->listar_tipos_de_caja();
        $data['cantidad_paginas'] = ceil($cantidad / $filasPorPagina);
        $data["lista"] = $this->transaccionesm->listar_transacciones($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("transacciones/consultar");
        $this->load->view("footer");
    }

}
