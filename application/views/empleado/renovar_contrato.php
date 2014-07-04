<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Renovar contrato laboral</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3"> 
                            <div class="form-group">
                                <label>Empleado<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos que pertenecen a cualquiera de sus sedes encargadas.</p>                                
                                <select name="empleado" id="empleado" data-placeholder="Seleccione el empleado a consultar" class="chosen-select form-control exit_caution">
                                    <option value="default"></option>
                                    {empleado}
                                    <option value="{id}+{dni}">{nombre1} {nombre2} {apellido1} {apellido2}</option>
                                    {/empleado}
                                </select>
                            </div>
                            <div id="validacion_inicial">
                            </div>                            
                            <div class="row text-center separar_submit">
                                <button type="button" class="btn btn-default" id="consultar_empleado"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar empleado </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_anular" style="display: none">
                    <div class="row">
                        <legend>Información del empleado</legend>
                        <div class="overflow_tabla">
                            <table class="table table-hover tabla-matriculas">
                                <thead>
                                    <tr>
                                        <th class="text-center"># Mátricula</th>
                                        <th class="text-center">Valor total</th>
                                        <th class="text-center">Caja</th>
                                        <th class="text-center">Efectivo en caja</th>
                                        <th class="text-center">Cuenta</th>
                                        <th class="text-center">valor cuenta</th>
                                        <th class="text-center">responsable</th>
                                        <th class="text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_info_empleado">
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Fecha Inicial<em class="required_asterisco">*</em></label>
                                    <div class="input-group">
                                        <input name="fecha_inicio" id="fecha_inicio" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha inicial de la Ausencia">
                                        <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Fecha Final<em class="required_asterisco">*</em></label>
                                    <div class="input-group">
                                        <input name="fecha_fin" id="fecha_fin" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha final de la Ausencia">
                                        <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tipo de Ausencia<em class="required_asterisco">*</em></label>
                            <select name="t_ausencia" id="t_ausencia" class="form-control exit_caution">
                                <option value="default">Seleccione Tipo de Ausencia</option>
                                {t_ausencia}
                                <option value="{id}">{tipo} - ({salarial})</option>
                                {/t_ausencia}
                            </select>
                        </div>                        
                        <div class="form-group">
                            <label>Observación<em class="required_asterisco">*</em></label>
                            <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Motivo de la anulación, quien autorizó, etc..."  style="max-width:100%;"></textarea>
                        </div>
                        <div id="validacion_alert">
                        </div>                        
                        <div class="form-group separar_submit">
                            <input type="hidden" id="action_validar" value={action_validar} />
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                <button id="btn_validar" class="btn btn-success">Renovar contrato laboral</button>  
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div>   
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Llenamos la informacion de las matriculas y los pagos.
    $("form").delegate("#consultar_empleado", "click", function() {
        var empleado = $('#empleado').val();
        if (empleado != "default") {
            $.post('{action_validar_empleado_renovar_contrato}', {
                prefijo: prefijo,
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#tbody_info_empleado").html(obj.filasTabla);
                    $("#div_anular").css("display", "block");
                    $('#prefijo').attr('disabled', 'disabled');
                    $('#id').attr('readonly', 'readonly');
                    $('#consultar_empleado').attr('disabled', 'disabled');
                    //Actualizamo la informacion del titular en a nombre de 
                    $("#div_warning").remove();
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de consultar, seleccione el empleado al que se le renovará el contrato laboral.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });

    //Validamos el formulario antes de enviarlo por submit
    //Enviar formulario por ajax
    $('#btn_validar').live('click', function() {
        $('#prefijo').removeAttr("disabled");
        $.ajax({
            type: "POST",
            url: $("#action_validar").attr("value"),
            cache: false,
            data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $('#prefijo').attr('disabled', 'disabled');
                    $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                    $("#div_alert").html(data);
                    $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $('#prefijo').attr('disabled', 'disabled');
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p>Hubo un error en la peticion al servidor</p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });


</script>