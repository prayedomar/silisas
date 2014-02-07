<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Modificar cargo y jefe de empleados de RRPP</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                        <div class="form-group separar_div">
                            <label>Empleado<em class="required_asterisco">*</em></label>
                            <p class="help-block"><B>> </B>Sólo aparecerán los empleados de RRPP activos que pertenecen a sus sedes encargadas.</p>
                            <select name="empleado" id="empleado" data-placeholder="Seleccione Empleado a Modificar" class="form-control">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row separar_div">
                    <div class="col-xs-6">
                        <div class="overflow_tabla">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Cargo Actual</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_cargo">
                                </tbody>
                            </table>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="overflow_tabla">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Jefe Actual</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_jefe">
                                </tbody>
                            </table>   
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
</div>
<!--Modal Modificar Cargo Empleado-->
<div class="modal" id="modalEditarCargo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Modificar: Cargo de un Empleado</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_editar_cargo}" id="form_edit_cargo">
                    <div class="form-group">
                        <label>Nuevo Cargo</label>
                        <p class="help-block"><B>> </B>Sólo aperecerán los cargos de RRPP.</p>
                        <select name="cargo" id="cargo" class="form-control input_modal_1">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea name="observacion" id="observacion_1" class="form-control exit_caution alfanumerico" rows="2" maxlength="255" placeholder="Observación..."  style="max-width:100%;"></textarea>
                    </div>                    
                    <div class="form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" name="checkbox_placa" id="checkbox_placa" value="' . $fila->id . '"/><h4 class="h_negrita">Solicitar placa de ascenso</h4><p class="help-block">Con ésta opción realiza el pedido automático de la placa de reconocimiento para el empleado que está modificando.</p></label>
                        </div>
                    </div>
                    <input type="hidden" name="id_responsable" value={id_responsable} />
                    <input type="hidden" name="dni_responsable" value={dni_responsable} />                    
                    <input type="hidden" name="empleado" id="empleado_selected"/>
                    <input type="hidden" name="genero_cargo" id="genero_cargo"/>
                </form>
                <div id="alert_modal_1">
                </div>
            </div>
            <div class="modal-footer">
                <button id="editar_cargo_laboral" class="btn btn-success">Actualizar Cargo Laboral</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"  id="cerrar_modal_1">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal modificar Jefe de Empleado-->
<div class="modal" id="modalEditarJefe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Modificar: Jefe de un Empleado</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{action_editar_jefe}" id="form_edit_jefe">
                    <div class="form-group">
                        <label>Nuevo Jefe</label>
                        <p class="help-block"><B>> </B>Seleccione el jefe más inmediato que ganará comisiones por el empleado.</p>                                                        
                        <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos de RRPP, que tienen un nivel jerárquico superior.</p>
                        <select name="jefe" id="jefe" class="form-control input_modal_2">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea name="observacion" id="observacion_2" class="form-control exit_caution alfanumerico" rows="2" maxlength="255" placeholder="Observación..."  style="max-width:100%;"></textarea>
                    </div>                    
                    <input type="hidden" name="id_responsable" value={id_responsable} />
                    <input type="hidden" name="dni_responsable" value={dni_responsable} />                    
                    <input type="hidden" name="empleado" id="empleado_selected"/>
                    <input type="hidden" name="empleado_jefe" id="empleado_jefe"/>
                </form>
                <div id="alert_modal_2">
                </div>
            </div>
            <div class="modal-footer">
                <button id="editar_jefe" class="btn btn-success">Actualizar Nuevo Jefe</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"  id="cerrar_modal_2">Cerrar</button>
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
<script type="text/javascript">
    //Cargar los empleados
    $.post('{action_llena_empleado_rrpp_sedes_responsable}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#empleado").html(data);
        $("#empleado").prepend('<option value="default" selected>Seleccione Empleado</option>');
    });

    //Cargar cargo y jefe de un empleado
    $("#empleado").live("change", function() {
        empleado = $('#empleado').val();
        $.post("{action_llena_cargo_empleado}", {
            empleado: empleado
        }, function(data) {
            $("#tbody_cargo").html(data);
        });
        $.post("{action_llena_jefe_empleado}", {
            empleado: empleado
        }, function(data) {
            $("#tbody_jefe").html(data);
        });
    });

    //Cerrar mensaje de notificacion al toca input modal
    $('.input_modal_1').live('click', function() {
        $("#alert_modal_1").removeAttr('class');
        $("#alert_modal_1  > *").remove();
    });

    //Cerrar mensaje de notificacion al toca input modal
    $('.input_modal_2').live('click', function() {
        $("#alert_modal_2").removeAttr('class')
        $("#alert_modal_2  > *").remove();
    });

    //Activar Modal de editar Jefe
    $('.editar_jefe').live('click', function() {
        empleado_jefe = $(this).attr('id');
        $.post("{action_llena_jefe_faltante}", {
            empleado_jefe: empleado_jefe
        }, function(data) {
            $("#jefe").html(data);
            $("#jefe").prepend('<option value="default" selected>Seleccione Nuevo Jefe</option>');
        });
        $("#empleado_jefe").attr('value', $(this).attr('id'));
        $("#observacion_2").attr('value', "");
        $("#alert_modal_2").removeAttr('class');
        $("#alert_modal_2  > *").remove();
        $("#empleado_selected").attr('value', $('#empleado_sedes').val());
        $("#modalEditarJefe").modal('show');
    });

    //Editar Jefe
    $('#editar_jefe').live('click', function() {
        $.ajax({
            type: "POST",
            url: $("#form_edit_jefe").attr("action"),
            cache: false,
            data: $("#form_edit_jefe").serialize(), // Adjuntar los campos del formulario enviado.
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
                        $("#empleado").change();
                        $("#cerrar_modal_2").click();
                        $("#trans_success").modal('show');
                        $("#jefe > *").remove();
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

    //Activar Modal de Editar cargo empleado
    $('.editar_cargo').live('click', function() {
        genero_cargo = $(this).attr('id');
        $.post('{action_llena_cargo_genero_cargo_old}', {
            genero_cargo: genero_cargo
        }, function(data) {
            $("#cargo").html(data);
            $("#cargo").prepend('<option value="default" selected>Seleccione Nuevo Cargo</option>');
        });
        $("#observacion_1").attr('value', "");
        $("#checkbox_placa").attr('checked', false);
        $("#empleado_selected").attr('value', $('#empleado').val());
        $("#genero_cargo").attr('value', $(this).attr('id'));
        $("#alert_modal_1").removeAttr('class');
        $("#alert_modal_1  > *").remove();
        $("#modalEditarCargo").modal('show');
    });
    //Editar cargo empleado
    $('#editar_cargo_laboral').live('click', function() {
        $.ajax({
            type: "POST",
            url: $("#form_edit_cargo").attr("action"),
            cache: false,
            data: $("#form_edit_cargo").serialize(), // Adjuntar los campos del formulario enviado.
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
                        $("#empleado").change();
                        $("#cerrar_modal_1").click();
                        $("#trans_success").modal('show');
                        $("#cargo > *").remove();
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


</script>