<?php

class Sede extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('sedem');
        $this->load->model('paism');
        $this->load->model('provinciam');
        $this->load->model('ciudadm');
    }

    function consultar() {
        $this->load->model('est_sedem');
        $data["tab"] = "consultar_sede";
        $data["paises"] = $this->paism->listar_paises();
        if (!empty($_GET["pais"])) {
            $data["departamentos"] = $this->provinciam->listarProvinciasPorPais($_GET['pais']);
        }
        if (!empty($_GET["departamento"])) {
            $data["ciudades"] = $this->ciudadm->listarCiudadesPorProvicia($_GET['departamento']);
        }
        $data["estados"] = $this->est_sedem->listar_estatus();

        $filasPorPagina = 4;
        if (empty($_GET["page"])) {
            $inicio = 0;
            $paginaActual = 1;
        } else {
            $inicio = ($_GET["page"] - 1) * $filasPorPagina;
            $paginaActual = $_GET["page"];
        }
        $data['paginaActiva'] = $paginaActual;
        $data['cantidadPaginas'] = "5";
        $cantidadSedes = $this->sedem->cantidadSedes($_GET, $inicio, $filasPorPagina);
        $cantidadSedes = $cantidadSedes[0]->cantidad;
        $data['cantidadSedes'] = $cantidadSedes;
        $data['cantidadPaginas'] = ceil($cantidadSedes / $filasPorPagina);
        $data["lista_sedes"] = $this->sedem->listar_sedes($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("sede/consultar");
        $this->load->view("footer");
    }

    function listar_departamentos() {

        $this->escapar($_GET);
        echo json_encode($this->provinciam->listarProvinciasPorPais($_GET['idPais']));
    }

    function listar_ciudades() {

        $this->escapar($_GET);
        echo json_encode($this->ciudadm->listarCiudadesPorProvicia($_GET['idDepartamento']));
    }

}
