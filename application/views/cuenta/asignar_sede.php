<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail"> 
            <div class="row">
                <legend>Autorizar y consultar, cuentas bancarias a sedes</legend>
                <p class="help-block"><B>> </B>Esta función es importante para garantizar que los empleados a los que se le autorize el uso de una cuenta, pertenezcan a cualquiera de las sedes habilitadas para utilizar dicha cuenta.</p>                            
                <p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
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
                        <div class="overflow_tabla separar_div">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sedes Autorizadas para utilizar la Cuenta</th>
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
                        <a href="{base_url}" class="btn btn-info" role="button"> Volver a la pagina principal </a>
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
                <h3 class="modal-title" id="myModalLabel">Anular Autorización de Sede para utilizar una Cuenta</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_anular_sede_cuenta}" id="form_anular_sede_cuenta">
                    <center><p>¿Está seguro que desea desautorizar la cuenta seleccionada para ésta sede?</p>
                        <p>Automáticamente se desautorizarán todos los empleados autorizados para utilizar la cuenta,
                            cuya sede principal coincida con ésta sede.</p></center>
                    <input type="hidden" name="id_responsable" value={id_responsable} />
                    <input type="hidden" name="dni_responsable" value={dni_responsable} />
                    <input type="hidden" name="sede_cuenta" id="sede_cuenta"/>
                </form>
                <div id="alert_modal_2">
                </div>
            </div>
            <div class="modal-footer">
                <button id="anular_sedes_cuenta" class="btn btn-success">Desautorizar Sede</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cerrar_modal_2">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Agregar Sede Secundaria-->
<div class="modal" id="modalAgregarSedeCuenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Autorizar Cuenta para las Sedes</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_agregar_sede_cuenta}" id="form_autorizar_cuenta_sede">
                    <div id="div_checkbox_sedes_cuenta">
                    </div>
                    <input type="hidden" name="id_responsable" value={id_responsable} />
                    <input type="hidden" name="dni_responsable" value={dni_responsable} />
                    <input type="hidden" name="cuenta" id="cuenta_select"/>
                </form>
                <div id="alert_modal_3">
                </div>
            </div>
            <div class="modal-footer">
                <button id="autorizar_sedes_cuenta" class="btn btn-success">Autorizar Cuenta para las Sedes</button>
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
                <h3 class="modal-title" id="myModalLabel">No hay más sedes disponibles</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" id="msn_trans_error">Ya se autorizaron todas las sedes disponibles para ésta cuenta.</div>
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

    //Cargar sedes de una cuenta
    $("table").delegate("#cuenta", "click", function() {
        cuenta = $("input[name='cuenta']:checked").val();
        $.post('{action_llena_sedes_cuenta}', {
            cuenta: cuenta
        }, function(data) {
            $("#tbody_sedes_cuenta").html(data);
            $("#tbody_sedes_cuenta").append('<tr><td class="text-center" colspan="2"><button class="btn btn-success btn-sm agregar_sede_cuenta">Autorizar Sedes para la Cuenta</button></td></tr>');
        });
    });

    //Cerrar mensaje de notificacion al toca input modal
    $('.input_modal_3').live('click', function() {
        $("#alert_modal_3").removeAttr('class')
        $("#alert_modal_3  > *").remove();
    });
    
    //Activar Modal de Agregar sede secundaria
    $('.agregar_sede_cuenta').live('click', function() {
        cuenta = $("input[name='cuenta']:checked").val();
        $.post("{action_llena_checkbox_sedes_cuenta}", {
            cuenta: cuenta,
            idResposable: '{id_responsable}',
            dniResposable: '{dni_responsable}'
        }, function(data) {
            $("#div_checkbox_sedes_cuenta").html(data);
            if ($('#div_checkbox_sedes_cuenta').is(':empty')) {
                $("#trans_error").modal('show');
            } else {
                $("#div_checkbox_sedes_cuenta").append('<p class="help-block"><B>> </B>Sólo aparecerán las sedes faltantes de la cuenta y cada una de sus sedes autorizadas.</p>');
                $("#cuenta_select").attr('value', $("input[name='cuenta']:checked").val());
                $("#alert_modal_3").removeAttr('class');
                $("#alert_modal_3  > *").remove();
                $("#modalAgregarSedeCuenta").modal('show')
            }
        });
    });

    //Autorizar sedes 
    $('#autorizar_sedes_cuenta').live('click', function() {
        $.ajax({
            type: "POST",
            url: $("#form_autorizar_cuenta_sede").attr("action"),
            cache: false,
            data: $("#form_autorizar_cuenta_sede").serialize(), // Adjuntar los campos del formulario enviado.
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
                        $("#div_checkbox_sedes_cuenta  > *").remove();
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
    $('.anular_sede_cuenta').live('click', function() {
        $("#alert_modal_2").removeAttr('class')
        $("#alert_modal_2  > *").remove();
        $("#sede_cuenta").attr('value', $(this).attr('id'));
        $("#modalAnularSedeCuenta").modal('show')
    });
    //Anular sede Secundaria
    $('#anular_sedes_cuenta').live('click', function() {
        //Guardamos en un input del form el empleado escogido, para q se valla por serialize
        $.ajax({
            type: "POST",
            url: $("#form_anular_sede_cuenta").attr("action"),
            cache: false,
            data: $("#form_anular_sede_cuenta").serialize(), // Adjuntar los campos del formulario enviado.
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