<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail"> 
            <div class="row">
                <legend>Modificar sedes de un empleado</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="form-group separar_div">
                        <label>Empleado<em class="required_asterisco">*</em></label>
                        <select name="empleado" id="empleado_sedes" data-placeholder="Seleccione Empleado a modificar" class="chosen-select form-control">
                            <option value="default"></option>
                            {empleado}
                            <option value="{id}-{dni}">{nombre1} {nombre2} {apellido1} {apellido2}</option>
                            {/empleado}
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="overflow_tabla">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sede Principal Actual</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_sede_ppal">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="overflow_tabla">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sedes Secundarias Actuales</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_sede_secundaria">
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
<!--Modal Editar Sede Principal-->
<div class="modal" id="modalSedePpal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Editar Sede Principal</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_editar_ppal}" id="form_edit_sede_ppal">
                    <div class="form-group">
                        <label>Sede Principal</label>
                        <select name="sede_ppal" id="sede_ppal" class="form-control input_modal_1">
                        </select>
                        <p class="help-block"><B>> </B>Sólo aparecerán las sedes faltantes del empleado y cada una de sus sedes autorizadas.</p>
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea name="observacion" id="observacion" class="form-control input_modal_1" rows="3" maxlength="250" placeholder="Observación..."style="max-width:100%;"></textarea>
                    </div>
                    <input type="hidden" name="id_responsable" value={id_responsable} />
                    <input type="hidden" name="dni_responsable" value={dni_responsable} />                    
                    <input type="hidden" name="empleado" id="empleado_selected"/>
                </form>
                <div id="alert_modal_1">
                </div>
            </div>
            <div class="modal-footer">
                <button id="editar_sede_ppal" class="btn btn-success">Actualizar Sede Principal</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"  id="cerrar_modal_1">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal eliminar sede secundaria-->
<div class="modal" id="modalEditarSedeSecundaria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Eliminar Sede Secundaria</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_anular_secundaria}" id="form_anular_sede_secundaria">
                    <center><p>¿Está seguro que desea eliminar la sede secundaria?</p></center>
                    <input type="hidden" name="id_responsable" value={id_responsable} />
                    <input type="hidden" name="dni_responsable" value={dni_responsable} />
                    <input type="hidden" name="id_empleado_sede" id="id_empleado_sede"/>
                </form>
                <div id="alert_modal_2">
                </div>
            </div>
            <div class="modal-footer">
                <button id="anular_sede_secundaria" class="btn btn-success">Eliminar Sede Secundaria</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cerrar_modal_2">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Agregar Sede Secundaria-->
<div class="modal" id="modalAgregarSedeSecundaria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Agregar Sedes Secundarias</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_agregar_secundaria}" id="form_agregar_sede_secundaria">
                    <div id="div_checkbox_secundarias">
                    </div>
                    <input type="hidden" name="id_responsable" value={id_responsable} />
                    <input type="hidden" name="dni_responsable" value={dni_responsable} />
                    <input type="hidden" name="empleado" id="empleado_select"/>
                </form>
                <div id="alert_modal_3">
                </div>
            </div>
            <div class="modal-footer">
                <button id="agregar_sede_secundaria" class="btn btn-success">Agregar Sedes Secundarias</button>
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
                <div class="alert alert-info" id="msn_trans_error">Ya se agregaron todas las sedes secundarias vigentes para éste empleado.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Cargar sedes de un empleado
    $("#empleado_sedes").live("change", function() {
        empleado = $('#empleado_sedes').val();
        $.post('{action_llena_empleado_sede_ppal}', {
            empleado: empleado
        }, function(data) {
            $("#tbody_sede_ppal").html(data);
        });
        $.post('{action_llena_empleado_sede_secundaria}', {
            empleado: empleado
        }, function(data) {
            $("#tbody_sede_secundaria").html(data);
            $("#tbody_sede_secundaria").append('<tr><td class="text-center" colspan="2"><button class="btn btn-success btn-sm agregar_sede">Agregar Sedes Secundarias</button></td></tr>');
        });
    });

    //Cerrar mensaje de notificacion al toca input modal
    $('.input_modal_1').live('click', function() {
        $("#alert_modal_1").removeAttr('class');
        $("#alert_modal_1  > *").remove();
    });

    //Cerrar mensaje de notificacion al toca input modal
    $('.input_modal_3').live('click', function() {
        $("#alert_modal_3").removeAttr('class')
        $("#alert_modal_3  > *").remove();
    });

    //Activar Modal de Agregar sede secundaria
    $('.agregar_sede').live('click', function() {
        empleado = $('#empleado_sedes').val();
        $.post('{action_llena_checkbox_secundarias}', {
            empleado: empleado,
            idResposable: '{id_responsable}',
            dniResposable: '{dni_responsable}'
        }, function(data) {
            $("#div_checkbox_secundarias").html(data);
            if ($('#div_checkbox_secundarias').is(':empty')) {
                $("#trans_error").modal('show');
            } else {
                $("#div_checkbox_secundarias").append('<p class="help-block"><B>> </B>Sólo aparecerán las sedes faltantes del empleado y cada una de sus sedes autorizadas.</p>');
                $("#empleado_select").attr('value', $(empleado_sedes).val());
                $("#alert_modal_3").removeAttr('class');
                $("#alert_modal_3  > *").remove();
                $("#modalAgregarSedeSecundaria").modal('show');
            }
        });
    });

    //Agregar sede Secundaria
    $('#agregar_sede_secundaria').live('click', function() {
        $.ajax({
            type: "POST",
            url: $("#form_agregar_sede_secundaria").attr("action"),
            cache: false,
            data: $("#form_agregar_sede_secundaria").serialize(), // Adjuntar los campos del formulario enviado.
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
                        $("#empleado_sedes").change();
                        $("#cerrar_modal_3").click();
                        $("#trans_success").modal('show');
                        $("#div_checkbox_secundarias  > *").remove();
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

    //Activar Modal de editar sede ppal
    $('.editar_sede').live('click', function() {
        sede_ppal = $(this).attr('id');
        $.post('{action_llena_sede_ppal_faltante}', {
            sede_ppal: sede_ppal,
            idResposable: '{id_responsable}',
            dniResposable: '{dni_responsable}'
        }, function(data) {
            $("#sede_ppal").html(data);
            $("#sede_ppal").prepend('<option value="default" selected>Seleccione Nueva Sede Principal</option>');
        });
        $("#observacion").attr('value', "");
        $("#alert_modal_1").removeAttr('class')
        $("#alert_modal_1  > *").remove();
        $("#empleado_selected").attr('value', $('#empleado_sedes').val());
        $("#modalSedePpal").modal('show');
    });

    //Editar sede principal
    $('#editar_sede_ppal').live('click', function() {
        //Guardamos en un input del form el empleado escogido, para q se valla por serialize
        $.ajax({
            type: "POST",
            url: $("#form_edit_sede_ppal").attr("action"),
            cache: false,
            data: $("#form_edit_sede_ppal").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                var obj = JSON.parse(data);
                if (obj.respuesta == 'error')
                {
                    $("#alert_modal_1").attr('class', 'alert alert-danger');
                    $("#alert_modal_1").html(obj.mensaje);
                    return false;
                } else {
                    if (obj.respuesta == "OK")
                    {
                        $("#empleado_sedes").change();
                        $("#cerrar_modal_1").click();
                        $("#trans_success").modal('show');
                        $("#sede_ppal > *").remove();
                        return false;
                    }
                }
                return false;
            },
            error: function(data) {
                $("#alert_modal_1").attr('class', 'alert alert-danger');
                $("#alert_modal_1").html('<p>Hubo un error en la peticion al servidor</p>');
            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });

    //Activar Modal de anular sede secundaria
    $('.anular_sede').live('click', function() {
        $("#alert_modal_2").removeAttr('class')
        $("#alert_modal_2  > *").remove();
        $("#id_empleado_sede").attr('value', $(this).attr('id'));
        $("#modalEditarSedeSecundaria").modal('show');
    });
    //Anular sede Secundaria
    $('#anular_sede_secundaria').live('click', function() {
        //Guardamos en un input del form el empleado escogido, para q se valla por serialize
        $.ajax({
            type: "POST",
            url: $("#form_anular_sede_secundaria").attr("action"),
            cache: false,
            data: $("#form_anular_sede_secundaria").serialize(), // Adjuntar los campos del formulario enviado.
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
                        $("#empleado_sedes").change();
                        $("#cerrar_modal_2").click();
                        $("#trans_success").modal('show');
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