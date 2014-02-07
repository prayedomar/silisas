<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear cuenta bancaria (Registra los movimientos de las cuentas bancarias reales)</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3 ">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="form-group">
                                <label>Número de Cuenta Bancaria<em class="required_asterisco">*</em></label>
                                <input name="cuenta" id="cuenta" type="text" class="form-control exit_caution numerico" placeholder="Cuenta Bancaria de Nómina" maxlength="12">
                            </div>
                            <div class="form-group">
                                <label>Tipo de Cuenta Bancaria<em class="required_asterisco">*</em></label>
                                <select name="t_cuenta" id="t_cuenta" class="form-control exit_caution">
                                    <option value="default">Seleccione Tipo de Cuenta</option>
                                    {t_cuenta}
                                    <option value="{id}">{tipo}</option>
                                    {/t_cuenta}
                                </select>
                            </div>                         
                            <div class="form-group">
                                <label>País del Banco<em class="required_asterisco">*</em></label>
                                <select name="pais" id="pais" class="form-control exit_caution">
                                    <option value="default">Seleccione País</option>
                                    {pais}
                                    <option value="{id}">{nombre}</option>
                                    {/pais}
                                </select>
                            </div>                            
                            <div class="form-group">
                                <label>Banco<em class="required_asterisco">*</em></label>
                                <select name="banco" id="banco" class="form-control exit_caution" disabled="disabled">
                                    <option value="default">Seleccione primero País.</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nombre de la Cuenta<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Por ejemplo: "Empresarial Principal", "Manejo Sede Floresta", etc.</p>                                
                                <input name="nombre_cuenta" id="nombre_cuenta" type="text" class="form-control exit_caution letras_numeros" placeholder="Nombre de la Cuenta" maxlength="60">
                            </div>                               
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="255" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Cuenta</button>                                 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="{base_url}" class="btn btn-danger" role="button">Cancelar</a>
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
    //Llenamos el select bancos segun pais
    $(".form-group").delegate("#pais", "change", function() {
        pais = $('#pais').val();
        $.post('{action_llena_banco_pais}', {
            pais: pais
        }, function(data) {
            $("#banco").removeAttr("disabled");
            $("#banco").html(data);
            $("#banco").prepend('<option value="default" selected>Seleccione Banco</option>');
        });
    });
</script>