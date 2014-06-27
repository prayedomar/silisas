<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear código de autorización</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3 ">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="form-group">
                                <label>Empleado a autorizar<em class="required_asterisco">*</em></label>
                                <select name="empleado" id="empleado" data-placeholder="Seleccione Empleado a modificar" class="chosen-select form-control exit_caution">
                                    <option value="default"></option>
                                    {empleado}
                                    <option value="{id}_{dni}">{nombre1} {nombre2} {apellido1} {apellido2}</option>
                                    {/empleado}
                                </select>
                            </div>                             
                            <div class="form-group">
                                <label>Tipo de permiso a autorizar<em class="required_asterisco">*</em></label>
                                <select name="tabla_autorizada" id="tabla_autorizada" class="form-control exit_caution">
                                    <option value="default">Seleccione tipo de permiso a autorizar</option>
                                    {tabla_autorizacion}
                                    <option value="{id}">{nombre}</option>
                                    {/tabla_autorizacion}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Código del registro que se va a modificar<em class="required_asterisco">*</em></label>
                                <input name="registro_autorizado" id="registro_autorizado" type="text" class="form-control exit_caution numerico" placeholder="Número de Contrato Físico" maxlength="13">
                            </div>                           
                            <div class="form-group">
                                <label>Observación<em class="required_asterisco">*</em></label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observacion..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear código de autorización</button>                                 
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