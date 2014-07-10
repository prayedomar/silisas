<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Liquidar comisiones de matrícula</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                <div class="row">
                    <div class="row">
                        <div class="col-xs-8 col-xs-offset-2 text-center">
                            <div id="div_validacion_success">
                            </div>
                        </div>
                    </div>
                    <h4 class="text-center separar_div"><B>Nota: </B>Verifique el organigrama de RRPP de su sede (jefes y cargos), antes de realizar la liquidación de una matrícula.</h4>
                    <form role="form" method="post" action="{action_crear}" id="formulario">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-4 col-xs-offset-4">
                                    <div class="form-group" id="div_matricula">
                                        <label>Número de Matrícula<em class="required_asterisco">*</em></label>
                                        <p class="help-block"><B>> </B>Sólo aparecerán las matrículas no liquidadas, que pertenecen a su sede principal y que completaron el pago de la cuota inicial.</p>
                                        <select name="matricula" id="matricula" class="form-control exit_caution">
                                            <option value="default">Seleccione matrícula a liquidar</option>
                                            <?php foreach ($matriculas_iliquidadas as $row) { ?>
                                                <option value="<?= $row->contrato ?>"><?= $row->contrato ?></option>
                                            <?php } ?> 
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            <div class="overflow_tabla separar_div"   id="div_detalle_matricula" style="display:none;">
                                <label>Detalles de la Matrícula</label>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>                                           
                                            <th class="text-center">Titular</th>
                                            <th class="text-center">Plan</th>
                                            <th class="text-center">Observación</th>
                                            <th class="text-center">Nombre Ejecutivo</th>
                                            <th class="text-center">Cargo Ejecutivo</th>
                                            <th class="text-center">Fecha Matrícula</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_detalle_matricula">
                                    </tbody>
                                </table>
                                <p class="help-block"><B>> </B>Verifique que el ejecutivo de la tabla sí sea el que realizó la matrícula, de lo contrario, modifiquelo en el campo de Comsión Directa.</p>                                
                                <p class="help-block"><B>> </B>Verifique que el cargo del ejecutivo de la tabla sí sea el correcto, de lo contrario, modifiquelo en la opción Modificar->Cargo y Jefe.</p>                                
                            </div>
                            <div id="div_comisiones"  style="display:none;">
                                <div class="row">
                                    <div class="col-xs-6 separar_div">
                                        <legend>Comisión Directa</legend>
                                        <p class="help-block"><B>> </B>Sólo aparecerán los empleados de RRPP activos que pertenecen a su sede principal.</p>                                                                        
                                        <div id="div_comision_directa">
                                            <div id="label_ejecutivo_directo"></div>
                                            <div class="form-group">
                                                <select name="ejecutivo_directo" id="ejecutivo_directo" class="form-control exit_caution">
                                                    <option value="default">Seleccione ejecutivo directo</option>
                                                    <?php foreach ($ejecutivo_directo as $row) { ?>
                                                        <option value="<?= $row->id . "+" . $row->dni . "+" . $row->cargo ?>"><?= $row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2 ?></option>
                                                    <?php } ?> 
                                                </select>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="col-xs-6 separar_div">
                                        <legend>Comisiones por Escala</legend>
                                        <div id="div_comision_escala">  
                                        </div>
                                    </div>
                                </div>
                                <div id="validacion_alert">
                                </div>                            
                                <div class="form-group separar_submit">
                                    <input type="hidden" id="action_validar" value={action_validar} />
                                    <input type="hidden" name="id_responsable" value={id_responsable} />
                                    <input type="hidden" name="dni_responsable" value={dni_responsable} />
                                    <!--Para seleccionar automaticamente el select de ejecutivos directo, el ejecuivo original de la matricula-->
                                    <input type="hidden" name="ejecutivo_original" id="ejecutivo_original"/>
                                    <center>
                                        <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                                        <button id="botonValidar" class="btn btn-success">Liquidar Comisiones de Matrícula</button>                                 
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
<!--Modal loading-->
<div class="modal" id="modal_loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_loading">
                    <div class="row text-center">
                        <div class="col-xs-2 col-xs-offset-5 separar_div">
                            <img src="<?= base_url() ?>images/loading_2.gif" class="img-responsive">
                        </div>
                        <div class="col-xs-12">
                            <h4 class="modal-title" id="myModalLabel">Estamos procesando su solicitud</h4>
                            <h6 class="modal-title" id="myModalLabel">Espere unos segundos</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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
                    $("#ejecutivo_directo").attr('value', obj.IdDniEjecutivo);

                    //Llenamos los selects de las comisiones por escala
                    ejecutivoDirecto = $('#ejecutivo_directo').val();
                    $.post('{action_llena_cargo_comision_faltante}', {
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