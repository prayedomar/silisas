<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear sede</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p>                  
                <div class="row">
                    <form role="form" method="post" action="{action_crear}" id="formulario">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="required">Nombre de la Sede<em class="required_asterisco">*</em></label>
                                <input name="nombre" id="nombre" type="text" class="form-control exit_caution letras_numeros" placeholder="Nombre de la Sede" maxlength="40" autofocus="autofocus">
                            </div>
                            <div class="form-group">
                                <label>País de domicilio<em class="required_asterisco">*</em></label>
                                <select name="pais" id="pais" class="form-control exit_caution">
                                    <option value="default">Seleccione País</option>
                                    {pais}
                                    <option value="{id}">{nombre}</option>
                                    {/pais}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Departamento de domiclio<em class="required_asterisco">*</em></label>
                                <select name="provincia" id="provincia" class="form-control exit_caution" disabled>
                                    <option value="default">Seleccione primero País</option>
                                </select>                                
                            </div>
                            <div class="form-group">
                                <label>Ciudad de domiclio<em class="required_asterisco">*</em></label>
                                <select name="ciudad" id="ciudad" class="form-control exit_caution" disabled>
                                    <option value="default">Seleccione primero Depto</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Estado<em class="required_asterisco">*</em></label>
                                <select name="estado" id="estado" class="form-control exit_caution">
                                    <option value="default">Seleccione Estado</option>
                                    {est_sede}
                                    <option value="{id}">{estado}</option>
                                    {/est_sede}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dirección<em class="required_asterisco">*</em></label>
                                <input name="direccion" id="direccion" type="text" class="form-control exit_caution alfanumerico" placeholder="Dirección de la Sede" maxlength="80">
                            </div>                            
                            <div id="validacion_alert">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Telefono 1</label>
                                <input name="tel1" id="tel1" type="text" class="form-control exit_caution alfanumerico" placeholder="Telefono 1" maxlength="40">
                            </div>
                            <div class="form-group">
                                <label>Telefono 2</label>
                                <input name="tel2" id="tel2" type="text" class="form-control exit_caution alfanumerico" placeholder="Telefono 2" maxlength="40">
                            </div>
                            <div class="form-group">
                                <label class="required">Prefijo para Transacciones<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Prefijo de 4 letras para Facturas, Recibos de Caja, etc.</p>                                                                
                                <input name="prefijo_trans" id="prefijo_trans" type="text" class="form-control exit_caution alfabeto" placeholder="Prefijo para Transacciones" maxlength="4" autofocus="autofocus">
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
                                    <button id="botonValidar" class="btn btn-success">Crear Sede</button>                                 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //PAra cargar los selects dinamicos de pais y departamento
    $("#pais").live("change", function() {
        pais = $('#pais').val();
        $.post('{action_llena_provincia}', {
            pais: pais
        }, function(data) {
            $("#provincia").removeAttr("disabled");
            $("#provincia").html(data);
            $("#provincia").prepend('<option value="default" selected>Seleccione Departamento</option>');
            //Con esto activamos automaticamente el evento click como si lo hicieramos nosotros.
            $("#provincia").change();
        });
    });
    $("#provincia").live("change", function() {
        provincia = $('#provincia').val();
        $.post('{action_llena_ciudad}', {
            provincia: provincia
        }, function(data) {
            $("#ciudad").removeAttr("disabled")
            $("#ciudad").html(data);
            $("#ciudad").prepend('<option value="default" selected>Seleccione Ciudad</option>');
        });
    });
</script>