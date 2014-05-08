<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear salón</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p>  
                <div class="row">
                    <form role="form" method="post" action="{action_crear}" id="formulario">
                        <div class="col-xs-6">                          
                            <div class="form-group">
                                <label>Nombre<em class="required_asterisco">*</em></label>
                                <input name="nombre" id="nombre" type="text" class="form-control exit_caution letras_numeros" placeholder="Nombre del Salón" maxlength="40">
                            </div>
                            <div class="form-group">
                                <label>Capacidad<em class="required_asterisco">*</em></label>
                                <input name="capacidad" id="capacidad" type="text" class="form-control exit_caution numerico" placeholder="Capacidad del Salón" maxlength="2">
                            </div>
                            <div class="form-group">
                                <label>Sede<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán cada una de sus sedes encargadas.</p>                                
                                <select name="sede" id="sede" class="form-control exit_caution">
                                    <option value="default">Seleccione Sede</option>
                                    {sede}
                                    <option value="{id}">{nombre}</option>
                                    {/sede}
                                </select>
                            </div>
                            <div id="validacion_alert">
                            </div>
                        </div>
                        <div class="col-xs-6">                          
                            <div class="form-group">
                                <label>Vigente<em class="required_asterisco">*</em></label>
                                <select name="vigente" id="vigente" class="form-control exit_caution">
                                    <option value="default">Seleccione Vigencia</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>              
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution" rows="4" maxlength="250" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Salón</button>                                 
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
