<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear traslado de contratos físicos de matrícula (Transacción masiva)</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <form role="form" method="post" action="{action_crear}" id="formulario">
                        <div class="col-xs-8 col-xs-offset-2">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Número de Contrato Inicial<em class="required_asterisco">*</em></label>
                                        <input name="contrato_inicial" id="contrato_inicial" type="text" class="form-control exit_caution numerico" placeholder="Número de Contrato Inicial" maxlength="13">
                                    </div>  
                                </div>
                                <div class="col-xs-6">  
                                    <div class="form-group">
                                        <label>Número de Contrato Final<em class="required_asterisco">*</em></label>
                                        <input name="contrato_final" id="contrato_final" type="text" class="form-control exit_caution numerico" placeholder="Número de Contrato Final" maxlength="13">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Sede Actual<em class="required_asterisco">*</em></label>
                                <p class="help-block"><B>> </B>Sólo aparecerán las sedes autorizadas del responsable.</p>                                
                                <select name="sede_actual" id="sede_actual" class="form-control exit_caution">
                                    <option value="default">Seleccione Sede</option>
                                    {sede_actual}
                                    <option value="{id}">{nombre}</option>
                                    {/sede_actual}
                                </select>
                            </div>                            
                            <div class="form-group">
                                <label>Sede Destino<em class="required_asterisco">*</em></label>                             
                                <select name="sede_destino" id="sede_destino" class="form-control exit_caution">
                                    <option value="default">Seleccione Sede</option>
                                    {sede_destino}
                                    <option value="{id}">{nombre}</option>
                                    {/sede_destino}
                                </select>
                            </div>
                            <div id="validacion_alert">
                            </div>                            
                            <div class="form-group separar_submit">
                                <input type="hidden" id="action_validar" value={action_validar} />
                                <input type="hidden" name="id_responsable" value={id_responsable} />
                                <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                <center>
                                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                    <button id="botonValidar" class="btn btn-success">Crear Traslado de Contratos</button>                                 
                                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                    <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                                </center>
                            </div>   
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal loading-->
<div class="modal" id="modal_loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_loading">
                    <div class="row  text-center">
                        <div class="col-xs-2 col-xs-offset-5 separar_div">
                            <img src="<?= base_url() ?>images/loading_2.gif" class="img-responsive">
                        </div>
                        <div  class="col-xs-10 col-xs-offset-1">
                            <h4 class="modal-title" id="myModalLabel">Estamos procesando tu solicitud</h4>
                            <h6 class="modal-title" id="myModalLabel">Espera unos segundos</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>