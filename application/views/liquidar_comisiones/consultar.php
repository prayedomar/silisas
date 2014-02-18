<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar liquidación de comisiones de matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                <input name="matricula" id="matricula" type="text" class="form-control exit_caution numerico" placeholder="Número de Contrato Físico" maxlength="13">
                            </div>
                        </div>
                        <div class="form-group separar_submit">
                            <center>
                                <button id="consultar" class="btn btn-success">Consultar comisiones de matrícula</button>
                                <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div>
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-2">
                                <div id="validacion_alert">
                                </div>
                            </div>
                        </div>
                        <div class="overflow_tabla separar_div"   id="div_detalle_matricula" style="display:none;">
                        <!--<div class="overflow_tabla separar_div"   id="div_detalle_matricula">-->
                            <label>Comisiones pagadas en nómina para ésta matricula</label>
                            <table class="table table-hover">
                                <thead>
                                    <tr>                 
                                        <th class="text-center">Fecha</th>                                        
                                        <th class="text-center">Id. Nómina</th>
                                        <th class="text-center">Ejecutivo</th>
                                        <th class="text-center">Detalle</th>
                                        <th class="text-center">Escala</th>
                                        <th class="text-center">Valor</th>                                  
                                    </tr>
                                </thead>
                                <tbody id="tbody_detalle_matricula">
                                </tbody>
                            </table>
                        </div>                            
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".contenidoperm").delegate("#consultar", "click", function() {
        matricula = $('#matricula').val();
        if (matricula != '') {
            $.post('{action_llena_comisiones_matricula}', {
                matricula: matricula
            }, function(data) {
                if (data == "") {
                    $("#validacion_alert").html('<div class="alert alert-info" id="div_info_comisiones"></div>');
                    $("#div_info_comisiones").html("<p>No hay comisiones para ésta matricula.</p>");
                    $("#div_info_comisiones").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                    $("#div_detalle_matricula").css("display", "none");
                } else {
                    $("#tbody_detalle_matricula").html(data);
                    $("#div_detalle_matricula").css("display", "block");
                }
            });
        } else {
            $("#validacion_alert").html('<div class="alert alert-danger" id="div_info_comisiones"></div>');
            $("#div_info_comisiones").html("<p>Ingrese un número de matrícula válido.</p>");
            $("#div_info_comisiones").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
            $("#div_detalle_matricula").css("display", "none");
        }
    });
</script>