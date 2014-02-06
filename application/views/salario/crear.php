<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">       
                <div class="row">
                    <div class="col-xs-6">
                        <legend>Crear salario laboral</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                        <div class="form-group">
                            <label>Nombre del salario<em class="required_asterisco">*</em></label>
                            <input name="nombre" id="nombre" type="text" class="form-control exit_caution letras_numeros" placeholder="Nombre del Salario" maxlength="40">
                        </div>
                        <div class="form-group">
                            <label>Tipo de Salario<em class="required_asterisco">*</em></label>
                            <select name="t_salario" id="t_salario_salario" class="form-control exit_caution">
                                <option value="default">Seleccione Tipo de Salario</option>
                                {t_salario}
                                <option value="{id}">{tipo}</option>
                                {/t_salario}
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="255" placeholder="Descripción..."  style="max-width:100%;"></textarea>
                        </div>
                        <div id="validacion_alert">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <legend>Conceptos Base para el salario:</legend>
                        <div id="div_t_conceptos">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <hr>
                    <div class="form-group">
                        <input type="hidden" id="action_validar" value={action_validar} />
                        <input type="hidden" name="id_responsable" value={id_responsable} />
                        <input type="hidden" name="dni_responsable" value={dni_responsable} />
                        <center>
                            <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                            <button id="botonValidar" class="btn btn-success">Crear Salario</button>                                 
                            <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                            <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Cargar t_concepto segun t_salario
    $("#t_salario_salario").live("change", function() {
        t_salario = $('#t_salario_salario').val();
        $.post('{action_llena_t_concepto_salario}', {
            t_salario: t_salario
        }, function(data) {
            $("#t_salario_salario option[value=default]").remove();
            $("#div_t_conceptos").html(data);
        });
    });
</script>