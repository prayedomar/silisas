<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear caja de efectivo (Punto de venta)</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3 ">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="form-group">
                                <label>Sede<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán cada una de sus sedes autorizadas.</p>
                                <select name="sede" id="sede" class="form-control exit_caution">
                                    <option value="default">Seleccione Sede</option>
                                    {sede}
                                    <option value="{id}">{nombre}</option>
                                    {/sede}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tipo de Caja<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán los tipos de cajas faltantes para la sede escogida.</p>
                                <select name="t_caja" id="t_caja" class="form-control exit_caution" disabled="disabled">
                                    <option value="default" selected="selected">Seleccione Primero: Sede</option>
                                </select>
                            </div>                            
                            <div class="form-group">
                                <label>Empleado Encargado<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos que no cuentan con una caja y cuya sede principal coincida con la sede escogida.</p>
                                <select name="empleado" id="empleado" data-placeholder="Seleccione Empleado Encargado de la Caja" class="form-control exit_caution" disabled="disabled">
                                    <option value="default" selected="selected">Seleccione Primero: Sede</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Caja</button>                                 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                                </center>
                            </div>   
                            <div id="validacion_alert">
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Llenamos el select t_caja a partir de la sede escojida
    $(".form-group").delegate("#sede", "change", function() {
        sede = $('#sede').val();
        $.post('{action_llena_t_caja_sede}', {
            sede: sede
        }, function(data) {
            $("#t_caja").removeAttr("disabled")
            $("#t_caja").html(data);
            $("#t_caja").prepend('<option value="default" selected="selected">Seleccione Tipo de Caja</option>');
        });
        $.post('{action_llena_encargado_sede}', {
            sede: sede
        }, function(data) {
            $("#empleado").removeAttr("disabled")
            $("#empleado").html(data);
            $("#empleado").prepend('<option value="default" selected="selected">Seleccione Encargado de la Caja</option>');
        });
    });
</script>