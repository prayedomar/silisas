<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar pagos realizados a una matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">  
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-2">
                                <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                <input name="id" id="id" type="text" class="form-control numerico" placeholder="Número de Contrato Físico" maxlength="13" <?php if (isset($id)) { ?> value="<?php echo $id ?>" <?php } ?>>
                            </div>
                        </div><br>
                        <div id="validacion_inicial">
                        </div> 
                        <div class="row text-center separar_submit">
                            <button type="button" class="btn btn-default" id="consultar_pagos"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                            <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar matrícula </a>
                        </div>
                    </div>
                </div>
                <div class="row" style="display: none" id="div_pagos">
                    <div class="col-xs-12">
                        <hr>
                        <div class="row separar_submit" id="info_matricula">
                        </div>
                        <div id="tabla_pagos">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Llenamos la informacion de las matriculas y los pagos.     
    $(".row").delegate("#consultar_pagos", "click", function() {
        var id = $('#id').val();
        if (id != "") {
            $.post('{action_valida_llena_pagos}', {
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#div_pagos").css("display", "block");
                    $("#tabla_pagos").html(obj.html_pagos);
                    $("#info_matricula").html('<center><table><tr><td><h4>Sede principal: </h4></td><td><h4 class="h_negrita"> ' + obj.sede + '</h4></td></tr><tr><td><h4>Nombre del titular: </h4></td><td><h4 class="h_negrita"> ' + obj.titular + '</h4></td></tr><tr><td><h4>Documento titular: </h4></td><td><h4 class="h_negrita"> ' + obj.idTitular + '</h4></td></tr><tr><td><h4>Nombre del plan: </h4></td><td><h4 class="h_negrita"> ' + obj.plan + '</h4></td></tr><tr><td><h4>Costo total: </h4></td><td><h4 class="h_negrita"> $' + obj.costo + '</h4></td></tr><tr><td><h4>Abonado al plan: </h4></td><td><h4 class="h_negrita"> $' + obj.abonado + '</h4></td></tr><tr><td><h4>Saldo pendiente: </h4></td><td><h4 class="h_negrita"> $' + obj.saldo + '</h4></td></tr></table></center>');
                    $('#id').attr('disabled', 'disabled');
                    $('#consultar_pagos').attr('disabled', 'disabled');
                    $("#div_warning").remove();
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de consultar, ingrese el número de matrícula.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });

</script>