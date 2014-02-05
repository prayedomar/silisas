<div class="contenidoperm">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 thumbnail">
            <div class="row">
                <legend>Crear Ausencia Laboral</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 ">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="form-group">
                                <label>Empleado<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán los empleados activos que pertenecen a cualquiera de sus sedes encargadas.</p>                                
                                <select name="empleado" id="empleado" data-placeholder="Seleccione Empleado a modificar" class="chosen-select form-control exit_caution">
                                    <option value="default"></option>
                                    {empleado}
                                    <option value="{id}-{dni}">{nombre1} {nombre2} {apellido1} {apellido2}</option>
                                    {/empleado}
                                </select>
                            </div>  
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha Inicial<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_inicio" id="fecha_inicio" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha inicial de la Ausencia">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha Final<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_fin" id="fecha_fin" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha final de la Ausencia">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
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
                                <label>Descripción<em class="required_asterisco">*</em></label>
                                <textarea name="descripcion" id="descripcion" class="form-control exit_caution alfanumerico" rows="4" maxlength="255" placeholder="Descripción..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Ausencia Laboral</button>                                 
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