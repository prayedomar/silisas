<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//si no existe la función invierte_date_time la creamos
if (!function_exists('get_transferencias_pdtes')) {

    function menu_alertas() {
        //Hay que discriminar por perfil de usuario para hacer las consultas
        $perfil_usuario = $_SESSION["perfil"];
        if (($perfil_usuario != 'admon_sede') OR ($perfil_usuario != 'admon_sistema') OR ($perfil_usuario != 'aux_admon') OR ($perfil_usuario != 'cartera') OR ($perfil_usuario != 'directivo') OR ($perfil_usuario != 'secretaria')) {
            $ci = & get_instance();
            $ci->load->model('transferenciam');
            $transferencias = $ci->transferenciam->transferencia_pdte_responsable_rapida();
            $cantidad_transferencias = $ci->transferenciam->cantidad_transferencia_pdte_responsable_rapida();
            $cantidad = $cantidad_transferencias[0]->cantidad;
            if ($cantidad == 0) {
                $menu = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 18px;"><span class="glyphicon glyphicon-bell">
                         </span> Alertas <span class="label label-success">0</span></a><ul class="dropdown-menu">
                        <li><p><center><b> No tiene alertas pendientes </b></center></p></a></li></ul></li>';
            } else {
                $menu = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 18px;"><span class="glyphicon glyphicon-bell">
                         </span> Alertas <span class="label label-danger">' . $cantidad . '</span></a><ul class="dropdown-menu">';
                //obtenemos las transacciones del helper: alertas_helper
                foreach ($transferencias as $fila) {
                    $menu .= '<li><a href="' . base_url() . 'transferencia/aprobar"><span class="label label-default">' . $fila->prefijo . ' ' . $fila->id . '</span> Transferencia pendiente por aprobar</a></li>';
                }
                $menu .= '</ul></li>';
            }
            return $menu;
        } else {
            $menu = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 18px;"><span class="glyphicon glyphicon-bell">
                         </span> Alertas <span class="label label-success">0</span></a><ul class="dropdown-menu">
                        <li><p><center><b> No tiene alertas pendientes </b></center></p></a></li></ul></li>';
            return $menu;
        }
    }

}
//end application/helpers/alertas_helper.php