<!DOCTYPE html>
<html lang="es">
    <head>
        <title>SILI S.A.S - Inicio</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=9"/>
        <meta name="description" content="Inicio Directivo"/>
        <meta name="author" content="Omar Stevenson Rivera Correa"/>
        <!-- Favicon -->        
        <link rel="shortcut icon" href="<?= base_url() ?>images/favicon2.ico"/>
        <!-- CSS -->
        <link  rel="stylesheet" href="<?= base_url() ?>libraries/bootstrap_3.0.2/css_cerulean/bootstrap.min.css"/>
        <link rel="stylesheet" href="<?= base_url() ?>/css/bootstrapMio.css"/>
        <link rel="stylesheet" href="<?= base_url() ?>libraries/jqueryUI/jquery-ui-1.10.4.custom.min.css"/>
        <link rel="stylesheet" href="<?= base_url() ?>libraries/select_chosen/chosen.css"/>
        <link rel="stylesheet" href="<?= base_url() ?>public/css/global.css"/>
        <?php if (isset($tab) && $tab == "consultar_sede") { ?>
            <link href='<?= base_url() ?>public/css/consultar_sede.css' rel='stylesheet'>   
        <?php } ?>
        <!-- Js -->
        <script src="<?= base_url() ?>libraries/html5shim/html5.js"></script>
        <script src="<?= base_url() ?>libraries/respond/respond.min.js"></script>
        <script src="<?= base_url() ?>libraries/jquery/jquery1.7.2.min.js"></script>
        <script src="<?= base_url() ?>libraries/bootstrap_3.0.2/js/bootstrap.min.js"></script>
        <script src="<?= base_url() ?>libraries/jqueryUI/jquery-ui-1.10.4.custom.min.js"></script>
        <script src="<?= base_url() ?>libraries/select_chosen/chosen.jquery.js"></script>


        <!--llenar ciudades y departamentos-->
        <script type="text/javascript">
            $(document).ready(function() {
                //Deshabilitamos la tecla esc en todo el programa, porque con esta pueden cancelar el envio de una peticion post
                //de cualquier formulario y no tiene ninguna productividad dentro de la plataforma.
                $(document).keydown(function(e) {
                    if (e.keyCode == 27)
                        return false;
                });

                //Enviar formulario por ajax
                $('#botonValidar').live('click', function() {
                    //PAra desactivar el click al lado del modal para cerrarlo
                    $(function() {
                        $('#modal_loading').modal({
                            show: true,
                            keyboard: false,
                            backdrop: 'static'
                        });
                    });
                    $.ajax({
                        type: "POST",
                        url: $("#action_validar").attr("value"),
                        cache: false,
                        data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
                        success: function(data)
                        {
                            if (data != "OK") {
                                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                                $("#div_alert").html(data);
                                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                                //Para cerrar el modal de loading
                                $('#modal_loading').modal('hide');
                            } else {
                                $(window).unbind('beforeunload');
                                $("#btn_submit").click();
                            }
                        },
                        error: function(data) {
                            $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                            $('#div_alert').html('<p>Hubo un error en la peticion al servidor</p>');
                            $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

                        }
                    });
                    return false; // Evitar ejecutar el submit del formulario
                });

                //Para impedir cerrar el formulario si hubo cambios sin guardar
                $(".exit_caution").live("change", function() {
                    $(window).bind('beforeunload', function() {
                        return 'Guarden los datos del formulario antes de salir, de lo contrario se perderán los datos.';
                    });
                });

                //Para validar entrada de datos restringida a inputs
                (function(a) {
                    a.fn.validar_inputs = function(b) {
                        a(this).live({keypress: function(a) {
                                var c = a.which, d = a.keyCode, e = String.fromCharCode(c).toLowerCase(), f = b;
                                (-1 != f.indexOf(e) || 9 == d || 37 != c && 37 == d || 39 == d && 39 != c || 8 == d || 46 == d && 46 != c) && 161 != c || a.preventDefault()
                            }})
                    }
                })(jQuery);

                //Para escribir solo letras, numeros, alfanumericos
                $('.alfabeto').validar_inputs('abcdefghijklmnñopqrstuvwxyzáéíóú');
                $('.alfabeto_espacios').validar_inputs(' abcdefghijklmnñopqrstuvwxyzáéíóú');
                $('.alfanumerico').validar_inputs(' abcdefghijklmnñopqrstuvwxyzáéíóú0123456789-_()@./,;:+*¿?!¡#$%[]{}');
                $('.email').validar_inputs('abcdefghijklmnñopqrstuvwxyzáéíóú0123456789-_()@.,;:+*¿?!¡#$%[]{}');
                $('.letras_numeros').validar_inputs(' abcdefghijklmnñopqrstuvwxyzáéíóú0123456789-.');
                $('.numerico').validar_inputs('0123456789');
                $('.decimal').validar_inputs('0123456789.');
                $('.soloclick').validar_inputs('');

                //CAlendario Datepicker jquery
                //    $('.datepicker').datepicker();

                //Select chose
                var config = {
                    '.chosen-select': {},
                    '.chosen-select-deselect': {allow_single_deselect: true},
                    '.chosen-select-no-single': {disable_search_threshold: 10},
                    '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
                    '.chosen-select-width': {width: "10%"}
                }
                for (var selector in config) {
                    $(selector).chosen(config[selector]);
                }

                //Separador de miles
                function format(comma, period) {
                    comma = comma || ',';
                    period = period || '.';
                    var split = this.toString().split('.');
                    var numeric = split[0];
                    var decimal = split.length > 1 ? period + split[1] : '';
                    var reg = /(\d+)(\d{3})/;
                    while (reg.test(numeric)) {
                        numeric = numeric.replace(reg, '$1' + comma + '$2');
                    }
                    return numeric + decimal;
                }
                $('.miles').live("change", function() {
                    $(this).val(format.call($(this).val(), ',', '.'));
                });

                //Formatea la cadena de texto a 2 decimales
                (function($) {
                    $.fn.currencyFormat = function() {
                        this.each(function(i) {
                            $(this).change(function(e) {
                                if (isNaN(parseFloat(this.value)))
                                    return;
                                this.value = parseFloat(this.value).toFixed(2);
                            });
                        });
                        return this; //for chaining
                    }
                })(jQuery);
                $(function() {
                    $('.decimal2').currencyFormat();
                });

            });
        </script>
    </head>
    <body>
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?= base_url() ?>" style="font-size: 40px;">SILI S.A.S</a>
            </div>
            <div class="">
                <ul class="nav navbar-nav">
                    <li class="dropdown dropdown-large">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;">Crear <b class="caret"></b></a>
                        <ul class="dropdown-menu dropdown-menu-large row">
                            <li class="col-sm-4">
                                <ul>
                                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>
                                        <li class="dropdown-header"><u> Sedes</u></li>
                            <?php } ?>
                            <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>
                                <li><a href="<?= base_url() ?>sede/crear">Sede</a></li>
                            <?php } ?>
                            <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                        
                                <li><a href="<?= base_url() ?>salon/crear">Salón</a></li>
                            <?php } ?>                                    
                            <li class="divider"></li>                                            
                            <li class="dropdown-header"><u> Empleados</u></li>
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                    
                        <li><a href="<?= base_url() ?>salario/crear">Salario Laboral</a></li>  
                    <?php } ?>                                    
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon"))) { ?>                                       
                        <li><a href="<?= base_url() ?>empleado/crear">Empleado</a></li>
                    <?php } ?>                                    
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                        <li><a href="<?= base_url() ?>sede_secundaria/crear">Sedes Secundarias</a></li>
                    <?php } ?>                          
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon"))) { ?>                                       
                        <li><a href="<?= base_url() ?>despachar_placa/crear">Despachar Placas</a></li>
                    <?php } ?>                              
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                        <li><a href="<?= base_url() ?>recibir_placa/crear">Recibir Placas</a></li>
                    <?php } ?>                             
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                        <li><a href="<?= base_url() ?>ausencia_laboral/crear">Ausencia Laboral</a></li>
                    <?php } ?>                           
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                        <li><a href="<?= base_url() ?>llamado_atencion/crear">Llamado de Atención</a></li>
                    <?php } ?>
                    <li class="divider"></li>
                    <li class="dropdown-header"><u> Clientes</u></li>
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera"))) { ?>                                       
                        <li><a href="<?= base_url() ?>titular/crear">Titular</a></li>
                    <?php } ?>                            
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera"))) { ?>                                       
                        <li><a href="<?= base_url() ?>alumno/crear">Alumno</a></li>
                    <?php } ?>                        
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                        <li><a href="<?= base_url() ?>cliente/crear">Cliente Prestatario</a></li>
                    <?php } ?>                                    
                    <li class="divider"></li>
                    <li class="dropdown-header"><u> Proveedores</u></li>
                    <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                        <li><a href="<?= base_url() ?>proveedor/crear">Proveedor</a></li>
                    <?php } ?>                                    
                </ul>
                </li>
                <li class="col-sm-4">
                    <ul>
                        <li class="dropdown-header"><u> Transacciones</u></li>
                <li class="dropdown-header" style="font-size:16px;"><u>Créditos</u>:</li>  
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>abono_matricula/crear">ABONO A MATRÍCULA</a></li>
                <?php } ?>         
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>factura/crear">Factura de venta</a></li>
                <?php } ?>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>recibo_caja/crear">Recibo de Caja</a></li>     
                <?php } ?>                       
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>abono_adelanto/crear">Abono adelanto nómina</a></li> 
                <?php } ?>             
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>   
                    <li><a href="<?= base_url() ?>retefuente_compras/crear">Retefuente por compras</a></li>
                <?php } ?>   
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>ingreso/crear">Ingreso</a></li>
                <?php } ?>                     
                <li class="dropdown-header" style="font-size:16px;"><u>Débitos</u>:</li>  
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>   
                    <li><a href="<?= base_url() ?>nomina/crear">Nómina laboral</a></li>
                <?php } ?>                                             
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>adelanto/crear">Adelanto de nómina</a></li>
                <?php } ?>   
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>pago_proveedor/crear">Pago a proveedor</a></li>
                <?php } ?>     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>nota_credito/crear">Nota crédito</a></li>
                <?php } ?>     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>transferencia/crear">Transferencia intersede</a></li>
                <?php } ?>       
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>   
                    <li><a href="<?= base_url() ?>retefuente_ventas/crear">Retefuente por ventas</a></li>
                <?php } ?>                     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>egreso/crear">Egreso</a></li>
                <?php } ?>                                       
                <!--<?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                                                                                                                                                                                     <li><a href="<?= base_url() ?>prestamo/crear">Préstamo</a></li> 
                <?php } ?>                                                    
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                                                                                                                                                                                     <li><a href="<?= base_url() ?>abono_prestamo/crear">Abono préstamo</a></li>
                <?php } ?>-->
                </ul>
                </li>
                <li class="col-sm-4">
                    <ul>
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>   
                            <li class="dropdown-header"><u> Cajas y Bancos</u></li>
                <?php } ?>   
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                    <li><a href="<?= base_url() ?>caja/crear">Caja (Punto de venta)</a></li>
                <?php } ?>                       
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                    <li><a href="<?= base_url() ?>cuenta/crear">Cuenta Bancaria</a></li>
                <?php } ?>                                                           
                <li class="divider"></li>                                                        
                <!--                                    <li class="dropdown-header">Inventario</li>
                                                    <li><a href="#">Articulo Inventario</a></li>
                                                    <li><a href="#">Articulo Insumo</a></li>
                                                    <li><a href="#">Linea Celular</a></li>
                                                    <li><a href="#">Pedido de Insumos</a></li>
                                                    <li class="divider"></li>                                    -->
                <li class="dropdown-header"><u> Matrículas</u></li>        
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                    <li><a href="<?= base_url() ?>contrato_matricula/crear">Contratos Físicos</a></li> 
                <?php } ?>                                    
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera"))) { ?>                                       
                    <li><a href="<?= base_url() ?>matricula/crear">Matrícula</a></li>
                <?php } ?>                                    
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>liquidar_comisiones/crear/new">Liquidar Matrícula</a></li>
                <?php } ?> 
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "jefe_cartera", "cartera"))) { ?>                                       
                    <li><a href="<?= base_url() ?>descuento_matricula/crear">Descuento especial</a></li> 
                <?php } ?>
                <li class="divider"></li>
                <li class="dropdown-header"><u> Enseñanza</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "docente", "secretaria"))) { ?>                                       
                    <li><a href="<?= base_url() ?>reporte_alumno/crear">Reporte de alumno</a></li> 
                <?php } ?>
                <li class="divider"></li>  
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                           
                    <li class="dropdown-header"><u> Traslados</u></li>                                
                    <li><a href="<?= base_url() ?>traslado_contrato_matricula/crear">Contratos Físicos</a></li>
                <?php } ?>
                <li class="divider"></li>
                <li class="dropdown-header"><u> Permisos</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "jefe_cartera"))) { ?>                                       
                    <li><a href="<?= base_url() ?>cod_autorizacion/crear">Cód. de autorización</a></li> 
                <?php } ?>                    
                </ul>
                </li>
                </ul>
                </li>
                <li class="dropdown dropdown-large">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;"> Autorizar <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-large row" style="margin-left: 310px;">
                        <li class="col-sm-12">
                            <ul>                           
                                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>   
                                    <li class="dropdown-header"><u> Cajas y Bancos</u></li>
                        <?php } ?>                     
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                            <li><a href="<?= base_url() ?>cuenta/asignar_sede">Cuenta Bancaria a Sedes</a></li>
                        <?php } ?>                           
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                            <li><a href="<?= base_url() ?>cuenta/asignar_empleado_ingresar">Cuenta bancaria a empleado para INGRESAR</a></li>
                        <?php } ?>  
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                            <li><a href="<?= base_url() ?>cuenta/asignar_empleado_retirar">Cuenta bancaria a empleado para RETIRAR</a></li>
                        <?php } ?> 
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                            <li><a href="<?= base_url() ?>cuenta/asignar_empleado_consultar">Cuenta bancaria a empleado para CONSULTAR</a></li>
                        <?php } ?>                             
                        <li class="divider"></li>                      
                        <li class="dropdown-header"><u> Transacciones</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>transferencia/aprobar">Transferencia intersede</a></li>
                <?php } ?>                       
                </ul>
                </li>
                </ul>
                </li>                
                <li class="dropdown dropdown-large">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;"> Modificar <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-large row" style="margin-left: 426px;">
                        <li class="col-sm-4">
                            <ul>
                                <li class="dropdown-header"><u> Empleados</u></li>
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                            <li><a href="<?= base_url() ?>sedes_empleado/editar">Sedes de Empleado</a></li>
                        <?php } ?>                              
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                            <li><a href="<?= base_url() ?>cargo_jefe_rrpp/editar">Cargo y Jefe de RRPP</a></li>
                        <?php } ?> 
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                            <li><a href="<?= base_url() ?>empleado/renovar_contrato">Renovar contrato laboral</a></li>
                        <?php } ?> 
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon"))) { ?>                                       
                            <li><a href="<?= base_url() ?>empleado/anular_contrato">Finalizar contrato laboral</a></li>
                        <?php } ?>                         
                    </ul>
                </li>
                <li class="col-sm-4">
                    <ul>
                        <li class="dropdown-header"><u> Matrículas</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>matricula/editar_plan">Cambio de plan</a></li>  
                <?php } ?>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>
                    <li><a href="<?= base_url() ?>plan_matricula/modificar">Plan y comisiones</a></li>
                <?php } ?>
                </ul>
                </li>
                <li class="col-sm-4">
                    <ul>
                        <li class="dropdown-header"><u> Clientes</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "docente", "secretaria"))) { ?>                                       
                    <li><a href="<?= base_url() ?>alumno/actualizar">Alumno</a></li>
                <?php } ?>                                   
                </ul>
                </li>
                </ul>
                </li>
                <li class="dropdown dropdown-large">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;"> Anular <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-large row" style="margin-left: 450px;">
                        <li class="col-sm-6">
                            <ul>
                                <li class="dropdown-header"><u> Transacciones</u></li> 
                        <li class="dropdown-header" style="font-size:16px;"><u>Créditos</u>:</li>                                
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>factura/anular">Factura de venta</a></li>
                <?php } ?>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>recibo_caja/anular">Recibo de Caja</a></li>     
                <?php } ?>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>abono_matricula/anular">Abono a matrícula</a></li>
                <?php } ?>                    
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>abono_adelanto/anular">Abono adelanto nómina</a></li> 
                <?php } ?>             
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>   
                    <li><a href="<?= base_url() ?>retefuente_compras/anular">Retefuente por compras</a></li>
                <?php } ?>   
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>ingreso/anular">Ingreso</a></li>
                <?php } ?>                     
                <li class="dropdown-header" style="font-size:16px;"><u>Débitos</u>:</li>  
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>   
                    <li><a href="<?= base_url() ?>nomina/anular">Nómina laboral</a></li>
                <?php } ?>                                             
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>adelanto/anular">Adelanto de nómina</a></li>
                <?php } ?>   
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>pago_proveedor/anular">Pago a proveedor</a></li>
                <?php } ?>     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>nota_credito/anular">Nota crédito</a></li>
                <?php } ?>     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>transferencia/anular">Transferencia intersede</a></li>
                <?php } ?>       
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>   
                    <li><a href="<?= base_url() ?>retefuente_ventas/anular">Retefuente por ventas</a></li>
                <?php } ?>                     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>egreso/anular">Egreso</a></li>
                <?php } ?>
                </ul>
                </li>
                <li class="col-sm-6">
                    <ul>
                        <li class="dropdown-header"><u> Matrículas </u></li> 
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon"))) { ?>                                       
                    <li><a href="<?= base_url() ?>contrato_matricula/anular">Contratos Físicos</a></li> 
                <?php } ?>                    
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera"))) { ?>                                       
                    <li><a href="<?= base_url() ?>matricula/anular">Matrícula</a></li>
                <?php } ?>
                </ul>
                </li>                
                </ul>
                </li>                
                <li class="dropdown dropdown-large">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;">Consultar <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-large row"  style="margin-left: 500px;">
                        <li class="col-sm-4">
                            <ul>
                                <li class="dropdown-header"><u> Sedes</u></li>
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "contador", "calidad", "docente", "empleado_admon", "empleado_rrpp", "secretaria", "titular", "alumno", "cliente"))) { ?>                                       
                            <li><a href="<?= base_url() ?>sede/consultar">Sedes</a></li>
                        <?php } ?>                           
                        <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "calidad", "docente", "empleado_rrpp", "secretaria", "titular", "alumno", "cliente"))) { ?>                                       
                            <li><a href="<?= base_url() ?>salon/consultar">Salones</a></li>
                        <?php } ?>                                    
                        <li class="divider"></li>                                            
                        <li class="dropdown-header"><u> Empleados</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>salario/consultar">Salarios Laborales</a></li> 
                <?php } ?>                                
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "contador"))) { ?>                                       
                    <li><a href="<?= base_url() ?>empleado/consultar">Empleados</a></li>
                <?php } ?>                           
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                    <li><a href="<?= base_url() ?>sede_secundaria/crear">Sedes Secundarias</a></li>
                <?php } ?>                                   
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "contador"))) { ?>                                       
                    <li><a href="<?= base_url() ?>ausencia_laboral/consultar">Ausencias Laborales</a></li>
                <?php } ?>                              
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>                                       
                    <li><a href="<?= base_url() ?>llamado_atencion/consultar">Llamados de Atención</a></li>
                <?php } ?>                                    
                <li class="divider"></li>
                <li class="dropdown-header"><u> Clientes</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "calidad", "docente", "secretaria", "titular", "alumno", "cliente"))) { ?>                                       
                    <li><a href="<?= base_url() ?>titular/consultar">Titulares</a></li>
                <?php } ?>                              
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "calidad", "docente", "secretaria", "titular", "alumno", "cliente"))) { ?>                                       
                    <li><a href="<?= base_url() ?>alumno/consultar">Alumnos</a></li>
                <?php } ?>                            
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo"))) { ?>                                       
                    <li><a href="<?= base_url() ?>cliente/consultar">Clientes Prestatarios</a></li>
                <?php } ?>                                    
                <li class="divider"></li>
                <li class="dropdown-header"><u> Proveedores</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "contador"))) { ?>                                       
                    <li><a href="<?= base_url() ?>proveedor/consultar">Proveedores</a></li>  
                <?php } ?>                                    
                </ul>
                </li>
                <li class="col-sm-4">
                    <ul>                               
                        <li class="dropdown-header"><u> Transacciones</u></li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "contador", "aux_admon", "jefe_cartera", "cartera", "contador", "secretaria"))) { ?>                                       
                    <li><a href="<?= base_url() ?>transacciones/consultar">Flujo de Transacciones</a></li>
                <?php } ?>         
                <li class="dropdown-header" style="font-size:16px;"><u>Créditos</u>:</li>         
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "contador", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>factura/consultar">Factura de venta</a></li>
                <?php } ?>   
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "contador", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>recibo_caja/consultar">Recibo de caja</a></li>
                <?php } ?>                                               
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>abono_adelanto/consultar">Abono adelanto de nómina</a></li>
                <?php } ?>                      
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>retefuente_compras/consultar">Retefuente por compras</a></li>
                <?php } ?>                     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>ingreso/consultar">Ingreso</a></li>
                <?php } ?>                    
                <li class="dropdown-header" style="font-size:16px;"><u>Débitos</u>:</li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>   
                    <li><a href="<?= base_url() ?>nomina/consultar">Nómina laboral</a></li>
                <?php } ?>          
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>adelanto/consultar">Adelanto de nómina</a></li>
                <?php } ?>                 
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>pago_proveedor/consultar">Pago a proveedor</a></li>
                <?php } ?>    
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>nota_credito/consultar">Nota crédito</a></li>
                <?php } ?>     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>retefuente_ventas/consultar">Retefuente por ventas</a></li>
                <?php } ?>                      
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede"))) { ?>
                    <li><a href="<?= base_url() ?>egreso/consultar">Egreso</a></li>
                <?php } ?>   
                <li class="dropdown-header" style="font-size:16px;"><u>Crédito/Débito</u>:</li>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>
                    <li><a href="<?= base_url() ?>transferencia/consultar">Transferencia intersede</a></li>
                <?php } ?>                     
                </ul>
                </li>
                <li class="col-sm-4">
                    <ul>
                        <!--                                    <li class="dropdown-header">Inventario</li>
                                                            <li><a href="#">Articulo Inventario</a></li>
                                                            <li><a href="#">Articulo Insumo</a></li>
                                                            <li><a href="#">Linea Celular</a></li>
                                                            <li><a href="#">Pedido de Insumos</a></li>
                                                            <li class="divider"></li>                                    -->
                        <li class="dropdown-header"><u> Matrículas</u></li>
                <!--<li><a href="#">Contratos Físicos</a></li>--> 
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "calidad", "docente", "secretaria"))) { ?>                                       
                    <li><a href="<?= base_url() ?>matricula/consultar">Matrícula</a></li>
                <?php } ?> 
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera"))) { ?>                                       
                    <li><a href="<?= base_url() ?>liquidar_comisiones/consultar">Liquidación de comisiones</a></li>
                <?php } ?>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>                                       
                    <li><a href="<?= base_url() ?>matricula/consultar_plan_pagos">Cronograma de pagos</a></li>
                <?php } ?>
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>                                       
                    <li><a href="<?= base_url() ?>matricula/consultar_pagos_matricula">Pagos realizados</a></li>
                <?php } ?>                    
                <li class="divider"></li>                     
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "contador"))) { ?>  
                    <li class="dropdown-header"><u> Cajas y Bancos</u></li>
                <?php } ?>    
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "contador"))) { ?>                                       
                    <li><a href="<?= base_url() ?>caja/consultar">Caja (Punto de venta)</a></li> 
                <?php } ?>                                 
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "contador"))) { ?>                                       
                    <li><a href="<?= base_url() ?>cuenta/consultar">Cuenta Bancaria</a></li>
                <?php } ?>
                <li class="divider"></li>
                <li class="dropdown-header"><u> Permisos</u></li>                               
                <?php if (in_array($_SESSION["perfil"], array("admon_sistema", "directivo", "admon_sede", "aux_admon", "jefe_cartera", "cartera", "secretaria"))) { ?>                                       
                    <li><a href="<?= base_url() ?>cod_autorizacion/consultar">Cód. de autorización</a></li>
                <?php } ?>                    
                </ul>
                </li>
                </ul>
                </li>                    
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <!--llamamos al helper alertas_helper para que nos traiga el menu de alertas-->
                    <?= menu_alertas() ?>                   
                    <li class="dropdown dropdown-large">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 18px;"><span
                                class="glyphicon glyphicon-user"></span> Opciones de usuario <b class="caret"></b></a>
                        <ul class="dropdown-menu dropdown-menu-large row">
                            <li class="col-sm-4">
                                <ul>
                                    <li><img src="<?= $_SESSION["rutaImg"] ?>"></li>
                                </ul>
                            </li>
                            <li class="col-sm-8">
                                <ul>
                                    <!--<li><a href="#">Ver Perfil</a></li>-->
                                    <li><p><b>Usuario: </b><em><?= $_SESSION["nombres"] ?></em></p></li>
                                    <li><p><b>Perfil: </b><em><?= $_SESSION["perfil"] ?></em></p></li>
                                    <li class="divider"></li>
                                    <li><a href="<?= base_url() ?>password/cambiar"><span
                                                class="glyphicon glyphicon-lock"></span> Cambiar Contraseña</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?= base_url() ?>login/logout_ci"><span
                                                class="glyphicon glyphicon-off"></span> Cerrar Sesión &raquo;</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>      
                </ul>
            </div>
        </div>  
