<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Crear nómina laboral</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <div class="form-group">
                                <label>Tipo Nómina<em class="required_asterisco">*</em></label>
                                <select name="tipo_nomina" id="tipo_nomina" data-placeholder="Seleccione tipo de nómina" class="form-control exit_caution">
                                    <option value="default">Seleccione tipo de nómina</option>
                                    <option value="1">Nómina RRPP: Conceptos pendientes</option>
                                    <option value="2">Nómina RRPP: Otros conceptos</option>
                                    <option value="3">Nómina Administrativa</option>
                                </select>
                            </div>                                
                            <div class="form-group">
                                <label>Empleado<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos de cada una de sus sedes autorizadas.</p>
                                <select name="empleado" id="empleado" data-placeholder="Seleccione Empleado" class="form-control exit_caution" disabled>
                                    <option value="default">Seleccione primero tipo de nómina</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Periodicidad<em class="required_asterisco">*</em></label>
                                <select name="periodicidad" id="periodicidad" data-placeholder="Seleccione Empleado" class="form-control exit_caution" disabled>
                                    <option value="default">Seleccione primero Empleado</option>
                                </select>
                            </div>                            
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Fecha Inicial<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_inicio" id="fecha_inicio" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha inicial de la Nómina">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Fecha Final<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_fin" id="fecha_fin" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha final de la Nómina">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div id="validacion_inicial">
                            </div>                            
                            <div class="row text-center separar_submit">
                                <button type="button" class="btn btn-default" id="consultar_empleado"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar Empleado </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_info_apoyo" style="display: none">
                    <div class="row separar_div">
                        <legend>Información de Apoyo</legend>
                        <div class="row">
                            <div class="col-xs-12">
                                <div id="div_contrato_laboral">
                                </div>
                                <div id="div_ultimas_nominas">
                                </div>
                                <div id="div_adelantos">
                                </div>
                                <div id="div_prestamos">
                                </div>
                                <div id="div_ausencias">
                                </div>
                                <div id="div_seguridad_social">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_nomina" style="display: none;">
                    <!--<div id="div_nomina">-->
                    <div class="row separar_div">
                        <legend>Conceptos de Nómina</legend><p class="required_alert"><em class="required_asterisco separar_div">*</em> Campos Obligatorios</p>
                        <div id="conceptos_pendientes">
                        </div>
                        <div id="conceptos_nuevos">
                            <label>Conceptos Nuevos</label>
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <button class="btn btn-default" type="button" id="agregar_concepto"><span class="glyphicon glyphicon-plus"></span> Agregar Concepto</button>  
                            </div>
                            <div class="col-xs-6 col-xs-offset-4">
                                <div class="row">
                                    <div class="col-xs-5  col-xs-offset-1">
                                        <p><h4>Total Devengado</h4></p>
                                        <p><h4>Total Deducido</h4></p>
                                        <p><h3>Total Nómina</h3></p>
                                    </div>
                                    <div class="col-xs-6">
                                        <div id="div_total_devengado"><h4>$ 0.00</h4></div>
                                        <div id="div_total_deducido"><h4>$ 0.00</h4></div>
                                        <div id="div_total_nomina"><h3>$ 0.00</h3></div>
                                    </div>
                                </div>   
                            </div>
                        </div>   
                    </div>
                    <div class="row">
                        <legend>Pago de Nómina</legend>
                        <div class="overflow_tabla">
                            <label>Caja de Efectivo Origen (Punto de venta)</label>
                            <p class="help-block"><B>> </B>Seleccione una caja en el caso en que halla utilizado dinero de ella para realizar ésta transacción (Sólo aparecerán las cajas previamente autorizadas para usted).</p>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Escojer</th>  
                                        <th class="text-center">Sede</th>
                                        <th class="text-center">Tipo de Caja</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Fecha de Creación</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_caja_efectivo">
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label>Valor retirado de la Caja de Efectivo</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" name="efectivo_retirado" id="efectivo_retirado" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly="readonly">
                            </div>
                        </div>
                        <hr>
                        <div class="overflow_tabla">
                            <label>Cuenta Bancaria Origen</label>
                            <p class="help-block"><B>> </B>Seleccione una cuenta en el caso en que halla utilizado dinero de ella para realizar ésta transacción (Sólo aparecerán las cuentas previamente autorizadas para usted).</p>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Escojer</th>
                                        <th class="text-center"># Cuenta</th>
                                        <th class="text-center">Tipo Cuenta</th>
                                        <th class="text-center">Banco</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Observación</th>
                                        <th class="text-center">Fecha Creación</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_cuenta_bancaria">
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label>Valor retirado de la Cuenta Bancaria</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" name="valor_retirado" id="valor_retirado" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12" readonly="readonly">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Observación</label>
                            <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observación..."  style="max-width:100%;"></textarea>
                        </div>
                        <div id="validacion_alert">
                        </div>                        
                        <div class="form-group separar_submit">
                            <input type="hidden" id="action_validar" value={action_validar} />
                            <input type="hidden" name="id_responsable" value={id_responsable} />
                            <input type="hidden" name="dni_responsable" value={dni_responsable} />
                            <!--calculamos la cantidad de dias de la nomina y la cantidad de dias laborados (dias_nomina - ausencias)-->
                            <input type="hidden" name="dias_nomina" id="dias_nomina"/>
                            <input type="hidden" name="dias_remunerados" id="dias_remunerados"/>
                            <input type="hidden" name="cant_ausencias" id="cant_ausencias"/>
                            <!--Aqui almacenamos el total devengado de la nomina-->
                            <input type="hidden" name="total_devengado" class="miles decimal2" id="total_devengado"/>
                            <input type="hidden" name="total_deducido" class="miles decimal2" id="total_deducido"/>
                            <input type="hidden" name="total_nomina" class="miles decimal2" id="total_nomina"/>
                            <!--para controlar el id de los nuevos conceptos agregados-->
                            <input type="hidden" name="contador_new_concepto" class="miles decimal2" id="contador_new_concepto"/>
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                <button id="btn_validar" class="btn btn-success">Crear Nómina</button>  
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div>   
                    </div> 
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Cargar empleado segun tipo de nomina
    $(".form-group").delegate("#tipo_nomina", "change", function() {
        if ($("#tipo_nomina").val() == "default") {
            $("#empleado").attr('disabled', 'disabled');
            $("#empleado").html('<option value="default" selected>Seleccione primero tipo de nomina</option>');
        } else {
            tipoNomina = $('#tipo_nomina').val();
            $.post("{action_llena_empleados_t_nomina}", {
                tipoNomina: tipoNomina
            }, function(data) {
                $("#empleado").removeAttr("disabled");
                $("#empleado").html(data);
                $("#empleado").prepend('<option value="default" selected>Seleccione empleado</option>');
            });
        }
    });    
    
    //Colocamos el contador de nuevos conceptos en 1
    $('#contador_new_concepto').attr('value', '1');

    //Calculamos total devengado, deducido y total nomina
    function calcular_total() {
        var total_devengado = 0;
        var total_deducido = 0;
        var cantidad = 0;
        var valor_unitario = 0;
        $(".renglon_concepto").each(function() {
            cantidad = new Number($(this).find("#cantidad").val());
            valor_unitario = new Number($(this).find("#valor_unitario").val().split(",").join(""));
            debito_credito = new Number($(this).find("#debito_credito").val());
            if (debito_credito == 1) {
                total_devengado = total_devengado + (cantidad * valor_unitario);
                $(this).find("#total_concepto").attr('value', (cantidad * valor_unitario).toFixed(2));
                $(this).find("#total_concepto").change();
            } else {
                total_deducido = total_deducido + (cantidad * valor_unitario);
                $(this).find("#total_concepto").attr('value', (cantidad * valor_unitario).toFixed(2));
                $(this).find("#total_concepto").change();
            }
        });
        $('#total_devengado').attr('value', ((total_devengado).toFixed(2)));
        $('#total_devengado').change();
        $("#div_total_devengado").html("<h4>$ " + $('#total_devengado').val() + "</h4>");
        $('#total_deducido').attr('value', ((total_deducido).toFixed(2)));
        $('#total_deducido').change();
        $("#div_total_deducido").html("<h4>$ " + $('#total_deducido').val() + "</h4>");
        $('#total_nomina').attr('value', ((total_devengado - total_deducido).toFixed(2)));
        $('#total_nomina').change();
        $("#div_total_nomina").html("<h3>$ " + $('#total_nomina').val() + "</h3>");
    }

    //Habilita el campo periodicidad
    $("form").delegate("#empleado", "change", function() {
        empleado = $(this).val();
        $.post('{action_llena_periodicidad_nomina}', {
            empleado: empleado
        }, function(data) {
            $("#periodicidad").removeAttr("disabled");
            $("#periodicidad").html(data);
            $("#periodicidad").prepend('<option value="default" selected>Seleccione Periodicidad</option>');
        });
    });

    //Llenamos la informacion de apoyo
    $("form").delegate("#consultar_empleado", "click", function() {
        var empleado = $('#empleado').val();
        var periodicidad = $('#periodicidad').val();
        var fechaInicio = $('#fecha_inicio').val();
        var fechaFin = $('#fecha_fin').val();
        if ((empleado != "default") && (periodicidad != "default") && (fechaInicio != "") && (fechaFin != "")) {
            //Validamos las fecha que coincidan con la periodicidad
            $.post('{action_validar_fechas_periodicidad}', {
                periodicidad: periodicidad,
                fechaInicio: fechaInicio,
                fechaFin: fechaFin
            }, function(data) {
                if (data == "OK") {
                    //Bloqueamos los 3 primeros campos
                    $('#empleado').attr('disabled', 'disabled');
                    $('#periodicidad').attr('disabled', 'disabled');
                    $('#fecha_inicio').attr('disabled', 'disabled');
                    $('#fecha_fin').attr('disabled', 'disabled');
                    $('#consultar_empleado').attr('disabled', 'disabled');

                    //Llenamos El contrato laboral
                    $.post('{action_llena_info_contrato_laboral}', {
                        empleado: empleado
                    }, function(data) {
                        //Mostramos el div de info ayuda
                        $("#div_info_apoyo").css("display", "block");
                        if (data == "") {
                            $("#div_contrato_laboral").html('<label>Contrato Laboral</label><div class="alert alert-info separar_div" id="div_info_contrato_laboral"></div>');
                            $("#div_info_contrato_laboral").html("<p>No se encontró un Contrato Laboral para el empleado, por lo tanto, No es posible realizar la nómina.</p>");
                        } else {
                            $("#div_contrato_laboral").html(data);
                            $("#div_contrato_laboral").prepend('<label>Contrato Laboral</label>');
                            $("#div_nomina").css("display", "block");

                            //Llenamos la ultima nomina
                            $.post('{action_llena_info_ultimas_nominas}', {
                                empleado: empleado
                            }, function(data) {
                                if (data == "") {
                                    $("#div_ultimas_nominas").html('<label>Últimas Nóminas</label><div class="alert alert-info separar_div" id="div_info_ultimas_nominas"></div>');
                                    $("#div_info_ultimas_nominas").html("<p>No se encontraron Nóminas para el empleado.</p>");
                                } else {
                                    $("#div_ultimas_nominas").html(data);
                                    $("#div_ultimas_nominas").prepend('<label>Últimas Nóminas</label>');
                                }
                            });
                            //Llenamos los adelantos vigentes
                            $.post('{action_llena_info_adelantos}', {
                                empleado: empleado
                            }, function(data) {
                                if (data == "") {
                                    $("#div_adelantos").html('<label>Adelantos Vigentes</label><div class="alert alert-info separar_div" id="div_info_adelantos"></div>');
                                    $("#div_info_adelantos").html("<p>No se encontraron Adelantos vigentes para el empleado.</p>");
                                } else {
                                    $("#div_adelantos").html(data);
                                    $("#div_adelantos").prepend('<label>Adelantos Vigentes</label>');
                                }
                            });
                            //Llenamos los Prestamos vigentes
                            $.post('{action_llena_info_prestamos}', {
                                empleado: empleado
                            }, function(data) {
                                if (data == "") {
                                    $("#div_prestamos").html('<label>Préstamos Vigentes</label><div class="alert alert-info separar_div" id="div_info_prestamos"></div>');
                                    $("#div_info_prestamos").html("<p>No se encontraron Prestamos vigentes para el empleado.</p>");
                                } else {
                                    $("#div_prestamos").html(data);
                                    $("#div_prestamos").prepend('<label>Préstamos Vigentes</label>');
                                }
                            });
                            //Llenamos las Ausencias Laborales vigentes dentro del periodo de la nomina
                            $.post('{action_llena_info_ausencias}', {
                                empleado: empleado,
                                fechaInicio: fechaInicio,
                                fechaFin: fechaFin
                            }, function(data) {
                                var obj = JSON.parse(data);
                                if (obj.respuesta == "OK")
                                {
                                    if ($("#periodicidad").val() == '1') {
                                        var dias_nomina = new Number(obj.cant_nomina);
                                    } else {
                                        if ($("#periodicidad").val() == '2') {
                                            var dias_nomina = new Number(7);
                                        } else {
                                            if ($("#periodicidad").val() == '3') {
                                                var dias_nomina = new Number(15);
                                            } else {
                                                if ($("#periodicidad").val() == '4') {
                                                    var dias_nomina = new Number(30);
                                                }
                                            }
                                        }
                                    }
                                    var dias_no_remunerados = new Number(obj.cant_no_remunerada);
                                    $('#dias_nomina').attr('value', dias_nomina);
                                    $('#dias_remunerados').attr('value', (dias_nomina - dias_no_remunerados));
                                    $('#cant_ausencias').attr('value', obj.cant_ausencias);
                                    if (obj.html_ausencias == "") {
                                        $("#div_ausencias").html('<label>Ausencias Laborales</label><div class="alert alert-info separar_div" id="div_info_ausencias"></div>');
                                        $("#div_info_ausencias").html("<p>No se encontraron Ausencias Laborales vigentes para el empleado, en el rango de fechas de la Nómina.</p>");
                                    } else {
                                        $("#div_ausencias").html(obj.html_ausencias);
                                        $("#div_ausencias").prepend('<label>Ausencias Laborales</label>');
                                    }
                                }
                            });
                            //Llenamos los pagos de serguridad social
                            $.post('{action_llena_info_seguridad_social}', {
                                empleado: empleado
                            }, function(data) {
                                if (data == "") {
                                    $("#div_seguridad_social").html('<label>Últimos pagos de Seguridad Social (RRPP y Prestación de Servicios)</label><div class="alert alert-info separar_div" id="div_info_seguridad_social"></div>');
                                    $("#div_info_seguridad_social").html("<p>No se encontraron Pagos de Seguridad Social para el empleado.</p>");
                                } else {
                                    $("#div_seguridad_social").html(data);
                                    $("#div_seguridad_social").prepend('<label>Últimos pagos de Seguridad Social (RRPP y Prestación de Servicios)</label>');
                                }
                            });
                            //Llenamos los conceptos pendientes de RRPP
                            $.post('{action_llena_concepto_pdtes_rrpp}', {
                                empleado: empleado
                            }, function(data) {
                                if (data == "") {
                                    $("#conceptos_pendientes").html(data);
                                    $("#conceptos_pendientes").removeAttr('class');
                                } else {
                                    $("#conceptos_pendientes").html(data);
                                    $('#conceptos_pendientes').attr('class', 'separar_div');
                                }
                                calcular_total();
                            });
                            //Agregamos los conceptos cotidianos dependiendo del t_salario
                            var idUltimoConcepto = $('#contador_new_concepto').val();
                            $.post('{action_llena_concepto_cotidiano}', {
                                empleado: empleado,
                                idUltimoConcepto: idUltimoConcepto
                            }, function(data) {
                                var obj = JSON.parse(data);
                                if (obj.respuesta == "OK")
                                {
                                    $("#conceptos_nuevos").append(obj.html_concepto);
                                    //Damos click a los select de t_concepto
                                    $(".renglon_cotidiano").each(function() {
                                        $(this).find("#t_concepto_nomina").change();
                                    });
                                    //Aumentamos el contador de nuevos conceptos.
                                    $('#contador_new_concepto').attr('value', obj.ultimo_concepto);
                                }
                            });
                        }
                    });
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(data);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong>Antes de consultar, ingrese empleado, periodicidad, fecha inicial y fecha final.</strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });

    $("#div_nomina").delegate("#cantidad", "keyup", function() {
        calcular_total();
    });
    $("#div_nomina").delegate("#valor_unitario", "keyup", function() {
        calcular_total();
    });

    //Agregamos nuevos conceptos desde el boton Agregar Concepto.
    $("#div_nomina").delegate("#agregar_concepto", "click", function() {
        //Agregamos un concepto de nomina
        var idUltimoConcepto = $('#contador_new_concepto').val();
        var empleado = $('#empleado').val();
        $.post('{action_llena_agregar_concepto}', {
            empleado: empleado,
            idUltimoConcepto: idUltimoConcepto
        }, function(data) {
            if (data != "") {
                $("#conceptos_nuevos").append(data);
                //Aumentamos el contador de nuevos conceptos.
                var aumentarId = (new Number($('#contador_new_concepto').val()) + 1);
                $('#contador_new_concepto').attr('value', aumentarId);
            }
        });
    });

    //Eliminamos los conceptos y preguntamos antes
    $("#div_nomina").delegate(".drop_concepto_pdte", "click", function() {
        if (confirm('¿Está seguro que desea eliminar el Concepto de la Nómina? \n \n Sólo cuando creé una nueva Nómina, podrá recuperarlo.')) {
            $("#div_concepto_pdte_" + $(this).attr('id')).remove();
            calcular_total();
        }
    });

    //Eliminamos los conceptos y preguntamos antes
    $("#div_nomina").delegate(".drop_concepto_new", "click", function() {
        if (confirm('¿Está seguro que desea eliminar el Concepto de la Nómina?')) {
            $("#div_concepto_new_" + $(this).attr('id')).remove();
            calcular_total();
        }
    });

    //Cargamos la informacion en el concepto segun el t_concepto
    $("#div_nomina").delegate("#t_concepto_nomina", "change", function() {
        var empleado = $('#empleado').val();
        var tConceptoNomina = $(this).val();
        var idDivConcepto = $(this).parent().parent().parent().parent().attr('id');
        $.post('{action_llena_info_t_concepto}', {
            empleado: empleado,
            tConceptoNomina: tConceptoNomina
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.respuesta == "OK")
            {
                if (obj.detalle_requerido == '1') {
                    $("#" + idDivConcepto).find("#label_detalle").html('<label>Detalles adicionales<em class="required_asterisco">*</em></label>');
                } else {
                    if (obj.detalle_requerido == '0') {
                        $("#" + idDivConcepto).find("#label_detalle").html('<label>Detalles adicionales</label>');
                    }
                }
                $("#" + idDivConcepto).find("#detalle").attr('placeholder', obj.placeholder_detalle);
                if (new Number(obj.valor_unitario) == '0') {
                    $("#" + idDivConcepto).find("#valor_unitario").attr('value', 0);
                    $("#" + idDivConcepto).find("#valor_unitario").removeAttr('readonly');
                } else {
                    $("#" + idDivConcepto).find("#valor_unitario").attr('readonly', 'readonly');
                    $("#" + idDivConcepto).find("#valor_unitario").attr('value', obj.valor_unitario);
                    $("#" + idDivConcepto).find("#valor_unitario").change();
                }
                if (obj.debito_credito == '1') {
                    $("#" + idDivConcepto).find("#label_total_concepto").html('<label>Devengado</label>');
                    $("#" + idDivConcepto).find("#debito_credito").attr('value', 1);
                } else {
                    if (obj.debito_credito == '0') {
                        $("#" + idDivConcepto).find("#label_total_concepto").html('<label>Deducido</label>');
                        $("#" + idDivConcepto).find("#debito_credito").attr('value', 0);
                    }
                }
                if (obj.t_cantidad_dias == '1') {
                    $("#" + idDivConcepto).find("#cantidad").attr('value', 1);
                    $("#" + idDivConcepto).find("#cantidad").attr('readonly', 'readonly');
                } else {
                    if (obj.t_cantidad_dias == '2') {
                        $("#" + idDivConcepto).find("#cantidad").attr('value', $('#dias_nomina').val());
                        $("#" + idDivConcepto).find("#cantidad").attr('readonly', 'readonly');
                    } else {
                        if (obj.t_cantidad_dias == '3') {
                            $("#" + idDivConcepto).find("#cantidad").attr('value', $('#dias_remunerados').val());
                            $("#" + idDivConcepto).find("#cantidad").attr('readonly', 'readonly');
                        } else {
                            if (obj.t_cantidad_dias == '4') {
                                $("#" + idDivConcepto).find("#cantidad").attr('value', 0);
                                $("#" + idDivConcepto).find("#cantidad").removeAttr('readonly');
                            }
                        }
                    }
                }
                $("#" + idDivConcepto).find("#detalle").removeAttr('readonly');
                calcular_total();
            }
        });
    });

    //Llenamos la cajas del responsable
    $.post('{action_llena_caja_responsable}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#tbody_caja_efectivo").html(data);
    });
    //Llenamos las cajas del responsable
    $.post('{action_llena_cuenta_responsable}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#tbody_cuenta_bancaria").html(data);
    });
    //Habilitamos input de efectivo retirado de las cajas
    $("table").delegate("#caja", "click", function() {
        var total_nomina = new Number($('#total_nomina').val().split(",").join(""));
        if ($('#valor_retirado').is('[readonly]')) {
            $('#efectivo_retirado').attr('value', ((total_nomina).toFixed(2)));
        } else {
            var valor_retirado = new Number($('#valor_retirado').val().split(",").join(""));
            $('#efectivo_retirado').attr('value', ((total_nomina - valor_retirado).toFixed(2)));
        }
        $('#efectivo_retirado').change();
        $("#efectivo_retirado").removeAttr("readonly");
    });
    //Habilitamos inputde valor retirado de las cuentas
    $("table").delegate("#cuenta", "click", function() {
        var total_nomina = new Number($('#total_nomina').val().split(",").join(""));
        if ($('#efectivo_retirado').is('[readonly]')) {
            $('#valor_retirado').attr('value', ((total_nomina).toFixed(2)));
        } else {
            var efectivo_retirado = new Number($('#efectivo_retirado').val().split(",").join(""));
            $('#valor_retirado').attr('value', ((total_nomina - efectivo_retirado).toFixed(2)));
        }
        $('#valor_retirado').change();
        $("#valor_retirado").removeAttr("readonly");
    });
    //Calcula el valor contrario al modificar el valor retirado.
    $(".form-group").delegate("#valor_retirado", "blur", function() {
        //Preguntamos si el valor retirado es readonly
        if (!($('#efectivo_retirado').is('[readonly]'))) {
            var total_nomina = new Number($('#total_nomina').val().split(",").join(""));
            var valor_retirado = new Number($('#valor_retirado').val().split(",").join(""));
            $('#efectivo_retirado').attr('value', ((total_nomina - valor_retirado).toFixed(2)));
        }
        $('#efectivo_retirado').change();
    });
    //Calcula el valor contrario al modificar el efectivo retirado.
    $(".form-group").delegate("#efectivo_retirado", "blur", function() {
        if (!($('#valor_retirado').is('[readonly]'))) {
            var total_nomina = new Number($('#total_nomina').val().split(",").join(""));
            var efectivo_retirado = new Number($('#efectivo_retirado').val().split(",").join(""));
            $('#valor_retirado').attr('value', ((total_nomina - efectivo_retirado).toFixed(2)));
        }
        $('#valor_retirado').change();
    });

//Validamos el formulario antes de enviarlo por submit
    //Enviar formulario por ajax
    $('#btn_validar').live('click', function() {
        $('#empleado').removeAttr("disabled");
        $('#periodicidad').removeAttr("disabled");
        $('#fecha_inicio').removeAttr("disabled");
        $('#fecha_fin').removeAttr("disabled");
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
                    $('#empleado').attr('disabled', 'disabled');
                    $('#periodicidad').attr('disabled', 'disabled');
                    $('#fecha_inicio').attr('disabled', 'disabled');
                    $('#fecha_fin').attr('disabled', 'disabled');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p>Hubo un error en la peticion al servidor</p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                $('#empleado').attr('disabled', 'disabled');
                $('#periodicidad').attr('disabled', 'disabled');
                $('#fecha_inicio').attr('disabled', 'disabled');
                $('#fecha_fin').attr('disabled', 'disabled');
            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });


</script>