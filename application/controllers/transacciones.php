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
        $this->load->model('t_transm');
        $data["tab"] = "transacciones";
        $data['tipos_documentos'] = $this->t_dnim->listar_todas_los_tipos_de_documentos();
        $data['lista_sedes'] = $this->sedem->listar_todas_las_sedes();
        $data['lista_trans'] = $this->t_transm->listar_tipos_de_transacciones();
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
        $cantidad = sizeof($cantidad);
        $data['cantidad'] = $cantidad;
        $data['totales'] = $this->transaccionesm->total_transacciones($_GET, $inicio, $filasPorPagina);
        $data['lista_sedes'] = $this->sedem->listar_todas_las_sedes();
        $data["listar_cajas"] = $this->t_cajam->listar_tipos_de_caja();
        $data['cantidad_paginas'] = ceil($cantidad / $filasPorPagina);
        $data["lista"] = $this->transaccionesm->listar_transacciones($_GET, $inicio, $filasPorPagina);
        $this->load->view("header", $data);
        $this->load->view("transacciones/consultar");
        $this->load->view("footer");
    }

    public function excel() {
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=reporte_transacciones_" . date("Y-m-d") . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
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
        $data["lista"] = $this->transaccionesm->listar_transacciones_excel($_GET);
        ?>
       <table border="1" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th><?= utf8_decode("Tipo de transacción") ?></th>
                    <th>Id</th>
                    <th>Total</th>
                    <th>Caja</th>
                    <th>Efectivo de caja</th>
                    <th>Cuenta</th>
                    <th>Valor de cuenta</th>
                    <th>Vigente</th>
                    <th>Sede</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody id="bodyTabla">
                <?php foreach ($data["lista"] as $row) { ?>
                    <tr>
                        <td><?= $row->fecha_trans ?></td>
                        <td><?= utf8_decode($row->tipo_trans) ?></td>
                        <td><?= $row->prefijo . " " . $row->id ?></td>
                        <td><?= "$" . number_format($row->total, 2, '.', ',') ?></td>
                        <td><?= ($row->caja != "") ? utf8_decode($row->caja) : "--" ?></td>
                        <td><?= ($row->efectivo_caja != "") ? "$" . number_format($row->efectivo_caja, 2, '.', ',') : "--" ?></td>
                        <td><?= ($row->cuenta != "") ? utf8_decode($row->cuenta) : "--" ?></td>
                        <td><?= ($row->valor_cuenta != "") ? "$" . number_format($row->valor_cuenta, 2, '.', ',') : "--" ?></td>
                        <td><?= $row->vigente == 1 || $row->vigente == 2 ? "Vigente" : "No vigente" ?></td>
                        <td><?= utf8_decode($row->sede) ?></td>
                        <td><?= utf8_decode($row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
    }

}
