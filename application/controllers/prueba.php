<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
        $contrato_laboral = $this->select_model->contrato_laboral_empleado('1128478351', $$nomina->dni_empleado);
        echo 'ok';
    }

    function detalle_transacciones_matricula() {
        $this->load->model('facturam');
        $this->load->model('select_model');
        $facturas = $this->facturam->todas_las_facturas();
        foreach ($facturas as $fila) {
            $matricula = $this->select_model->matricula_id($fila->matricula);
            $titular = $this->select_model->titular($matricula->id_titular, $matricula->dni_titular);
            $nombre_titular = $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . " " . $titular->apellido2;
            $detalle_array = array(
                "Matrícula" => $fila->matricula,
                "Titular" => $nombre_titular,
                "Identificación" => $matricula->id_titular,
                "Observación" => $fila->observacion
            );
            $detalle_json = json_encode($detalle_array);
            $data = array(
                'detalle_json' => $detalle_json
            );
            $this->db->where('t_trans', '7');
            $this->db->where('prefijo', $fila->prefijo);
            $this->db->where('id', $fila->id);
            $this->db->where('credito_debito', '1');
            $this->db->update('movimiento_transaccion', $data);
            if ($error = $this->db->_error_message()) {
                var_dump($error);
            }
        }
        echo 'ok';
    }

    function detalle_transacciones_recibo_caja() {
        $this->load->model('recibo_cajam');
        $this->load->model('select_model');
        $facturas = $this->recibo_cajam->todos_los_recibos();
        foreach ($facturas as $fila) {
            $matricula = $this->select_model->matricula_id($fila->matricula);
            $titular = $this->select_model->titular($matricula->id_titular, $matricula->dni_titular);
            $nombre_titular = $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . " " . $titular->apellido2;
            $detalle_array = array(
                "Matrícula" => $fila->matricula,
                "Titular" => $nombre_titular,
                "Identificación" => $matricula->id_titular,
                "Observación" => $fila->observacion
            );
            $detalle_json = json_encode($detalle_array);
            $data = array(
                'detalle_json' => $detalle_json
            );
            $this->db->where('t_trans', '8');
            $this->db->where('prefijo', $fila->prefijo);
            $this->db->where('id', $fila->id);
            $this->db->where('credito_debito', '1');
            $this->db->update('movimiento_transaccion', $data);
            if ($error = $this->db->_error_message()) {
                var_dump($error);
            }
        }
        echo 'ok';
    }

    function detalle_transacciones_abono_matricula() {
        $this->load->model('abono_matriculam');
        $this->load->model('select_model');
        $facturas = $this->abono_matriculam->todos_los_abonos();
        foreach ($facturas as $fila) {
            $matricula = $this->select_model->matricula_id($fila->matricula);
            $titular = $this->select_model->titular($matricula->id_titular, $matricula->dni_titular);
            $nombre_titular = $titular->nombre1 . " " . $titular->nombre2 . " " . $titular->apellido1 . " " . $titular->apellido2;
            $detalle_array = array(
                "Matrícula" => $fila->matricula,
                "Titular" => $nombre_titular,
                "Identificación" => $matricula->id_titular,
                "Observación" => $fila->observacion
            );
            $detalle_json = json_encode($detalle_array);
            $data = array(
                'detalle_json' => $detalle_json
            );
            $this->db->where('t_trans', '15');
            $this->db->where('prefijo', $fila->prefijo);
            $this->db->where('id', $fila->id);
            $this->db->where('credito_debito', '1');
            $this->db->update('movimiento_transaccion', $data);
            if ($error = $this->db->_error_message()) {
                var_dump($error);
            }
        }
        echo 'ok';
    }

    function detalle_transacciones_nomina() {
        $this->load->model('nominam');
        $this->load->model('select_model');
        $facturas = $this->nominam->todas_las_nominas();
        foreach ($facturas as $fila) {
            $empleado = $this->select_model->empleado($fila->id_empleado, $fila->dni_empleado);
            $this->load->model('t_deptom');
            $this->load->model('t_cargom');
            $nombre_empleado = $empleado->nombre1 . " " . $empleado->nombre2 . " " . $empleado->apellido1;
            $nombre_depto = $this->t_deptom->t_depto_id($fila->depto)->tipo;
            if ($empleado->genero == "M") {
                $nombre_cargo = $this->t_cargom->t_cargo_id($fila->cargo)->cargo_masculino;
            } else {
                $nombre_cargo = $this->t_cargom->t_cargo_id($fila->cargo)->cargo_femenino;
            }
            $detalle_array = array(
                "Empleado" => $nombre_empleado,
                "Identificación" => $fila->id_empleado,
                "Departamento" => $nombre_depto,
                "Cargo" => $nombre_cargo,
                "Devengado" => "$" . number_format($fila->total_devengado, '2', '.', ','),
                "Deducido" => "$" . number_format($fila->total_deducido, '2', '.', ','),
                "Observación" => $fila->observacion
            );
            $detalle_json = json_encode($detalle_array);
            ;
            $data = array(
                'detalle_json' => $detalle_json
            );
            $this->db->where('t_trans', '9');
            $this->db->where('prefijo', $fila->prefijo);
            $this->db->where('id', $fila->id);
            $this->db->where('credito_debito', '0');
            $this->db->update('movimiento_transaccion', $data);
            if ($error = $this->db->_error_message()) {
                var_dump($error);
            }
        }
        echo 'ok';
    }

    function detalle_transacciones_transferencia() {
        $this->load->model('transferenciam');
        $this->load->model('select_model');
        $facturas = $this->transferenciam->todas_las_aprobaciones();
        foreach ($facturas as $fila) {
            $transferencia = $this->transferenciam->transferencia_prefijo_id($fila->prefijo_transferencia, $fila->id_transferencia);
            $empleado_autoriza = $this->select_model->empleado($fila->id_responsable, $fila->dni_responsable);
            $detalle_array = array(
                "Sede_Origen" => $transferencia->nombre_sede_origen,
                "Remitente" => $transferencia->nombre_remitente
            );
            if ($transferencia->sede_caja_origen != NULL) {
                $detalle_array['Caja_de_origen'] = $transferencia->nombre_caja_origen;
                $detalle_array['Efectivo_retirado'] = '$' . number_format($transferencia->efectivo_retirado, 2, '.', ',');
            }
            if ($transferencia->cuenta_origen != NULL) {
                $detalle_array['Cuenta_de_origen'] = $transferencia->cuenta_origen;
                $detalle_array['Valor_retirado'] = '$' . number_format($transferencia->valor_retirado, 2, '.', ',');
            }
            $detalle_array['Fecha_envío'] = $transferencia->fecha_trans;
            $detalle_array['Observacion_transferencia'] = $transferencia->observacion;
            $detalle_array['Sede_Destino'] = $transferencia->nombre_sede_destino;
            $detalle_array['Aprueba'] = $empleado_autoriza->nombre1 . " " . $empleado_autoriza->nombre2 . " " . $empleado_autoriza->apellido1;
            if ($transferencia->tipo_destino == 1) {
                $detalle_array['Caja_destino'] = $transferencia->nombre_caja_destino;
                $detalle_array['Efectivo_ingresado'] = '$' . number_format($transferencia->efectivo_ingresado, 2, '.', ',');
            } else {
                $detalle_array['Cuenta_de_origen'] = $transferencia->cuenta_destino;
                $detalle_array['Valor_consignado'] = '$' . number_format($transferencia->valor_consignado, 2, '.', ',');
            }
            $detalle_array['Observacion_aprobación'] = $fila->observacion;
            $detalle_json = json_encode($detalle_array);
            ;
            $data = array(
                'detalle_json' => $detalle_json
            );
            $this->db->where('t_trans', '13');
            $this->db->where('prefijo', $fila->prefijo_transferencia);
            $this->db->where('id', $fila->id_transferencia);
            $this->db->where('credito_debito', '1');
            $this->db->update('movimiento_transaccion', $data);
            if ($error = $this->db->_error_message()) {
                var_dump($error);
            }
            $this->db->where('t_trans', '13');
            $this->db->where('prefijo', $fila->prefijo_transferencia);
            $this->db->where('id', $fila->id_transferencia);
            $this->db->where('credito_debito', '0');
            $this->db->update('movimiento_transaccion', $data);
            if ($error = $this->db->_error_message()) {
                var_dump($error);
            }
        }
        echo 'ok';
    }

    function historico_nominas() {
        $this->load->model('nominam');
        $this->load->model('select_model');
        $nominas_vigentes = $this->nominam->nominas_vigentes();

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'Letter', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sili S.A.S');
        $pdf->SetTitle('Nómina Sili S.A.S');
        $pdf->SetSubject('Nómina Sili S.A.S');
        $pdf->SetKeywords('sili, sili sas');
//// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// ---------------------------------------------------------
// establecer el modo de fuente por defecto            
        $pdf->setFontSubsetting(true);
        $pdf->setPrintHeader(false); //no imprime la cabecera ni la linea
        $pdf->setPrintFooter(false); //no imprime el pie ni la linea        
// Añadir una página        
        foreach ($nominas_vigentes as $row) {
            $id_nomina = $row->prefijo . '_' . $row->id;
            $salida_pdf = 'I';
            $nomina_prefijo_id = $id_nomina;
            $id_nomina_limpio = str_replace("_", " ", $nomina_prefijo_id);
            list($prefijo, $id) = explode("_", $nomina_prefijo_id);
            $nomina = $this->nominam->nomina_prefijo_id($prefijo, $id);
            $conceptos_nomina = $this->nominam->concepto_nomina_group_matricula($prefijo, $id);
            $dni_abreviado_empleado = $this->select_model->t_dni_id($nomina->dni_empleado)->abreviacion;
            if ($nomina->genero_empleado == "M") {
                $empleado_a = "o";
                $cargo = $nomina->cargo_masculino;
            } else {
                $empleado_a = "a";
                $cargo = $nomina->cargo_femenino;
            }
            $contrato_laboral = $this->select_model->contrato_laboral_empleado($nomina->id_empleado, $nomina->dni_empleado);
            if ($contrato_laboral->t_contrato == '4') {
                $titulo = "PAGO POR PRESTACIÓN DE SERVICIOS";
            } else {
                $titulo = "NÓMINA LABORAL";
            }

// Este método tiene varias opciones, consulta la documentación para más información.
            $pdf->AddPage();

            //preparamos y maquetamos el contenido a crear
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size:22px;font-weight: bold;font-style: italic;color:#0255bc}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:10px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:0px;text-align:center;}';
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:5px;text-align:center;}';
            $html .= 'td.c1{width:420px;line-height:20px;}td.c1000{line-height:100px;}';
            $html .= 'td.c2{width:310px;}';
            $html .= 'td.c3{width:100px;}';
            $html .= 'td.c11{width:150px;}';
            $html .= 'td.c4{width:265px;}';
            $html .= 'td.c5{width:160px;}';
            $html .= 'td.c6{width:150px;}';
            $html .= 'td.c9{width:115px;}';
            $html .= 'td.c10{font-size:4px;line-height:5px;}';
            $html .= 'td.c20{width:240px;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c21{width:140px;height:33px;line-height:32px;font-weight: bold;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c22{width:110px;height:33px;line-height:32px;font-weight: bold;font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.c23{font-family:helvetica,sans-serif;font-size:13px;line-height:25px;}';
            $html .= 'td.c24{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;height:30px;line-height:25px;}';
            $html .= 'td.c25{border-top-color:#000000;}';
            $html .= 'td.c26{border-bottom-color:#000000;}';
            $html .= 'td.c27{border-left-color:#000000;}';
            $html .= 'td.c28{border-right-color:#000000;}';
            $html .= 'td.c29{background-color:#F5F5F5;}';
            $html .= 'td.c30{font-family:helvetica,sans-serif;font-size:13px;}';
            $html .= 'td.a1{text-align:left;}';
            $html .= 'td.a2{text-align:center;}';
            $html .= 'td.a3{text-align:justify;}';
            $html .= 'th.a1{text-align:left;}';
            $html .= 'th.a2{text-align:center;}';
            $html .= 'th.a3{background-color:#F5F5F5;}';
            $html .= 'th.d1{width:310px;}';
            $html .= 'th.d2{width:80px;}';
            $html .= 'th.d3{width:120px;}';
            $html .= 'th.d4{width:110px;}';
            $html .= 'th.d5{width:110px;}';
            $html .= 'th.d6{height:30px;line-height:25px;}';
            $html .= 'th.d7{border-top-color:#000000;border-bottom-color:#000000;border-left-color:#000000;border-right-color:#000000;}';
            $html .= 'table{border-spacing: 0;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1 a2" rowspan="5" colspan="2"><h2>Sistema Integral Lectura Inteligente S.A.S</h2><p class="b2">Régimen Común - NIT: 900.064.309-1</p>'
                    . '<p class="b1">Medellín: Calle 47D # 77 AA - 67  (Floresta)  / Tels.: 4114107 – 4126800<br>'
                    . 'Medellín: Carrera 48B # 10 SUR - 118 (Poblado) / Tels.: 3128614 – 3126060<br>'
                    . 'Cali Sur: Carrera 44 # 5A – 26 (Tequendama) / Tels.: 3818008 – 3926723<br>'
                    . 'Cali Norte: Calle 25 # Norte 6A – 32 (Santa Mónica) / Tels.: 3816803 – 3816734<br>'
                    . 'Bucaramanga: Carrera 33 # 54 – 91 (Cabecera) / Tels.: 6832612 – 6174057<br>'
                    . 'Montería: Calle 58 # 6 – 39 (Castellana) / Tels.:7957110 – 7957110<br>'
                    . 'Montelíbano: Calle 17 # 13 2do piso / Tels.: 7625202 – 7625650<br>'
                    . 'Santa Marta: Carrera 13 B # 27 B – 84  (B. Bavaria) / Tels.: 4307566 – 4307570<br>'
                    . 'El Bagre: Calle 1 # 32 (Cornaliza) / Tels.: 8372645 – 8372653<br>'
                    . 'Caucasia: Carrera 8A # 22 – 48. 2do Piso (B. Kennedy) / Tels.: 8391693 - 8393582</p>'
                    . '</td>'
                    . '<td class="c2 a2" colspan="2"><img src="' . base_url() . 'images/logo.png" class="img-responsive"  width="180" height="100"/></td>'
                    . '<br>'
                    . '</tr><tr>'
                    . '<td class="a2 c24" colspan="2">' . $titulo . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Número:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . $id_nomina_limpio . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Fecha de emisión:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . date("Y-m-d", strtotime($nomina->fecha_trans)) . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c23 c25 c26 c27 c28 c12 c5"><b>Responsable empresa:</b></td><td class="c23 c25 c26 c27 c28 c12 c6">' . $nomina->responsable . '</td>'
                    . '</tr></table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Periodicidad:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">' . $nomina->tipo_periodicidad . '</td>'
                    . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Periodo:</b></td><td class="c4 c23 c12 c25 c26 c27 c28">Del ' . $nomina->fecha_inicio . ' al ' . $nomina->fecha_fin . '</td>'
                    . '</tr></table>';
            if ($contrato_laboral->t_contrato != '4') {
                $html .= '<table><tr>'
                        . '<td class="c3 c23 c12 c25 c26 c27 c28"><b>Días nómina:</b></td><td class="c9 c23 c12 c25 c26 c27 c28">' . $nomina->dias_nomina . '</td>'
                        . '<td class="c11 c23 c12 c25 c26 c27 c28"><b>Días remunerados:</b></td><td class="c3 c23 c12 c25 c26 c27 c28">' . $nomina->dias_remunerados . '</td>'
                        . '<td class="c11 c23 c12 c25 c26 c27 c28"><b>Ausencias:</b></td><td class="c9 c23 c12 c25 c26 c27 c28">' . $nomina->ausencias . '</td>'
                        . '</tr></table>';
            }
            $html .= '<table><tr>'
                    . '<table><tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Emplead' . $empleado_a . ':</b></td><td class="c4 c23 c25 c26 c27 c28">' . $nomina->empleado . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Documento:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $dni_abreviado_empleado . ' ' . $nomina->id_empleado . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Departamento:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $nomina->departamento . '</td>'
                    . '<td class="c3 c23 c25 c26 c27 c28"><b>Cargo:</b></td><td class="c4 c23 c25 c26 c27 c28">' . $cargo . '</td>'
                    . '</tr>'
                    . '</table><br><br>'
                    . '<table>'
                    . '<tr>'
                    . '<th class="d1 c23 d6 a2 d7 a3"><b>Concepto</b></th>'
                    . '<th class="d2 c23 d6 a2 d7 a3"><b>Cantidad</b></th>'
                    . '<th class="d3 c23 d6 a2 d7 a3"><b>Valor unitario</b></th>'
                    . '<th class="d4 c23 d6 a2 d7 a3"><b>Devengado</b></th>'
                    . '<th class="d5 c23 d6 a2 d7 a3"><b>Deducido</b></th>'
                    . '</tr>';
            $cont_filas = 0;
            foreach ($conceptos_nomina as $fila) {
                if ($fila->debito_credito == 1) {
                    $devengado = $fila->cantidad * $fila->total;
                    $deducido = "0.00";
                } else {
                    $devengado = "0.00";
                    $deducido = $fila->cantidad * $fila->total;
                }
                if ($fila->detalle) {
                    $detalle = " - " . $fila->detalle;
                } else {
                    $detalle = "";
                }
                $cont_filas ++;
                $html .= '<tr>'
                        . '<td class="d1 c30 c27 c28">' . $fila->tipo . $detalle . '</td>'
                        . '<td class="d2 a2 c30 c27 c28">' . $fila->cantidad . '</td>'
                        . '<td class="d3 a2 c30 c27 c28">$' . number_format($fila->total, 1, '.', ',') . '</td>'
                        . '<td class="d4 a2 c30 c27 c28">$' . number_format($devengado, 1, '.', ',') . '</td>'
                        . '<td class="d5 a2 c30 c27 c28">$' . number_format($deducido, 1, '.', ',') . '</td>'
                        . '</tr>';
            }
            for ($i = $cont_filas; $i < 30; $i++) {
                $html .= '<tr><td class="d1 c27 c28 c30"></td><td class="d2 c27 c28 c30"></td><td class="d3 c27 c28 c30"></td><td class="d4 c27 c28 c30"></td><td class="d5 c27 c28 c30"></td></tr>';
            }
            $html .= '</table><table>';
            if ($nomina->observacion != "") {
                $html .= '<tr><td class="c10 c25 c27 c28" colspan="4"> </td></tr><tr><td class="a3 c30 c27 c28" colspan="4"><b>Observaciones: </b>' . $nomina->observacion . '.</td></tr>'
                        . '<tr><td class="c10 c26 c27 c28" colspan="4"> </td></tr>';
            }
            $html .= '<tr>'
                    . '<td class="c20 a2 c25 c26 c27 c28" rowspan="3"><br><br><br><br><br>___________________________<br>Firma empleado</td>'
                    . '<td class="c20 a2 c25 c26 c27 c28" rowspan="3"><br><br><br><br><br>___________________________<br>Firma y sello empresa</td>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total devengados (+)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total_devengado, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total deducidos (-)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total_deducido, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td class="c21 c29 c25 c26 c27 c28">Total percibido (=)</td>'
                    . '<td class="c22 a2 c29 c25 c26 c27 c28">$' . number_format($nomina->total, 1, '.', ',') . '</td>'
                    . '</tr>'
                    . '</table><p class="b3">- Copia para la empresa -</p>';
            // Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            // reset pointer to the last page
            $pdf->lastPage();
        }
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode('Nómina ' . $id_nomina_limpio . ' Sili S.A.S.pdf');
        $pdf->Output($nombre_archivo, $salida_pdf);
    }

}
