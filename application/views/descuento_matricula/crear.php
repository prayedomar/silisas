<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Crear descuento especial de matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-2">
                                    <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                    <input name="id" id="id" type="text" class="form-control numerico" placeholder="Número de Contrato Físico" maxlength="13" <?php if (isset($id)) { ?> value="<?php echo $id ?>" <?php } ?>>
                                </div>
                            </div><br>
                            <div id="validacion_inicial">
                            </div>                            
                            <div class="row text-center separar_submit">
                                <button type="button" class="btn btn-default" id="consultar_matricula"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar matrícula </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_matriculas" style="display: none">
                    <div class="row">
                        <legend>Información de la matrícula</legend>
                        <div class="overflow_tabla separar_div">
                            <table class="table table-hover tabla-matriculas">
                                <thead>
                                    <tr>
                                        <th class="text-center">Titular</th>
                                        <th class="text-center">Plan</th>
                                        <th class="text-center">Valor total</th>
                                        <th class="text-center">descuentos</th>
                                        <th class="text-center">Saldo</th>
                                        <th class="text-center">Sede Origen</th>
                                        <th class="text-center">Fecha inicial</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_matricula_vigente">
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <?php if (isset($cod_required)) { ?>
                                    <div class="form-group">
                                        <label>Código de autorización<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><b>> </b>Éste código de autorización, lo debe solicitar a los directivos encargados.</p>
                                        <input name="cod_autorizacion" id="cod_autorizacion" type="text" class="form-control numerico" placeholder="Código de autorización" maxlength="13" <?php if (isset($id)) { ?> value="<?php echo $id ?>" <?php } ?>>
                                    </div> 
                                <?php } ?>
                                <div class="form-group">
                                    <label>Valor del descuento<em class="required_asterisco">*</em></label>                            
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" name="total" id="total" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Observación<em class="required_asterisco">*</em></label>
                            <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Motivo del descuento, quien autorizó, etc..."  style="max-width:100%;"></textarea>
                        </div>
                        <div id="validacion_alert">
                        </div>                        
                        <div class="form-group separar_submit">
                            <input type="hidden" id="action_validar" value={action_validar} />
                            <input type="hidden" name="id_responsable" value={id_responsable} />
                            <input type="hidden" name="dni_responsable" value={dni_responsable} />
                            <!--aqui hiran los datos ocultos-->
                            <input type="hidden" name="saldo" id="saldo" value="0" />
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                <button id="botonValidar" class="btn btn-success">Crear descuento de matrícula</button>  
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
    $("form").delegate("#consultar_matricula", "click", function() {
        var id = $('#id').val();
        if (id != "") {
            $.post('{action_validar_matricula}', {
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#tbody_matricula_vigente").html(obj.filasTabla);
                    $("#div_matriculas").css("display", "block");
                    $('#id').attr('readonly', 'readonly');
                    $('#consultar_matricula').attr('disabled', 'disabled');
                    $("#saldo").attr("value", obj.saldo);
                    $("#div_warning").remove();
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de consultar, ingrese el número de matrícula.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });
</script>