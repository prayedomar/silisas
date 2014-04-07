<?php

class Prueba extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('select_model');
        $this->load->model('insert_model');
        $this->load->model('update_model');
    }

    function index() {
//        $data['base_url'] = base_url();
//        $this->load->view('testV');


        list($prefijo, $id) = explode(" ", "FLST 1");
        $factura = $this->select_model->factura_prefijo_id($prefijo, $id);
        if ($factura == TRUE) {
            $this->load->library('Pdf');
            $pdf = new Pdf('P', 'mm', 'LETTER', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sili S.A.S');
            $pdf->SetTitle('Factura de Venta Sili S.A.S');
            $pdf->SetSubject('Factura de Venta Sili S.A.S');
            $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
////        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
//            $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
//            $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
//
//// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
//            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//
//// se pueden modificar en el archivo tcpdf_config.php de libraries/config
//            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//
//// se pueden modificar en el archivo tcpdf_config.php de libraries/config
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//
//// se pueden modificar en el archivo tcpdf_config.php de libraries/config
//            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//
//relación utilizada para ajustar la conversión de los píxeles
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// ---------------------------------------------------------
// establecer el modo de fuente por defecto            
            $pdf->setFontSubsetting(true);

            $pdf->setPrintHeader(false); //no imprime la cabecera ni la linea
            $pdf->setPrintFooter(false); //no imprime el pie ni la linea        
// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
            $pdf->AddPage();

            //preparamos y maquetamos el contenido a crear
            $html = '';
            $html .= '<style type=text/css>';
            $html .= 'h2{font-family: "times new roman", times, serif;font-size: 24px;font-weight: bold;font-style: italic;line-height:20px;}';
            $html .= 'p.b1{font-family: helvetica, sans-serif;font-size:9px;}';
            $html .= 'p.b2{font-family: helvetica, sans-serif;font-size:12px;font-weight: bold;line-height:0px;}';            
            $html .= 'p.b3{font-family: helvetica, sans-serif;font-size:20px;font-weight: bold;line-height:15px;}';
            $html .= 'p.b4{font-family: helvetica, sans-serif;font-size:18px;font-weight: bold;}';
            $html .= 'td.c1{width:390px;text-align:center;}';
            $html .= 'td.c2{width:310px;text-align:center;}';            
            $html .= 'table{border-collapse: collapse;}';
            $html .= 'table.t1{text-align:left;}';
            $html .= '</style>';
            $html .= '<table width="100%"><tr>'
                    . '<td class="c1"><h2>Sistema Integral Lectura Inteligente</h2><p class="b2">Régimen Común - NIT:900.064.309-1</p><p class="b2">Resolución DIAN No. 110000497290 del 16/08/2012</p>'
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
                    . '<td class="c2"><img width="150px" height="80px" src="' . base_url() . 'images/logo.png">'
                    . '<P class="b3">FACTURA DE VENTA</P>'
                    . '<table border="1" width="100%" class="t1"><tr>'
                    . '<td><b>Número:</b></td><td>FLST 23433</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td><b>Fecha de emisión:</b></td><td>2014/08/30</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td><b>Responsable:</b></td><td>Omar Rivera</td>'
                    . '</tr></table>'                    
                    . '</td>'
                    . '</tr></table>'
                    . '<br>'
                    . '<table border="1" width="697px" class="t1"><tr>'
                    . '<td><b>A nombre de:</b></td><td>FLST 23433</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td><b>Documento:</b></td><td>FLST 23433</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td><b>Número de matrícula:</b></td><td>23423</td>'                    
                    . '</tr>'
                    . '</table>'                    ;

// Imprimimos el texto con writeHTMLCell()
            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
            $nombre_archivo = utf8_decode("factura de venta.pdf");
            $pdf->Output($nombre_archivo, 'I');
        } else {
            echo "factura no encontrada";
        }
    }

    function prueba_ajax() {
        $var1 = $this->input->post('variable1');
        $var2 = $this->input->post('variable2');

        $suma = $var1 + $var2;
        $producto = $var1 * $var2;

// Array con las respuestas
        $respuesta['suma'] = $suma;
        $respuesta['producto'] = $producto;

        echo json_encode($respuesta);
    }

    function diasEntreFechas($fechaStart, $fechaEnd) {
        list($anyoStart, $mesStart, $diaStart) = explode("-", $fechaStart);
        list($anyoEnd, $mesEnd, $diaEnd) = explode("-", $fechaEnd);

        $diasStartJuliano = gregoriantojd($mesStart, $diaStart, $anyoStart);
        $diasEndJuliano = gregoriantojd($mesEnd, $diaEnd, $anyoEnd);

        return $diasEndJuliano - $diasStartJuliano;
    }

    function exceptions_error_handler($severity, $message, $filename, $lineno) {
        if (error_reporting() == 0) {
            return;
        }
        if (error_reporting() & $severity) {
            throw new ErrorException($message, 0, $severity, $filename, $lineno);
        }
    }

    function matriz_matricula($id_matricula) {
        $matricula = $this->select_model->matricula_id($id_matricula);
        $plan = $this->select_model->t_plan_id($matricula->plan);
        $fecha_inicio = date("Y-m-d", strtotime($matricula->fecha_matricula));
        $fecha_hoy = date('Y-m-d');
        $valor_total = $plan->valor_total;
        $cant_cuotas = $plan->cant_cuotas;
        $valor_inicial = $plan->valor_inicial;
        $valor_cuota = $plan->valor_cuota;
        $total_abonos = $this->select_model->total_abonos_matricula($id_matricula)->total;
        $tasa_mora_anual = $this->select_model->interes_mora()->tasa_mora_anual;

//Llenamos la primer fila
        $matriz_matricula = array();
        $matriz_matricula[0][1] = 0; //Numero de cuota
        if ($cant_cuotas == 0) { //Plan contado
            $matriz_matricula[0][2] = 3; //T_detalle: pago total 
        } else {
            $matriz_matricula[0][2] = 1; //T_detalle: pago inicial
        }
        $matriz_matricula[0][3] = $valor_inicial; //Valor esperado
//Miramos si se canceló esta cuota con el total de abonos
        if ($total_abonos >= $valor_inicial) {
            $total_abonos = $total_abonos - $valor_inicial; //Restamos del total de abonos esta cuota
            $matriz_matricula[0][4] = $valor_inicial; //Abonado
        } else {
            $matriz_matricula[0][4] = $total_abonos; //Abonado
            $total_abonos = 0; //Gastamos todo el total de abonados en este pago
        }
        $matriz_matricula[0][5] = $matriz_matricula[0][3] - $matriz_matricula[0][4]; //Valor pendiente de pago
        if ($matriz_matricula[0][5] <= 0) { //Puede que sea negativo en el caso en que halla abonado mas de lo esperado
            $matriz_matricula[0][6] = 1; //1: Cuota cancelada
        } else {
            $matriz_matricula[0][6] = 0; //0: Cuota no cancelada
        }
        $matriz_matricula[0][7] = $fecha_inicio; //Fecha esperada
        $valor_esperado = $matriz_matricula[0][3];
        $cuota_cancelada = $matriz_matricula[0][6];
        $fecha_esperada = $matriz_matricula[0][7];
        $matriz_matricula[0][8] = 0; //dias mora
        $matriz_matricula[0][9] = 0; //int mora            
        if (($cuota_cancelada == 0) && ($fecha_esperada < $fecha_hoy)) {
            $dias_mora = $this->dias_entre_fechas($fecha_esperada, $fecha_hoy);
//Descartamos una mora inferior a 4 dias de gracia.   
//Pero si es mayor a 4 la contamos completa sin descartar los 4 dias.
            if ($dias_mora > 4) {
                $matriz_matricula[0][8] = $dias_mora;
                if ($tasa_mora_anual) {
                    $Int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $valor_esperado), 2);
                    $matriz_matricula[0][9] = $Int_mora;
                }
            }
        }

//Calculamos el resto de cuotas
        for ($i = 1; $i <= $cant_cuotas; $i++) {
            $matriz_matricula[$i][1] = $i; //Numero de cuota
            $matriz_matricula[$i][2] = 2; //T_detalle: Abono total 
            $matriz_matricula[$i][3] = $valor_cuota; //valor esperado
            $valor_esperado = $matriz_matricula[$i][3];
            if ($total_abonos >= $valor_esperado) {
                $total_abonos = $total_abonos - $valor_esperado; //Restamos del total de abonos esta cuota
                $matriz_matricula[$i][4] = $valor_esperado; //Abonado
            } else {
                $matriz_matricula[$i][4] = $total_abonos; //Abonado
                $total_abonos = 0; //Gastamos todo el total de abonados en este pago
            }
            $valor_abonado = $matriz_matricula[$i][4];
            $matriz_matricula[$i][5] = $valor_esperado - $valor_abonado; //valor pendiente
            $valor_pendiente = $matriz_matricula[$i][5];
            if ($valor_pendiente <= 0) {
                $matriz_matricula[$i][6] = 1; //0: Cuota cancelada
            } else {
                $matriz_matricula[$i][6] = 0;  //0: Cuota no cancelada
            }
            $matriz_matricula[$i][7] = date("Y-m-d", strtotime("$fecha_inicio +$i month")); //Fecha esperada
            $cuota_cancelada = $matriz_matricula[$i][6];
            $fecha_esperada = $matriz_matricula[$i][7];
            $matriz_matricula[$i][8] = 0; //dias mora
            $matriz_matricula[$i][9] = 0; //int mora         
            if (($cuota_cancelada == 0) && ($fecha_esperada < $fecha_hoy)) {
                $dias_mora = $this->dias_entre_fechas($fecha_esperada, $fecha_hoy);
//Descartamos una mora inferior a 4 dias de gracia.   
//Pero si es mayor a 4 la contamos completa sin descartar los 4 dias.
                if ($dias_mora > 4) {
                    $matriz_matricula[$i][8] = $dias_mora;
                    if ($tasa_mora_anual) {
                        $Int_mora = round(((((pow((1 + ($tasa_mora_anual / 100)), (1 / 360))) - 1) * $dias_mora) * $valor_esperado), 2);
                        $matriz_matricula[$i][9] = $Int_mora;
                    }
                }
            }
        }
        return $matriz_matricula;
    }

}
