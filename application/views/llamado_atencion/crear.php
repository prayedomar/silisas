<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <legend>Crear llamado de atención, suspensión o terminación del contrato laboral</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
            <div class="row">
                <div class="col-xs-12">
                    <form role="form" method="post" action="{action_crear}" id="formulario">
                        <div class="row">
                            <div class="col-xs-6 col-xs-offset-3">
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
                            </div>
                        </div>
                        <div class="overflow_tabla">
                            <label>Falta Laboral<em class="required_asterisco">*</em></label>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Escojer</th>                                            
                                        <th class="text-center">Falta Laboral</th>
                                        <th class="text-center">Gravedad</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_falta_laboral">
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-xs-offset-3">
                                <div class="form-group">
                                    <label>Sanción a inponer<em class="required_asterisco">*</em></label>
                                    <select name="t_sancion" id="t_sancion" class="form-control exit_caution">
                                        <option value="default">Seleccione Sanción</option>
                                        {t_sancion}
                                        <option value="{id}">{tipo}</option>
                                        {/t_sancion}
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div id="div_suspension" style="display:none;">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Fecha Inicial de la Suspensión<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_inicio" id="fecha_inicio" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha inicial de la Ausencia">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Fecha Final de la suspensión<em class="required_asterisco">*</em></label>
                                        <div class="input-group">
                                            <input name="fecha_fin" id="fecha_fin" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha final de la Ausencia">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <p class="text-center"><B>Nota: </B>Automaticamente se creará la ausencia laboral para el empleado en las fechas especificadas.</p> 
                        </div>                      
                        <div id="div_anular_contrato" class="text-center" style="display:none;">
                            <p><B>Nota: </B>Automaticamente se anulará el contrato laboral del empleado.</p>                            
                        </div>
                        <div class="form-group">
                            <label>Descripción de los hechos<em class="required_asterisco">*</em></label>
                            <textarea name="descripcion" id="descripcion" class="form-control exit_caution alfanumerico" rows="3" maxlength="250" placeholder="Descripción de los hechos..."  style="max-width:100%;"></textarea>
                        </div>      
                        <div id="validacion_alert">
                        </div>                        
                        <div class="form-group separar_submit">
                            <input type="hidden" id="action_validar" value={action_validar} />
                            <input type="hidden" name="id_responsable" value={id_responsable} />
                            <input type="hidden" name="dni_responsable" value={dni_responsable} />
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                <button id="botonValidar" class="btn btn-success">Crear Llamado de Atención</button>                                 
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div>   
                    </form>
                </div> 
            </div>
        </div>
    </div>
</div>

<!--Llenamos en la tabla falta laboral-->
<script type="text/javascript">
    $.post('{action_llenar_faltas}', {},
            function(data) {
                $("#tbody_falta_laboral").html(data);
            });

    //Cargar div de sancion segun t_sancion
    $(".form-group").delegate("#t_sancion", "change", function() {
        t_sancion = $('#t_sancion').val();
        if (t_sancion == '2') {
            $("#div_suspension").css("display", "block");
            $("#div_anular_contrato").css("display", "none");
        } else {
            if (t_sancion == '3') {
                $("#div_anular_contrato").css("display", "block");
                $("#div_suspension").css("display", "none");
            } else {
                $("#div_suspension").css("display", "none");
                $("#div_anular_contrato").css("display", "none");
            }
        }
    });


</script>