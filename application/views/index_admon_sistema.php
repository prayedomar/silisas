<!DOCTYPE html>
<html lang="es">
    <head>
        <title>SILI - Administrador Sistema</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=9"/>
        <meta name="description" content="Inicio Directivo"/>
        <meta name="author" content="Omar Stevenson Rivera Correa"/>
        <!-- Favicon -->        
        <link rel="shortcut icon" href="{base_url}images/favicon2.ico"/>
        <!-- CSS -->
        <link  rel="stylesheet" href="{base_url}libraries/bootstrap_3.0.2/css_cerulean/bootstrap.min.css"/>
        <link rel="stylesheet" href="{base_url}/css/bootstrapMio.css"/>
        <link rel="stylesheet" href="{base_url}libraries/jqueryUI/jquery-ui.css"/>
        <link rel="stylesheet" href="{base_url}libraries/datepicker_bootstrap/datepicker.css"/>
        <link rel="stylesheet" href="{base_url}libraries/select_chosen/chosen.css"/>

        <!-- Js -->
        <script src="{base_url}libraries/html5shim/html5.js"></script>
        <script src="{base_url}libraries/respond/respond.min.js"></script>
        <script src="{base_url}libraries/jquery/jquery1.7.2.min.js"></script>
        <script src="{base_url}libraries/bootstrap_3.0.2/js/bootstrap.min.js"></script>
        <script src="{base_url}libraries/jqueryUI/jquery1.18-ui.min.js"/></script>
    <script src="{base_url}libraries/datepicker_bootstrap/bootstrap-datepicker.js"/></script>
<script src="{base_url}libraries/select_chosen/chosen.jquery.js"/></script>

<!--Style-->
<style>
    body {
        padding: 10px 10px 10px 10px;
    }
</style>

<!--llenar ciudades y departamentos-->
<script type="text/javascript">
    //Deshabilitamos la tecla esc en todo el programa, porque con esta pueden cancelar el envio de una peticion post
    //de cualquier formulario y no tiene ninguna productividad dentro de la plataforma.
    $(document).keydown(function(e) {
        if (e.keyCode == 27)
            return false;
    });

    $(document).ready(function() {
        //para que la navbar se active al pasar el mouse
        $('.nav li.dropdown').hover(function() {
            $(this).addClass('open');
        }, function() {
            $(this).removeClass('open');
        });
        //Enviar formulario por ajax
        $('#botonValidar').live('click', function() {
            //Mostramos el div de loading y edshabilitamos el teclado
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
//                        $("#validacion_alert").attr('class', 'alert alert-danger');
                        $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                        $("#div_alert").html(data);
                        $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                        //Para cerrar el modal de loading
                        $('#modal_loading').modal('hide');
                        //PAra habilitar la tecla esc
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
        $('.alfanumerico').validar_inputs(' abcdefghijklmnñopqrstuvwxyzáéíóú0123456789-_()@.,;:+*¿?!¡#$%[]{}');
        $('.email').validar_inputs('abcdefghijklmnñopqrstuvwxyzáéíóú0123456789-_()@.,;:+*¿?!¡#$%[]{}');
        $('.letras_numeros').validar_inputs(' abcdefghijklmnñopqrstuvwxyzáéíóú0123456789-.');
        $('.numerico').validar_inputs('0123456789');
        $('.decimal').validar_inputs('0123456789.');
        $('.soloclick').validar_inputs('');

        //CAlendario Datepicker jquery
        $('.datepicker').datepicker();

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
            <a class="navbar-brand" href="{base_url}" style="font-size: 40px;">SILI S.A.S</a>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown dropdown-large">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;">Crear <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-large row" style="margin-left: 215px;">
                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">Sedes</li>
                                <li><a href="{base_url}index_admon_sistema/crear_sede">Sede</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_salon">Salón</a></li>
                                <li class="divider"></li>                                            
                                <li class="dropdown-header">Empleados</li>
                                <li><a href="{base_url}index_admon_sistema/crear_salario">Salario Laboral</a></li>                                   
                                <li><a href="{base_url}index_admon_sistema/crear_empleado">Empleado</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_sede_secundaria">Sedes Secundarias</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_despachar_placa">Despachar Placas</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_recibir_placa">Recibir Placas</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_ausencia_laboral">Ausencia Laboral</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_llamado_atencion">Llamado de Atención</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Clientes</li>
                                <li><a href="{base_url}index_admon_sistema/crear_titular">Titular</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_alumno">Alumno</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_cliente">Cliente Prestatario</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Proveedores</li>
                                <li><a href="{base_url}index_admon_sistema/crear_proveedor">Proveedor</a></li>                                
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">Cajas y Bancos</li>
                                <li><a href="{base_url}index_admon_sistema/crear_caja">Caja (Punto de Venta)</a></li>                                
                                <li><a href="{base_url}index_admon_sistema/crear_cuenta">Cuenta Bancaria</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_asignar_cuenta_sede">Autorizar Cuenta Bancaria a Sedes</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_asignar_cuenta_empleado">Autorizar Cuenta Bancaria a Empleado</a></li>
                                <li class="divider"></li>                                
                                <li class="dropdown-header">Transacciones</li>
                                <li><a href="{base_url}index_admon_sistema/crear_adelanto">Adelanto</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_prestamo">Préstamo</a></li> 
                                <li><a href="{base_url}index_admon_sistema/crear_abono_adelanto">Abono a Adelanto</a></li>                                
                                <li><a href="{base_url}index_admon_sistema/crear_abono_prestamo">Abono a Préstamo</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_ingreso">Ingreso</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_egreso">Egreso</a></li>
                                <li><a href="#">Factura</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_nomina">Nómina Laboral</a></li>
                                <li><a href="#">Nota Credito</a></li>
                                <li><a href="#">Pago Proveedor</a></li>
                                <li><a href="#">Cuentas por Pagar</a></li>
                                <li><a href="#">Recibo de Caja</a></li>
                                <li><a href="#">Retención en la fuente</a></li>
                                <li><a href="#">Transferencia Intersede</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">Inventario</li>
                                <li><a href="#">Articulo Inventario</a></li>
                                <li><a href="#">Articulo Insumo</a></li>
                                <li><a href="#">Linea Celular</a></li>
                                <li><a href="#">Pedido de Insumos</a></li>
                                <li class="divider"></li>                                    
                                <li class="dropdown-header">Matrículas</li>
                                <li><a href="{base_url}index_admon_sistema/crear_contrato_fisico">Contratos Físicos</a></li>                                
                                <li><a href="{base_url}index_admon_sistema/crear_matricula">Matrícula</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_liquidar_matricula/new">Liquidar Matrícula</a></li>
                                <li><a href="#">Referido</a></li>
                                <li><a href="#">Consolidar Referido</a></li>
                                <li><a href="#">Material de Estudio</a></li>
                                <li><a href="#">Entrega de Material</a></li>
                                <li><a href="#">Comisión de Escala</a></li>
                                <li><a href="#">Comisión de Matrícula</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Permisos</li>
                                <li><a href="#">Código de Autorización</a></li>                                    
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">Traslados</li>
                                <li><a href="{base_url}index_admon_sistema/crear_traslado_contrato">Contratos Físicos</a></li>
                                <li><a href="#">Material de Estudio</a></li>
                                <li><a href="#">Articulo de Inventario</a></li>                                   
                                <li class="divider"></li>
                                <li class="dropdown-header">Enseñanza</li>
                                <li><a href="#">Horario de Clase</a></li>
                                <li><a href="#">Reserva de Clase</a></li>
                                <li><a href="#">Reporte de Alumno</a></li>
                                <li><a href="#">Grados</a></li>                                  
                                <li><a href="#">Descargar Certificado</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Servicio al cliente</li>
                                <li><a href="#">Acuerdo de Pago</a></li>
                                <li><a href="#">Registro PQR</a></li>
                                <li><a href="#">Respuesta PQR</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown dropdown-large">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;">Modificar <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-large row" style="margin-left: 310px;">
                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">Sedes</li>
                                <li><a href="{base_url}index_admon_sistema/crear_sede">Sede</a></li>
                                <li><a href="{base_url}index_admon_sistema/crear_salon">Salon</a></li>
                                <li class="divider"></li>                                    
                                <li class="dropdown-header">Empleados</li>
                                <li><a href="{base_url}index_admon_sistema/editar_sedes_empleado">Sedes de Empleado</a></li>
                                <li><a href="{base_url}index_admon_sistema/editar_cargo_jefe">Cargo y Jefe de RRPP</a></li>
                                <li><a href="#">Renovar Contrato Laboral</a></li>                                
                                <li><a href="#">Entrega de Placa</a></li>
                                <li><a href="#">Contrato Laboral</a></li>
                                <li><a href="#">Ausencia Laboral</a></li>
                                <li><a href="#">Llamado de Atención</a></li>
                                <li><a href="#">Suspensión Laboral</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Proveedores</li>
                                <li><a href="#">Proveedor</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">Transacciones</li>
                                <li><a href="#">Adelanto</a></li>
                                <li><a href="#">Egreso</a></li>
                                <li><a href="#">Factura</a></li>
                                <li><a href="#">Ingreso</a></li>
                                <li><a href="#">Nomina</a></li>
                                <li><a href="#">Nota Credito</a></li>
                                <li><a href="#">Pago Proveedor</a></li>
                                <li><a href="#">Préstamo</a></li>
                                <li><a href="#">Recibo de Caja</a></li>
                                <li><a href="#">Retención en la fuente</a></li>
                                <li><a href="#">Transferencia Intersede</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Bancos y Cajas</li>
                                <li><a href="#">Cuenta Banco</a></li>
                                <li><a href="#">Caja Efectivo</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">Inventario</li>
                                <li><a href="#">Articulo Inventario</a></li>
                                <li><a href="#">Articulo Insumo</a></li>
                                <li><a href="#">Linea Celular</a></li>
                                <li><a href="#">Pedido de Insumos</a></li>
                                <li class="divider"></li>                                    
                                <li class="dropdown-header">Matriculas</li>
                                <li><a href="#">Matricula</a></li>
                                <li><a href="#">Referido</a></li>
                                <li><a href="#">Consolidar Referido</a></li>
                                <li><a href="#">Contrato de Matrícula</a></li>
                                <li><a href="#">Material de Estudio</a></li>
                                <li><a href="#">Entrega de Material</a></li>
                                <li><a href="#">Comisión de Escala</a></li>
                                <li><a href="#">Comisión de Matrícula</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Permisos</li>
                                <li><a href="#">Código de Autorización</a></li>                                    
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">Traslados</li>
                                <li><a href="#">Contratos Físicos</a></li>
                                <li><a href="#">Material de Estudio</a></li>
                                <li><a href="#">Articulo de Inventario</a></li>                                   
                                <li class="divider"></li>
                                <li class="dropdown-header">Enseñanza</li>
                                <li><a href="#">Horario de Clase</a></li>
                                <li><a href="#">Reserva de Clase</a></li>
                                <li><a href="#">Reporte de Alumno</a></li>
                                <li><a href="#">Grados</a></li>
                                <li><a href="#">Descargar Certificado</a></li>                                
                                <li class="divider"></li>
                                <li class="dropdown-header">Clientes</li>
                                <li><a href="#">Titular</a></li>
                                <li><a href="#">Alumno</a></li>                                    
                                <li class="divider"></li>
                                <li class="dropdown-header">Servicio al cliente</li>
                                <li><a href="#">Acuerdo de Pago</a></li>
                                <li><a href="#">Registro PQR</a></li>
                                <li><a href="#">Respuesta PQR</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>              
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 20px;">Anular <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-header">Dropdown header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 20px;">Consultar <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-header">Dropdown header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown dropdown-large">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;">Opciones de Usuario <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-large row">
                        <li class="col-sm-4">
                            <ul>
                                <li><img src="{rutaImg}"></li>
                            </ul>
                        </li>
                        <li class="col-sm-8">
                            <ul>
                                <li><a href="#">Ver Perfil</a></li>
                                <li><a href="#">Cambiar Contraseña</a></li>
                                <li class="divider"></li>
                                <li><a href="{base_url}login/logout_ci">Cerrar Sesión &raquo;</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>      
            </ul>
        </div>
    </div>  
