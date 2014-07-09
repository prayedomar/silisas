<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Crear reporte de clase a Alumno </legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="col-xs-12">
                        <form role="form" method="post" action="{action_crear}" id="formulario">
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3">
                                    <legend>Alumno</legend>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Tipo de Identificación<em class="required_asterisco">*</em></label>
                                                <select name="dni" id="dni" class="form-control exit_caution">
                                                    <option value="default">Seleccione T.I.</option>
                                                    {dni}
                                                    <option value="{id}">{tipo}</option>
                                                    {/dni}
                                                </select>
                                            </div>   
                                        </div>
                                        <div class="col-xs-6">  
                                            <div class="form-group">
                                                <label>Número de Identificación<em class="required_asterisco">*</em></label>
                                                <input name="id" id="id" type="text" class="form-control exit_caution numerico" placeholder="Número de Identificación" maxlength="13">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="validacion_inicial">
                                    </div>                            
                                    <div class="row text-center separar_submit">
                                        <button type="button" class="btn btn-default" id="consultar_alumno"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                        <a href='{action_recargar}' class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> Modificar Alumno </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="div_t_curso" style="display: none">
                                <hr>
                                <div class="col-xs-6 col-xs-offset-3">
                                    <legend>Actualizar tipo de curso del alumno</legend>
                                    <div class="row">
                                        <div class="form-group">
                                            <label>Tipo de Curso<em class="required_asterisco">*</em></label>
                                            <select name="t_curso" id="t_curso" class="form-control exit_caution">
                                                <option value="default">Seleccione Tipo de Curso</option>
                                                {t_curso}
                                                <option value="{id}">{tipo}</option>
                                                {/t_curso}
                                            </select>
                                        </div> 
                                    </div>
                                    <div id="validacion_t_curso">
                                    </div>                            
                                    <div class="row text-center separar_submit">
                                        <button type="button" class="btn btn-default" id="actualizar_t_curso"><span class="glyphicon glyphicon-upload"></span> Actualizar tipo de curso </button>
                                    </div>
                                </div>
                            </div>
                            <div id="div_reporte" style="display: none">
                                <!--<div id="div_reporte">-->
                                <hr>
                                <div class="row separar_submit" id="info_alumno">
                                </div>
                                <div class="row" id="div_reportes_anteriores">
                                </div>
                                <div class="row">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <legend>Nuevo reporte de clase</legend>
                                        <div class="row">
                                            <div class="col-xs-4 col-xs-offset-4">
                                                <div class="form-group">
                                                    <label>Fecha de la clase<em class="required_asterisco">*</em></label>
                                                    <div class="input-group">
                                                        <input name="fecha_clase" id="fecha_clase" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de la clase">
                                                        <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>¿Asistió a la clase?<em class="required_asterisco">*</em></label>
                                                <select name="asistencia" id="asistencia" class="form-control exit_caution">
                                                    <option value="default"  selected="selected">Seleccione sí asistió a la clase</option>
                                                    <option value="1">Si</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Etapa al finalizar la clase</label>
                                                <select name="etapa" id="etapa" class="form-control exit_caution">
                                                    <option value="default">Etapa al finalizar la clase</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                </select>
                                            </div>   
                                            <div class="form-group">
                                                <label>Fase</label>
                                                <input name="fase" id="fase" type="text" class="form-control exit_caution alfanumerico" placeholder="Fase" maxlength="100">
                                            </div>
                                            <div class="form-group">
                                                <label>Meta velocidad</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">p.p.m</span>
                                                    <input type="text" name="meta_v" id="meta_v" class="form-control numerico miles" placeholder="0" maxlength="5">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Meta comprensión</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input type="text" name="meta_c" id="meta_c" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="5">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Meta Retención</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input type="text" name="meta_r" id="meta_r" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="5">
                                                </div>
                                            </div>                                        
                                        </div>
                                        <div class="col-xs-6">  
                                            <div class="form-group">
                                                <label>Cantidad de prácticas realizadas</label>
                                                <input type="text" name="cant_practicas" id="cant_practicas" class="form-control numerico miles" placeholder="0" maxlength="5">
                                            </div>                                             
                                            <div class="form-group">
                                                <label>Lectura</label>
                                                <input name="lectura" id="lectura" type="text" class="form-control exit_caution alfanumerico" placeholder="Lectura" maxlength="100">
                                            </div>                                        
                                            <div class="form-group">
                                                <label>Velocidad mental actual</label>                                
                                                <div class="input-group">
                                                    <span class="input-group-addon">p.p.m</span>
                                                    <input type="text" name="vlm" id="vlm" class="form-control numerico miles" placeholder="0" maxlength="5">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Velocidad verbal actual</label>                                
                                                <div class="input-group">
                                                    <span class="input-group-addon">p.p.m</span>
                                                    <input type="text" name="vlv" id="vlv" class="form-control numerico miles" placeholder="0" maxlength="5">
                                                </div>
                                            </div>                                        
                                            <div class="form-group">
                                                <label>Comprensión actual</label>                                
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input type="text" name="c" id="c" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="5">
                                                </div>
                                            </div>   
                                            <div class="form-group">
                                                <label>Retención actual</label>                                
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input type="text" name="r" id="r" class="form-control decimal decimal2 miles" placeholder="0.00" maxlength="5">
                                                </div>
                                            </div>                         
                                        </div>
                                    </div>
                                </div>
                                <div class="row separar_div">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <div id="div_ejercicios">
                                            <label>Ejercicios realizados en clase</label>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="btn btn-default" type="button" id="agregar_ejercicio"><span class="glyphicon glyphicon-plus"></span> Agregar ejercicio de clase</button>  
                                        </div> 
                                    </div>
                                </div>
                                <div class="row separar_div">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <div class="form-group">
                                            <label class="label_observacion">Observación para manejo interno</label>
                                            <label  style="display:none;" class="label_observacion_required">Observación interna<em class="required_asterisco">*</em></label>
                                            <textarea name="observacion_interna" id="observacion_interna" class="form-control exit_caution" rows="4" maxlength="250" placeholder="Observación manejo interno..."style="max-width:100%;"></textarea>
                                        </div> 
                                    </div>
                                </div>  
                                <div class="row separar_div">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <div class="form-group">
                                            <label class="label_observacion">Observación para el titular y/o el alumno</label>
                                            <label  style="display:none;" class="label_observacion_required">Observación para el titular y/o el alumno<em class="required_asterisco">*</em></label>
                                            <textarea name="observacion_titular_alumno" id="observacion_titular_alumno" class="form-control exit_caution" rows="4" maxlength="250" placeholder="Observación para el titular y/o el alumno..."style="max-width:100%;"></textarea>
                                        </div> 
                                    </div>
                                </div>  
                                <div id="validacion_alert">
                                </div>                                 
                                <div class="row">
                                    <div class="form-group separar_submit">
                                        <input type="hidden" id="action_validar" value={action_validar} />
                                        <input type="hidden" name="contador_new_ejercicio" class="miles decimal2" id="contador_new_ejercicio"/>
                                        <center>
                                            <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                            <button id="btn_validar" class="btn btn-success">Crear reporte de alumno</button> 
                                            <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                            <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                                        </center>
                                    </div>    
                                </div>
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Detalles del reporte</h3>
            </div>
            <div id="bodyModalDetalles" class="modal-body">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="text-right">Ejercicios realizados:</div>   
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <b><div id="divM_ejercicios"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="text-right">Meta velocidad:</div>   
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <b><div id="divM_meta_v"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="text-right">Meta Comprensión:</div>   
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <b><div id="divM_meta_c"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="text-right">Meta retención:</div>   
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <b><div id="divM_meta_r"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="text-right">Observación interna:</div>   
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <b><div id="divM_observacion_interna"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="text-right">Observación Titular y/o Alumno:</div>   
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <b><div id="divM_observacion_titular_alumno"></div></b>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="text-right">Responsable:</div>   
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <b><div id="divM_responsable"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <div class="text-right">Fecha del reporte:</div>   
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <b><div id="divM_fecha_trans"></div></b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Mostramos el obligatorio de descripcion si en t_egreso selecciona "otro" o "transaccion intersede de difereente pais"
    $(".form-group").delegate("#asistencia", "change", function() {
        if ($(this).val() == '1') {
            $(".label_observacion_required").css("display", "block");
            $(".label_observacion").css("display", "none");
        } else {
            $(".label_observacion_required").css("display", "none");
            $(".label_observacion").css("display", "block");
        }
    });

    //Colocamos el contador de nuevos ejercicios en 1
    $('#contador_new_ejercicio').attr('value', '1');

    //Llenamos la informacion de las matriculas y los pagos.     
    $("form").delegate("#consultar_alumno", "click", function() {
        var dni = $('#dni').val();
        var id = $('#id').val();
        if ((dni != "default") && (id != "")) {
            $.post('{action_validar_alumno}', {
                dni: dni,
                id: id
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    if (obj.t_curso == null) {
                        $("#div_t_curso").css("display", "block");
                    } else {
                        $("#div_t_curso").css("display", "none");
                        $("#info_alumno").html('<center><table><tr><td><h4>Nombre del alumno: </h4></td><td><h4 class="h_negrita"> ' + obj.nombre_alumno + '</h4></td></tr><tr><td><h4>Tipo de curso: </h4></td><td><h4 class="h_negrita"> ' + obj.tipo_curso + '</h4></td></tr></table></center>');
                        $("#div_reportes_anteriores").html(obj.html_reportes);
                        $("#div_reporte").css("display", "block");
                        $('#dni').attr('disabled', 'disabled');
                        $('#id').attr('disabled', 'disabled');
                        $('#consultar_alumno').attr('disabled', 'disabled');
                        $("#div_warning").remove();
                    }
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de consultar, ingrese el tipo y número de identificación del alumno.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    }
    );

    //Llenamos la informacion de las matriculas y los pagos.
    $("form").delegate("#actualizar_t_curso", "click", function() {
        var dni = $('#dni').val();
        var id = $('#id').val();
        var tCurso = $('#t_curso').val();
        if ((dni != "default") && (id != "") && (tCurso != "default")) {
            $.post('{action_actualizar_t_curso}', {
                dni: dni,
                id: id,
                tCurso: tCurso
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#consultar_alumno").click();
                } else {
                    $("#validacion_t_curso").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_t_curso").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de actualizar, seleccione el tipo del curso del alumno.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });

    $("#div_reporte").delegate("#agregar_ejercicio", "click", function() {
        //Agregamos un ejercicio de nomina
        var dni = $('#dni').val();
        var id = $('#id').val();
        var idUltimoEjercicio = $('#contador_new_ejercicio').val();
        $.post('{action_llena_agregar_ejercicio}', {
            dni: dni,
            id: id,
            idUltimoEjercicio: idUltimoEjercicio
        }, function(data) {
            if (data != "") {
                $("#div_ejercicios").append(data);
                var aumentarId = (new Number($('#contador_new_ejercicio').val()) + 1);
                $('#contador_new_ejercicio').attr('value', aumentarId);
            }
        });
    });

    //Eliminamos los conceptos y preguntamos antes
    $("#div_reporte").delegate(".drop_new_ejercicio", "click", function() {
        if (confirm('¿Está seguro que desea eliminar el ejercicio realizado?')) {
            $("#div_new_ejercicio_" + $(this).attr('id')).remove();
            calcular_total();
        }
    });

    $("#div_reporte").delegate("#t_habilidad", "change", function() {
        var dni = $('#dni').val();
        var id = $('#id').val();
        var habilidad = $(this).val();
        var idDivEjercicio = $(this).parent().parent().parent().parent().attr('id');
        $.post('{action_llena_ejercicio_habilidad}', {
            dni: dni,
            id: id,
            habilidad: habilidad
        }, function(data) {
            $("#" + idDivEjercicio).find("#t_ejercicio").removeAttr("disabled");
            $("#" + idDivEjercicio).find("#t_ejercicio").html(data);
            $("#" + idDivEjercicio).find("#t_ejercicio").prepend('<option value="default" selected>Seleccione Ejercicio</option>');
        });
    });

    $("#div_reporte").delegate(".ver-detalles", "click", function() {
        $("#modalDetalles").modal();
        $("#divM_ejercicios").html($(this).data("ejercicios"));
        $("#divM_meta_v").html($(this).data("meta_v"));
        $("#divM_meta_c").html($(this).data("meta_c"));
        $("#divM_meta_r").html($(this).data("meta_r"));
        $("#divM_observacion_interna").html($(this).data("observacion_interna"));
        $("#divM_observacion_titular_alumno").html($(this).data("observacion_titular_alumno"));
        $("#divM_responsable").html($(this).data("responsable"));
        $("#divM_fecha_trans").html($(this).data("fecha_trans"));
    });

    //Validamos el formulario antes de enviarlo por submit
    //Enviar formulario por ajax
    $('#btn_validar').live('click', function() {
        $('#dni').removeAttr("disabled");
        $('#id').removeAttr("disabled");
        $.ajax({
            type: "POST",
            url: $("#action_validar").attr("value"),
            cache: false,
            data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $('#dni').attr('disabled', 'disabled');
                    $('#id').attr('disabled', 'disabled');
                    $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                    $("#div_alert").html(data);
                    $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $('#dni').attr('disabled', 'disabled');
                $('#id').attr('disabled', 'disabled');
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p>Hubo un error en la peticion al servidor</p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });

</script>