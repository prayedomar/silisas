<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Despachar solicitudes de placas de ascenso</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="overflow_tabla">
                                <label>Solicitudes pendientes<em class="required_asterisco">*</em></label>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Escojer</th>                                            
                                            <th class="text-center">Empleado</th>
                                            <th class="text-center">Cargo Obtenido</th>
                                            <th class="text-center">Sede</th>
                                            <th class="text-center">Observación</th>
                                            <th class="text-center">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_solicitudes">
                                    </tbody>
                                </table>
                            </div>                            
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="250" placeholder="Observación..."  style="max-width:100%;"></textarea>
                            </div>
                            <div class="form-group">
                                <p><B>Nota: </B>Si por alguna razón decide cancelar el envío de alguna solicitud de placa, hagálo por la opción: Anular Solicitud de Placa.</p>                            
                            </div>
                            <div id="validacion_alert">
                            </div>                            
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Despachar Placas</button>                                 
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
<!--Llenamos en la tabla las solicitudes de placas pendientes-->
<script type="text/javascript">
    $.post('{action_llenar_placas}', {},
            function(data) {
                $("#tbody_solicitudes").html(data);
            });
</script>