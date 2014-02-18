<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar liquidación de comisiones de matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <div class="form-group" id="div_matricula">
                                    <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                    <input name="matricula" id="matricula" type="text" class="form-control exit_caution numerico" placeholder="Número de Contrato Físico" maxlength="13">
                                </div> 
                            </div>
                        </div>
                        <div class="form-group separar_submit">
                            <center>
                                <button id="botonValidar" class="btn btn-success">Consultar comisiones de matrícula</button>                                 
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                <a href="{base_url}" class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div>
                        <!--<div class="overflow_tabla separar_div"   id="div_detalle_matricula" style="display:none;">-->
                        <div class="overflow_tabla separar_div"   id="div_detalle_matricula">
                            <label>Detalles de la Matrícula</label>
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
                        <div id="validacion_alert">
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Llenamos las matriculas no liquidadas  que pertenecec a la sede ppal. del resopnsable
    $.post("{action_llena_matricula_iliquidada}", {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#matricula").html(data);
        $("#matricula").prepend('<option value="default" selected>Seleccione Matrícula</option>');
        $("#matricula option[value=" + '{id_matricula}' + "]").attr("selected", true);

        //Verificamos que el valor que viene por post si corresponda a cualquier valor de la lista de matricula
        if ($("#matricula").val() == '{id_matricula}') {
            $("#div_validacion_success").html('<div class="alert alert-success" id="div_success"></div>');
            $("#div_success").html("<p><strong>¡La Matrícula se creó correctamente!</strong> Ahora, cree la liquidación de Comisiones.</p>");
            //Cerramos el div automaticamente
            $("#div_success").delay(5000).fadeOut(1000);
        }

        //Llenamos la informacion al cargar, por si ya hay una matricula seleccionada por post.
        matricula = $('#matricula').val();
        if (matricula !== 'default') {
            $.post('{action_llena_detalle_matricula}', {
                matricula: matricula
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#tbody_detalle_matricula").html(obj.detalleMatricula);
                    $('#ejecutivo_original').attr('value', obj.IdDniEjecutivo);
                    $("#div_detalle_matricula").css("display", "block");
                    $("#div_comisiones").css("display", "block");
                    $("#ejecutivo_directo option[value=" + $('#ejecutivo_original').val() + "]").attr("selected", true);

                    //Llenamos los selects de las comisiones por escala
                    ejecutivoDirecto = $('#ejecutivo_directo').val();
                    $.post('{action_llena_cargo_comision_faltante}', {
                        idResposable: '{id_responsable}',
                        dniResposable: '{dni_responsable}',
                        ejecutivoDirecto: ejecutivoDirecto
                    }, function(data) {
                        if (data == "") {
                            $("#div_comision_escala").html('<div class="alert alert-info" id="div_info_comisiones"></div>');
                            $("#div_info_comisiones").html("<p>No hay comisiones por escala para liquidar.</p>");
                            $("#div_info_comisiones").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                        } else {
                            $("#div_comision_escala").html(data);
                            $("#div_comision_escala").prepend('<p class="help-block"><B>> </B>Los empleados con cargos que se limitan a una sede (Gerente Encargado e inferiores), solo aparecerán sí pertenecen a su sede principal.</p>');
                            $("#div_comision_escala").prepend('<p class="help-block"><B>> </B>Sólo aparecerán los empleados de RRPP que ocupan un cargo superior al de la escala.</p>');
                        }
                    });

                    //Cargamos el label del cargo del ejecutivo directo
                    $.post('{action_llena_cargo_ejecutivo_directo}', {
                        ejecutivoDirecto: ejecutivoDirecto
                    }, function(data) {
                        if (data == "") {
                            $("#label_ejecutivo_directo  > *").remove();
                        } else {
                            $("#label_ejecutivo_directo").html(data);
                        }
                    });
                }
            });
        }
    });

    //Llena la lista de ejecutivos de comision directa.
    $.post('{action_llena_ejecutivo}', {
        idResposable: '{id_responsable}',
        dniResposable: '{dni_responsable}'
    }, function(data) {
        $("#ejecutivo_directo").html(data);
        $("#ejecutivo_directo").prepend('<option value="default" selected>Seleccione Ejecutivo</option>');
    });

    //Llena Detalle de la matricula
    $("#div_matricula").delegate("#matricula", "change", function() {
        //Llenamos la informacion al cargar, por si ya hay una matricula seleccionada por post.
        matricula = $('#matricula').val();
        if (matricula !== 'default') {
            $.post('{action_llena_detalle_matricula}', {
                matricula: matricula
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#tbody_detalle_matricula").html(obj.detalleMatricula);
                    $('#ejecutivo_original').attr('value', obj.IdDniEjecutivo);
                    $("#div_detalle_matricula").css("display", "block");
                    $("#div_comisiones").css("display", "block");
                    $("#ejecutivo_directo option[value=" + $('#ejecutivo_original').val() + "]").attr("selected", true);

                    //Llenamos los selects de las comisiones por escala
                    ejecutivoDirecto = $('#ejecutivo_directo').val();
                    $.post('{action_llena_cargo_comision_faltante}', {
                        idResposable: '{id_responsable}',
                        dniResposable: '{dni_responsable}',
                        ejecutivoDirecto: ejecutivoDirecto
                    }, function(data) {
                        if (data == "") {
                            $("#div_comision_escala").html('<div class="alert alert-info" id="div_info_comisiones"></div>');
                            $("#div_info_comisiones").html("<p>No hay comisiones por escala para liquidar.</p>");
                            $("#div_info_comisiones").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                        } else {
                            $("#div_comision_escala").html(data);
                            $("#div_comision_escala").prepend('<p class="help-block"><B>> </B>Los empleados con cargos que se limitan a una sede (Gerente Encargado e inferiores), solo aparecerán sí pertenecen a su sede principal.</p>');
                            $("#div_comision_escala").prepend('<p class="help-block"><B>> </B>Sólo aparecerán los empleados de RRPP que ocupan un cargo superior al de la escala.</p>');
                        }
                    });

                    //Cargamos el label del cargo del ejecutivo directo
                    $.post('{action_llena_cargo_ejecutivo_directo}', {
                        ejecutivoDirecto: ejecutivoDirecto
                    }, function(data) {
                        if (data == "") {
                            $("#label_ejecutivo_directo  > *").remove();
                        } else {
                            $("#label_ejecutivo_directo").html(data);
                        }
                    });
                }
            });
        } else {
            $("#div_detalle_matricula").css("display", "none");
            $("#div_comisiones").css("display", "none");
            $("#div_comision_escala  > *").remove();
            $("#tbody_detalle_matricula  > *").remove();
        }
    });

    $(".form-group").delegate("#ejecutivo_directo", "change", function() {
        //Llenamos los selects de las comisiones por escala
        ejecutivoDirecto = $('#ejecutivo_directo').val();
        $.post('{action_llena_cargo_comision_faltante}', {
            idResposable: '{id_responsable}',
            dniResposable: '{dni_responsable}',
            ejecutivoDirecto: ejecutivoDirecto
        }, function(data) {
            if (data == "") {
                $("#div_comision_escala").html('<div class="alert alert-info" id="div_info_comisiones"></div>');
                $("#div_info_comisiones").html("<p>No hay comisiones por escala para liquidar.</p>");
                $("#div_info_comisiones").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
            } else {
                $("#div_comision_escala").html(data);
                $("#div_comision_escala").prepend('<p class="help-block"><B>> </B>Los empleados con cargos que se limitan a una sede (Gerente Encargado e inferiores), solo aparecerán sí pertenecen a su sede principal.</p>');
                $("#div_comision_escala").prepend('<p class="help-block"><B>> </B>Sólo aparecerán los empleados de RRPP que ocupan un cargo superior al de la escala.</p>');
            }
        });
        //Cargamos el label del cargo del ejecutivo directo
        $.post('{action_llena_cargo_ejecutivo_directo}', {
            ejecutivoDirecto: ejecutivoDirecto
        }, function(data) {
            if (data == "") {
                $("#label_ejecutivo_directo  > *").remove();
            } else {
                $("#label_ejecutivo_directo").html(data);
            }
        });

    });



</script>