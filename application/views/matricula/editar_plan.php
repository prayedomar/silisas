<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">          
                <div class="row">
                    <legend>Cambio de tipo de plan de una matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-4 col-xs-offset-4">
                                    <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                    <p class="help-block"><B>> </B>La matrícula debe pertenecer a su sede principal.</p>                                     
                                    <input name="matricula" id="matricula" type="text" class="form-control exit_caution numerico" placeholder="Número de Contrato Físico" maxlength="13">
                                </div>
                            </div>
                            <div class="form-group separar_submit">
                                <center>
                                    <button id="consultar" type="button" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Consultar</button>
                                    <a href='{action_recargar}' class="btn btn-default" role="button"> Cambiar matrícula</a>
                                </center>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-2">
                                    <div id="validacion_inicial">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_planes" style="display: none">
                <!--<div id="div_planes">-->
                    <div class="row separar_submit" id="nombre_titular">
                    </div>
                    <div class="overflow_tabla separar_div">
                        <label>Tipo de plan actual<em class="required_asterisco">*</em></label>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Año</th>                                          
                                    <th class="text-center">Cant. Alumnos</th>
                                    <th class="text-center">Valor Total</th>
                                    <th class="text-center">Valor Inicial</th>
                                    <th class="text-center">Valor Cuota</th>
                                    <th class="text-center">Cant. Cuotas</th>                                            
                                </tr>
                            </thead>
                            <tbody id="tbody_plan_old">
                            </tbody>
                        </table>
                    </div>
                    <div class="overflow_tabla">
                        <label>Nuevo tipo de plan<em class="required_asterisco">*</em></label>
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
                            <tbody id="tbody_plan_new">
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea name="observacion" id="observacion" class="form-control exit_caution alfanumerico" rows="4" maxlength="255" placeholder="Observación..."  style="max-width:100%;"></textarea>
                    </div>
                    <div class="form-group separar_submit">
                        <input type="hidden" id="action_validar" value={action_validar} />
                        <input type="hidden" name="id_responsable" value={id_responsable} />
                        <input type="hidden" name="dni_responsable" value={dni_responsable} />
                        <!--aqui hiran los datos ocultos-->
                        <input type="hidden" name="plan_old" id="plan_old"/>
                        <input type="hidden" name="id_matricula" id="id_matricula"/>
                        <center>
                            <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                            <button id="botonValidar" class="btn btn-success">Cambiar tipo de plan</button>  
                            <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                            <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                        </center>
                    </div>   
                    <div id="validacion_alert">
                    </div>
                </div>
            </form>
        </div>        
    </div>
</div>
<script type="text/javascript">
    //Llenamos la informacion de las matriculas y los planes.
    $("form").delegate("#consultar", "click", function() {
        var matricula = $('#matricula').val();
        if (matricula != "") {
            $.post('{action_llena_t_plan_old}', {
                matricula: matricula
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#nombre_titular").html('<center><table><th><td><h4>Nombre del titular: </h4></td><td><h4 class="h_negrita"> ' + obj.nombreTitular + '</h4></td></th></table></center>');
                    $("#tbody_plan_old").html(obj.filasTabla);
                    $("#plan_old").attr('value', obj.plan_old);
                    $("#id_matricula").attr('value', matricula);
                    $("#div_planes").css("display", "block");
                    $('#matricula').attr('disabled', 'disabled');
                    $('#consultar').attr('disabled', 'disabled');
                    $("#div_warning").remove();
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong>Antes de consultar, ingrese el número de matrícula.</strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });

    //Cargamos los planes comerciales de matricula vigentes
    $.post('{action_llena_plan_comercial}', {},
            function(data) {
                $("#tbody_plan_new").html(data);
            });
</script>