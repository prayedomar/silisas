<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-2">
                                    <div class="form-group">
                                        <label>Número del Contrato Físico de Matrícula<em class="required_asterisco">*</em></label>
                                        <input name="contrato" id="contrato" type="text" class="form-control exit_caution numerico" placeholder="Número de Contrato Físico" maxlength="13">
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha de pago de la cuota inicial<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><B>> </B>Ingrese la fecha en que el titular realizó el pago inicial (Si no ha realizado ningún pago, coloque la fecha que aprece en el contrato físico de matrícula).</p>
                                        <div class="input-group">
                                            <input name="fecha_matricula" id="fecha_matricula" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de Inicio">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Tipo de Id. del Titular<em class="required_asterisco">*</em></label>
                                                <select name="dni_titular" id="dni_titular" class="form-control exit_caution">
                                                    <option value="default">Seleccione T. Id. Titular</option>
                                                    {dni_titular}
                                                    <option value="{id}">{tipo}</option>
                                                    {/dni_titular}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Número de Id. del Titular<em class="required_asterisco">*</em></label>
                                                <input name="id_titular" id="id_titular" type="text" class="form-control exit_caution numerico" placeholder="Número de Id. del Titular" maxlength="13">
                                            </div>      
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Ejecutivo que realizó la matrícula<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><B>> </B>Sólo aparecerán los empleados de RRPP activos que pertenecen a su sede ppal.</p>
                                        <select name="ejecutivo" id="ejecutivo" data-placeholder="Seleccione Ejecutivo" class="form-control exit_caution">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Sede Principal<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><B>> </B>Sólo aparecerán las sedes autorizadas del responsable.</p>                                
                                        <select name="sede_ppal" id="sede_ppal" class="form-control exit_caution">
                                            <option value="default">Seleccione Sede Principal</option>
                                            {sede_ppal}
                                            <option value="{id}">{nombre}</option>
                                            {/sede_ppal}
                                        </select>
                                    </div>                                    
                                </div> 
                            </div>
                            <div class="overflow_tabla">
                                <label>Plan Comercial<em class="required_asterisco">*</em></label>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Escojer</th>  
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Año</th>                                          
                                            <th class="text-center">Cant. Alumnos</th>
                                            <th class="text-center">Valor Total</th>
                                            <th class="text-center">Valor Inicial</th>
                                            <th class="text-center">Valor Cuota</th>
                                            <th class="text-center">Cant. Cuotas</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_planes">
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div id="validacion_alert">
                            </div>                            
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Matrícula</button>                                 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                                </center>
                            </div>
                        </form>                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Cargamos los planes comerciales de matricula vigentes
    $.post('{action_llena_plan_comercial}', {},
            function(data) {
                $("#tbody_planes").html(data);
            });

    $.post('{action_llena_ejecutivo}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#ejecutivo").html(data);
        $("#ejecutivo").prepend('<option value="default" selected>Seleccione Ejecutivo</option>');
    });


</script>