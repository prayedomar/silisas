<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Anular transferencia intersede</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <legend>Transferencia intersede que se anulará</legend>
                            <p class="help-block"><B>> </B>Sólo aparecerán los prefijos de cada una de sus sedes autorizadas.</p>  
                            <div class="row separar_div">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Prefijo de sede<em class="required_asterisco">*</em></label>
                                        <select name="prefijo" id="prefijo" class="form-control" value="flst">
                                            <option value="default">Seleccione prefijo</option>
                                            {sede}
                                            <option value="{prefijo_trans}">{prefijo_trans}</option>
                                            {/sede}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Consecutivo<em class="required_asterisco">*</em></label>
                                        <input name="id" id="id" type="text" class="form-control numerico" placeholder="Consecutivo" maxlength="13" <?php if (isset($id)) { ?> value="<?php echo $id ?>" <?php } ?>>
                                    </div>
                                </div>
                            </div>
                            <div id="validacion_inicial">
                            </div>                            
                            <div class="row text-center separar_submit">
                                <button type="button" class="btn btn-default" id="consultar_transaccion"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Seleccionar otra  transferencia </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_anular" style="display: none">
                    <div class="row">
                        <legend>Información de la transferencia intersede</legend>
                        <div class="overflow_tabla">
                            <table class="table table-hover tabla-matriculas">
                                <thead>
                                    <tr>
                                        <th class="text-center">Valor total</th>
                                        <th class="text-center">Información de origen</th>
                                        <th class="text-center">Información de destino</th>
                                        <th class="text-center">Observación</th>
                                        <th class="text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_info_transaccion">
                                </tbody>
                            </table>
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
                                <button id="btn_validar" class="btn btn-success">Anular transferencia intersede</button>  
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
    //Llenamos la informacion de las matriculas y los pagos.
    $("form").delegate("#consultar_transaccion", "click", function() {
        var prefijo = $('#prefijo').val();
        var id = $('#id').val();
        if ((prefijo != "default") && (id != "")) {
            $.post('{action_validar_transaccion_anular}', {
                prefijo: prefijo,
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#tbody_info_transaccion").html(obj.filasTabla);
                    $("#div_anular").css("display", "block");
                    $('#prefijo').attr('disabled', 'disabled');
                    $('#id').attr('readonly', 'readonly');
                    $('#consultar_transaccion').attr('disabled', 'disabled');
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
            $("#div_warning").html("<p><strong><center>Antes de consultar, ingrese el prefijo y el consecutivo de la retención que se anulará.</center></strong></p>");
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