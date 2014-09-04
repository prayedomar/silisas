<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Anular abono a adelanto de nómina</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <legend>Abono a adelanto de nómina que se anulará</legend>
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
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Seleccionar otro Abono a adelanto </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_anular" style="display: none">
                    <div class="row">
                        <legend>Información del abono a adelanto de nómina</legend>
                        <div class="overflow_tabla">
                            <table class="table table-hover tabla-matriculas">
                                <thead>
                                    <tr>
                                        <th class="text-center">Adelanto inicial</th>                                        
                                        <th class="text-center">Beneficiario adelanto</th>
                                        <th class="text-center">Total éste abono</th>
                                        <th class="text-center">Caja</th>
                                        <th class="text-center">Efectivo en caja</th>
                                        <th class="text-center">Cuenta</th>
                                        <th class="text-center">valor cuenta</th>
                                        <th class="text-center">responsable</th>
                                        <th class="text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_info_transaccion">
                                </tbody>
                            </table>
                        </div>
                        <?php if (isset($cod_required)) { ?>
                            <div class="row">
                                <div class="col-xs-4 col-xs-offset-4">
                                    <div class="form-group">
                                        <label>Código de autorización<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><b>> </b>Éste código de autorización, lo debe solicitar a los directivos encargados.</p>
                                        <input name="cod_autorizacion" id="cod_autorizacion" type="text" class="form-control numerico" placeholder="Código de autorización" maxlength="13" <?php if (isset($id)) { ?> value="<?php echo $id ?>" <?php } ?>>
                                    </div> 
                                </div>
                            </div>       
                        <?php } ?>
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
                                <button id="btn_validar" class="btn btn-success">Anular abono a adelanto de nómina</button>  
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
            $("#div_warning").html("<p><strong><center>Antes de consultar, ingrese el prefijo y el consecutivo del abono a adelanto de nómina que se anulará.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });

    //Validamos el formulario antes de enviarlo por submit
    //Enviar formulario por ajax
    $('#btn_validar').live('click', function() {
        //desabilitamos el boton de enviar para evitar que lo apreten varias veces y que hagan la peticion al servidor 2 veces, ya me pasó una vez.
        $('#btn_validar').attr('disabled', 'disabled');    
        $('#prefijo').removeAttr("disabled");
        $.ajax({
            type: "POST",
            url: $("#action_validar").attr("value"),
            cache: false,
            data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $('#btn_validar').removeAttr("disabled");
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
                $('#btn_validar').removeAttr("disabled");
                $('#prefijo').attr('disabled', 'disabled');
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p><strong>Hubo un error en la peticion al servidor. Verifique su conexion a internet.</strong></p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });


</script>