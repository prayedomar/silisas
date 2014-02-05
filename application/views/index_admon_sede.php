<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Sili - Inicio</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9" />
        <meta name="description" content="Inicio Directivo" />
        <meta name="author" content="Omar Stevenson Rivera Correa" />
        <link rel="shortcut icon" href="{base_url}images/favicon2.ico" />
        <!-- CSS -->
        <link  rel="stylesheet" href="{base_url}libraries/bootstrap_3.0.2/css_cerulean/bootstrap.min.css" />
        <link rel="stylesheet" href="{base_url}/css/bootstrapMio.css" />
        <link rel="stylesheet" href="{base_url}libraries/bootstrap_select1.3.5/bootstrap-select.min.css" />
        <!-- Js -->
        <script src="{base_url}libraries/html5shim/html5.js"></script>
        <script src="{base_url}libraries/respond/respond.min.js"></script>
        <script src="{base_url}libraries/jquery/jquery-1.9.0.js"></script>
        <script src="{base_url}libraries/bootstrap_3.0.2/js/bootstrap.min.js"></script>
        <script src="{base_url}libraries/bootstrap_select1.3.5/bootstrap-select.min.js"  type="text/javascript"></script>
        <!--Style-->
        <style>
            body {
                padding: 10px 10px 10px 10px;
            }
        </style>
        <!--para que pueda funcionar bootstrap select-->
        <script type="text/javascript">
            $(window).on('load', function() {

                $('.selectpicker').selectpicker({
                    'selectedText': 'cat'
                });

                // $('.selectpicker').selectpicker('hide');
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.nav li.dropdown').hover(function() {
                    $(this).addClass('open');
                }, function() {
                    $(this).removeClass('open');
                });
            });
        </script>
    </head>
    <body>
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <a class="navbar-brand" href="{base_url}" style="font-size: 40px;">Sili SAS</a>
            </div>
            <div class="navbar-collapse collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown dropdown-large">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="font-size: 20px;">Crear <b class="caret"></b></a>
                        <ul class="dropdown-menu dropdown-menu-large row" style="margin-left: 175px;">
                            <li class="col-sm-3">
                                <ul>
                                    <li class="dropdown-header">Sedes</li>
                                    <li><a href="{base_url}index_admon_sede/crear_sede">Sede</a></li>
                                    <li><a href="#">Salon</a></li>
                                    <li class="divider"></li>                                    
                                    <li class="dropdown-header">Empleados</li>
                                    <li><a href="#">Empleado</a></li>
                                    <li><a href="#">Salario</a></li>
                                    <li><a href="#">Asignar Sede</a></li>
                                    <li><a href="#">Cambio de Cargo</a></li>
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
                                    <li><a href="#">Prestamo</a></li>
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
                                    <li><a href="#">Contrato de Matricula</a></li>
                                    <li><a href="#">Material de Estudio</a></li>
                                    <li><a href="#">Entrega de Material</a></li>
                                    <li><a href="#">Comisión de Escala</a></li>
                                    <li><a href="#">Comisión de Matricula</a></li>
                                    <li class="divider"></li>
                                    <li class="dropdown-header">Permisos</li>
                                    <li><a href="#">Código de Autorización</a></li>                                    
                                </ul>
                            </li>
                            <li class="col-sm-3">
                                <ul>
                                    <li class="dropdown-header">Traslados</li>
                                    <li><a href="#">Contrato de Matricula</a></li>
                                    <li><a href="#">Material de Estudio</a></li>
                                    <li><a href="#">Articulo de Inventario</a></li>                                   
                                    <li class="divider"></li>
                                    <li class="dropdown-header">Enseñanza</li>
                                    <li><a href="#">Horario de Clase</a></li>
                                    <li><a href="#">Reserva de Clase</a></li>
                                    <li><a href="#">Reporte de Alumno</a></li>
                                    <li><a href="#">Grados</a></li>
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 20px;">Modificar <b class="caret"></b></a>
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
                        <ul class="dropdown-menu dropdown-menu-large row" style="margin-left: 631px;">
                            <li class="col-sm-4">
                                <ul>
                                    <li><img src={rutaImg}></li>
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

