<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail"> 
            <div class="row">
                <legend>Autorizar cuentas bancaria a empleados para <U>INGRESAR DINERO</U></legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="col-xs-12">
                    <div class="overflow_tabla separar_div">
                        <label>Cuenta Bancaria<em class="required_asterisco">*</em></label>
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
                </div>
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                        <div class="overflow_tabla">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Empleados Autorizados para <u>ingresar dinero</u> a la Cuenta</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_sedes_cuenta">
                                </tbody>
                            </table>   
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <hr>
                <div class="col-xs-6 col-xs-offset-3">
                    <center>
                        <a href="<?= base_url() ?>"class="btn btn-info" role="button"> Volver a la pagina principal </a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal eliminar sede secundaria-->
<div class="modal" id="modalAnularSedeCuenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Eliminar Autorización de Cuenta para Empleado</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_anular_empleado_cuenta}" id="form_anular_empleado_cuenta">
                    <center><p>¿Está seguro que desea desautorizar la cuenta seleccionada para éste empleado?</p></center>
                        <input type="hidden" name="id_responsable" value={id_responsable} />
                        <input type="hidden" name="dni_responsable" value={dni_responsable} />
                        <input type="hidden" name="empleado_cuenta" id="empleado_cuenta"/>
                </form>
                <div id="alert_modal_2">
                </div>
            </div>
            <div class="modal-footer">
                <button id="anular_empleados_cuenta" class="btn btn-success">Desautorizar Empleado</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cerrar_modal_2">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Agregar Sede Secundaria-->
<div class="modal" id="modalAgregarEmpleadoCuenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Autorizar Cuenta para Empleados</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_agregar_empleado_cuenta}" id="form_autorizar_cuenta_empleado">
                    <div id="div_checkbox_empleados_cuenta">
                    </div>
                    <input type="hidden" name="id_responsable" value={id_responsable} />
                    <input type="hidden" name="dni_responsable" value={dni_responsable} />
                    <input type="hidden" name="cuenta" id="cuenta_select"/>
                </form>
                <div id="alert_modal_3">
                </div>
            </div>
            <div class="modal-footer">
                <button id="autorizar_empleados_cuenta" class="btn btn-success">Autorizar Cuenta para Empleados</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cerrar_modal_3">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Succesfull-->
<div class="modal" id="trans_success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Transacción Exitosa</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">La transacción se ha ejecutado correctamente en la base de datos.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="trans_error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">No hay más empleados disponibles</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" id="msn_trans_error">Ya se autorizaron todas los empleados disponibles para ésta cuenta.</div>
                <p class="help-block"><B>Nota: </B>Sólo aparecerán los empleados activos cuya sede principal coincida con cualquiera de las sedes autorizadas para esta cuenta y que pertenecen a cualquiera de sus sedes encargadas.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $.post('{action_llena_cuenta_bancaria}', {},
            function(data) {
                $("#tbody_cuenta_bancaria").html(data);
            });

    //Cargar Empleados autorizados de una cuenta
    $("table").delegate("#cuenta", "click", function() {
        cuenta = $("input[name='cuenta']:checked").val();
        $.post('{action_llena_empleados_cuenta}', {
            cuenta: cuenta
        }, function(data) {
            $("#tbody_sedes_cuenta").html(data);
            $("#tbody_sedes_cuenta").append('<tr><td class="text-center" colspan="2"><button class="btn btn-success btn-sm agregar_empleado_cuenta">Autorizar Cuenta para Empleados</button></td></tr>');
        });
    });

    //Cerrar mensaje de notificacion al toca input modal
    $('.input_modal_3').live('click', function() {
        $("#alert_modal_3").removeAttr('class')
        $("#alert_modal_3  > *").remove();
    });
    
    //Activar Modal de Agregar sede
    $('.agregar_empleado_cuenta').live('click', function() {
        cuenta = $("input[name='cuenta']:checked").val();
        $.post("{action_llena_checkbox_empleados_cuenta}", {
            cuenta: cuenta,
            idResposable: '{id_responsable}',
            dniResposable: '{dni_responsable}'
        }, function(data) {
            $("#div_checkbox_empleados_cuenta").html(data);
            if ($('#div_checkbox_empleados_cuenta').is(':empty')) {
                $("#trans_error").modal('show');
            } else {
                $("#div_checkbox_empleados_cuenta").append('<p class="help-block"><B>> </B>Sólo aparecerán los empleados activos cuya sede principal coincida con cualquiera de las sedes autorizadas para esta cuenta y que pertenecen a cualquiera de sus sedes encargadas.</p>');
                $("#cuenta_select").attr('value', $("input[name='cuenta']:checked").val());
                $("#alert_modal_3").removeAttr('class');
                $("#alert_modal_3  > *").remove();
                $("#modalAgregarEmpleadoCuenta").modal('show')
            }
        });
    });

    //Autorizar sedes 
    $('#autorizar_empleados_cuenta').live('click', function() {
        $.ajax({
            type: "POST",
            url: $("#form_autorizar_cuenta_empleado").attr("action"),
            cache: false,
            data: $("#form_autorizar_cuenta_empleado").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                var obj = JSON.parse(data);
                if (obj.respuesta == 'error')
                {
                    $("#alert_modal_3").attr('class', 'alert alert-danger');
                    $("#alert_modal_3").html(obj.mensaje);
                    return false;
                } else {
                    if (obj.respuesta == "OK")
                    {
                        $("#cuenta").change();
                        $("#cerrar_modal_3").click();
                        $("#trans_success").modal('show');
                        $("#div_checkbox_empleados_cuenta  > *").remove();
                        $("input[name='cuenta']:checked").click();
                        return false;
                    }
                }
                return false;
            },
            error: function(data) {
                $("#alert_modal_3").attr('class', 'alert alert-danger');
                $("#alert_modal_3").html('<p>Hubo un error en la peticion al servidor</p>');
            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });

    //Activar Modal de anular sede
    $('.anular_empleado_cuenta').live('click', function() {
        $("#alert_modal_2").removeAttr('class')
        $("#alert_modal_2  > *").remove();
        $("#empleado_cuenta").attr('value', $(this).attr('id'));
        $("#modalAnularSedeCuenta").modal('show')
    });
    //Anular sede Secundaria
    $('#anular_empleados_cuenta').live('click', function() {
        $.ajax({
            type: "POST",
            url: $("#form_anular_empleado_cuenta").attr("action"),
            cache: false,
            data: $("#form_anular_empleado_cuenta").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                var obj = JSON.parse(data);
                if (obj.respuesta == 'error')
                {
                    $("#alert_modal_2").attr('class', 'alert alert-danger');
                    $("#alert_modal_2").html(obj.mensaje);
                    return false;
                } else {
                    if (obj.respuesta == "OK")
                    {
                        $("#cuenta").change();
                        $("#cerrar_modal_2").click();
                        $("#trans_success").modal('show');
                        $("input[name='cuenta']:checked").click();
                        return false;
                    }
                }
                return false;
            },
            error: function(data) {
                $("#alert_modal_2").attr('class', 'alert alert-danger');
                $("#alert_modal_2").html('<p>Hubo un error en la peticion al servidor</p>');
            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });

</script>