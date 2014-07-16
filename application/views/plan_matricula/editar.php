<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <form role="form" method="post" action="{action_crear}" id="formulario">
                <div class="row">
                    <legend>Modificar plan de matrícula y/o sus comisiones</legend><p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <div class="row">
                                <div class="overflow_tabla">
                                    <label id="label_seleccion_plan">Seleccione plan a modificar<em class="required_asterisco">*</em></label>
                                    <label id="label_plan" style="display: none">Plan a modificar</label>
                                    <table class="table table-hover tabla-matriculas">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Escojer</th>
                                                <th class="text-center">Nombre</th>
                                                <th class="text-center">Cant de cupos</th>
                                                <th class="text-center">Valor total</th>
                                                <th class="text-center">Valor inicial</th>
                                                <th class="text-center">Valor cuotas</th>
                                                <th class="text-center">Cantidad cuotas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($t_plan as $row) { ?>
                                                <tr>
                                                    <td class="text-center"><input type="radio" name="id_plan" id="id_plan" value="<?= $row->id ?>"/></td>
                                                    <td><?= $row->nombre . ' (' . $row->anio . ')' ?></td>
                                                    <td class="text-center"><?= $row->cant_alumnos ?></td>
                                                    <td class="text-center">$<?= number_format($row->valor_total, 2, '.', ',') ?></td>
                                                    <td class="text-center">$<?= number_format($row->valor_inicial, 2, '.', ',') ?></td>
                                                    <td class="text-center">$<?= number_format($row->valor_cuota, 2, '.', ',') ?></td>
                                                    <td class="text-center"><?= $row->cant_cuotas ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="validacion_inicial">
                            </div>                            
                            <div class="row text-center separar_div">
                                <button type="button" class="btn btn-primary" id="consultar_plan_matricula"><span class="glyphicon glyphicon-search"></span> Consultar </button>
                                <a href='{action_recargar}' class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> Seleccionar otro plan a modificar </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div id="div_modificar_plan">-->
                <div id="div_modificar_plan" style="display: none">
                    <hr>
                    <div class="row">
                        <legend>Información del plan:</legend>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Nombre del plan<em class="required_asterisco">*</em></label>
                                <input name="nombre" id="nombre" type="text" class="form-control exit_caution letras_numeros" placeholder="Nombre del plan" maxlength="40">
                            </div>
                            <div class="form-group">
                                <label>Año de vigencia<em class="required_asterisco">*</em></label>
                                <input name="anio" id="anio" type="text" class="form-control exit_caution letras_numeros" placeholder="Año de vigencia" maxlength="40">
                            </div>                            
                            <div class="form-group">
                                <label>Cantidad de cupos de enseñanza<em class="required_asterisco">*</em></label>
                                <input name="cant_alumnos" id="cant_alumnos" type="text" class="form-control exit_caution numerico" placeholder="Cantidad de cupos de enseñanza" maxlength="2">
                            </div> 
                            <div class="form-group">
                                <label>Puntos para premios (real)<em class="required_asterisco">*</em></label>
                                <p class="help-block"><b>> </b>Combo vale 2, Tricombo vale 3...</p>                                
                                <input name="puntos_real" id="puntos_real" type="text" class="form-control exit_caution numerico" placeholder="Puntos real" maxlength="2">
                            </div> 
                            <div class="form-group">
                                <label>Puntos para premios (ireal)<em class="required_asterisco">*</em></label>
                                <p class="help-block"><b>> </b>Combo vale 1.5, Tricombo vale 2.25...</p>                                
                                <input name="puntos_ireal" id="puntos_ireal" type="text" class="form-control exit_caution decimal" placeholder="Puntos ireal" maxlength="4">
                            </div>                                                               
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Cantidad de cuotas<em class="required_asterisco">*</em></label>
                                <p class="help-block"><b>> </b>Sí es un plan contado, ingrese 0.</p>                                
                                <input name="cant_cuotas" id="cant_cuotas" type="text" class="form-control exit_caution numerico" placeholder="Cantidad de cuotas" maxlength="2">
                            </div>                            
                            <div class="form-group">
                                <label>Valor total<em class="required_asterisco">*</em></label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="valor_total" id="valor_total" class="form-control  exit_caution decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                </div>
                            </div>  
                            <div class="form-group">
                                <label>Valor cuota inicial<em class="required_asterisco">*</em></label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="valor_inicial" id="valor_inicial" class="form-control  exit_caution decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label>Valor cuotas<em class="required_asterisco">*</em></label>
                                <p class="help-block"><b>> </b>Sí es un plan contado, ingrese 0.</p>                                
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="valor_cuota" id="valor_cuota" class="form-control  exit_caution decimal decimal2 miles" placeholder="0.00" maxlength="12">
                                </div>
                            </div>  
                            <div class="form-group">
                                <label>Vigente para crear nuevas matrículas<em class="required_asterisco">*</em></label>
                                <select name="vigente" id="vigente" class="form-control exit_caution">
                                    <option value="default">Seleccione vigente</option>
                                    <option value="1">Vigente</option>
                                    <option value="0">No vigente</option>
                                </select>
                            </div>                              
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <legend>Comisiones directas:</legend>
                            <div id="div_comision_directa">
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-xs-6">
                            <legend>Comisiones por escalas:</legend>
                            <div id="div_comision_escala">
                            </div>                              
                        </div>
                        <div class="col-xs-6">
                            <legend>Comisiones por escalas (Con Gerente Encargado):</legend>
                            <div id="div_comision_escala_encargado">
                            </div>                              
                        </div>
                    </div>
                    <div class="row">
                        <div id="validacion_alert">
                        </div>                        
                        <div class="form-group separar_submit">
                            <input type="hidden" id="action_validar" value={action_validar} />
                            <center>
                                <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                                <button id="botonValidar" class="btn btn-success">Modificar plan</button>  
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                                <a href="<?= base_url() ?>" class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div>   
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Llenamos la informacion de las matriculas y los pagos.
    $("form").delegate("#consultar_plan_matricula", "click", function() {
        var idPlan = $("input:checked").val();
        
        if (typeof idPlan !== 'undefined') {
            $("input:checked").parent().parent().siblings().remove();
            $("#label_seleccion_plan").css("display", "none");
            $("#label_plan").css("display", "block");
            $('#consultar_plan_matricula').attr('disabled', 'disabled');
            $("#div_modificar_plan").css("display", "block");
            $.post('{action_llena_plan_matricula}', {
                idPlan: idPlan
            }, function(data) {
                var obj = JSON.parse(data);
                if (obj.respuesta == "OK")
                {
                    $("#div_comision_directa").html(obj.html_directas);
                    $("#div_comision_escala").html(obj.html_escalas);
                    $("#div_comision_escala_encargado").html(obj.html_escalas_encargado);
                    $("#nombre").attr("value", obj.nombre);
                    $("#cant_alumnos").attr("value", obj.cant_alumnos);
                    $("#anio").attr("value", obj.anio);
                    $("#valor_total").attr("value", obj.valor_total);
                    $("#valor_inicial").attr("value", obj.valor_inicial);
                    $("#valor_cuota").attr("value", obj.valor_cuota);
                    $("#cant_cuotas").attr("value", obj.cant_cuotas);
                    $("#puntos_ireal").attr("value", obj.puntos_ireal);
                    $("#puntos_real").attr("value", obj.puntos_real);
                    $("#vigente").attr("value", obj.vigente);
                } else {
                    $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
                    $("#div_warning").html(obj.mensaje);
                    $("#div_warning").delay(8000).fadeOut(1000);
                }
            });
        } else {
            $("#validacion_inicial").html('<div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>Antes de consultar, seleccione el plan de matrícula a modificar.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        }
    });
</script>