<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar reportes de alumnos <span class="help-block pull-right">(<?= $cantidadRegistros ?> reportes encontrados)</span></legend>
                <div id="divCriterios">
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Fecha de clase: Desde</label>
                            <div class="input-group">
                                <input id="desde"  type="text" class="soloclick datepicker form-control input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de clase: Desde" value="<?= isset($_GET["desde"]) ? $_GET["desde"] : "" ?>">
                                <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <label>Fecha de clase: Hasta</label>
                            <div class="input-group">
                                <input id="hasta"  type="text" class="soloclick datepicker form-control input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de clase: Hasta" value="<?= isset($_GET["hasta"]) ? $_GET["hasta"] : "" ?>">
                                <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <label>Sede</label>
                            <select id="sede" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_sedes as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["sede"]) && $_GET["sede"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Tipo de curso</label>
                            <select id="t_curso" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_cursos as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["t_curso"]) && $_GET["t_curso"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>                        
                    </div><br>
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Asistieron a clase</label>
                            <select id="asistencia" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="1" <?= isset($_GET["asistencia"]) && $_GET["asistencia"] == "1" ? "selected" : "" ?>>Si</option>
                                <option value="0" <?= isset($_GET["asistencia"]) && $_GET["asistencia"] == "0" ? "selected" : "" ?>>No</option>
                            </select>
                        </div> 
                        <div class="col-xs-2">
                            <label>Realizaron prácticas</label>
                            <select id="practicas" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="1" <?= isset($_GET["practicas"]) && $_GET["practicas"] == "1" ? "selected" : "" ?>>Si</option>
                                <option value="0" <?= isset($_GET["practicas"]) && $_GET["practicas"] == "0" ? "selected" : "" ?>>No</option>
                            </select>
                        </div> 
                        <div class="col-xs-2">
                            <label>Avanzaron de etapa</label>
                            <select id="avanzo" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="1" <?= isset($_GET["avanzo"]) && $_GET["avanzo"] == "1" ? "selected" : "" ?>>Si</option>
                                <option value="0" <?= isset($_GET["avanzo"]) && $_GET["avanzo"] == "0" ? "selected" : "" ?>>No</option>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <center><label>ID Alumno</label></center>
                            <input type='text' id="id_alumno" class='form-control numerico' maxlength="13" placeholder="Identificación del Alumno" value="<?= isset($_GET["id_alumno"]) ? $_GET["id_alumno"] : "" ?>">
                        </div>
                        <div class="col-xs-3">
                            <center><label>Responsable</label></center>
                            <select id="id_dni_responsable" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php
                                if (!empty($lista_empleados)) {
                                    foreach ($lista_empleados as $row) {
                                        ?>
                                        <option value="<?= $row->id . "_" . $row->dni ?>" <?= isset($_GET["id_dni_responsable"]) && $_GET["id_dni_responsable"] == ($row->id . "_" . $row->dni) ? "selected" : "" ?>><?= $row->nombres ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>                           
                        </div>                   
                    </div>                    
                    <div class="row">
                        <div class="col-xs-1 col-xs-offset-5">
                            <center>
                                <br>
                                <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                            </center>
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <a class='btn btn-primary' href="<?= base_url() ?>reporte_alumno/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th><center>Fecha clase</center></th>
                            <th><center>Alumno</center></th>
                            <th><center>¿Asistió?</center></th>
                            <th><center>¿Avanzó?</center></th>
                            <th><center>Observación interna</center></th>
                            <th><center>Sede</center></th>
                            <th><center>Responsable</center></th>
                            <th><center>Detalles</center></th>
                            </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($listaRegistros as $row) { ?>
                                    <tr>
                                        <td><center><?= $row->fecha_clase ?></center></td>
                                <td><?= $row->alumno ?></td>
                                <td><center><?= $row->asistencia == 1 ? "Si" : "No" ?></center></td>
                                <td><center><?= $row->avanzo == 1 ? "Si" : "No" ?></center></td>
                                <td><?= $row->observacion_interna ?></td>                        
                                <td><center><?= $row->nombre_sede ?></center></td>
                                <td><?= $row->responsable ?></td>
                                <td><center><button class="btn btn-primary btn-sm" 
                                                    data-etapa="<?= $row->etapa ?>" 
                                                    data-fase="<?= $row->fase ?>" 
                                                    data-practicas="<?= $row->practicas == 1 ? "Si" : "No" ?>" 
                                                    data-meta_v="<?= $row->meta_v ?>" 
                                                    data-meta_c="<?= $row->meta_c ?>"
                                                    data-meta_r="<?= $row->meta_r ?>"
                                                    data-cant_practicas="<?= $row->cant_practicas ?>"
                                                    data-lectura="<?= $row->lectura ?>"
                                                    data-vlm="<?= $row->vlm ?>"
                                                    data-vlv="<?= $row->vlv ?>"
                                                    data-c="<?= $row->c ?>"
                                                    data-r="<?= $row->r ?>"
                                                    data-ejercicios="<?= $row->lista_ejercicios ?>"
                                                    data-fecha_trans="<?= $row->fecha_trans ?>"
                                                    data-observacion_titular_alumno="<?= $row->observacion_titular_alumno ?>"
                                                    > Ver + </button></center></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="paginacion" class=" pull-right"
                             data-desde="<?= isset($_GET["desde"]) ? $_GET["desde"] : "" ?>"
                             data-hasta="<?= isset($_GET["hasta"]) ? $_GET["hasta"] : "" ?>"
                             data-sede="<?= isset($_GET["sede"]) ? $_GET["sede"] : "" ?>"
                             data-t_curso="<?= isset($_GET["t_curso"]) ? $_GET["t_curso"] : "" ?>"
                             data-id_alumno="<?= isset($_GET["id_alumno"]) ? $_GET["id_alumno"] : "" ?>"    
                             data-asistencia="<?= isset($_GET["asistencia"]) ? $_GET["asistencia"] : "" ?>"
                             data-practicas="<?= isset($_GET["practicas"]) ? $_GET["practicas"] : "" ?>"
                             data-avanzo="<?= isset($_GET["avanzo"]) ? $_GET["avanzo"] : "" ?>"
                             data-id_dni_responsable="<?= isset($_GET["id_dni_responsable"]) ? $_GET["id_dni_responsable"] : "" ?>">  
                            <ul class="pagination">
                                <li class="<?= $paginaActiva == 1 ? "active" : "noActive"; ?>">
                                    <a data-page="1">1</a></li>
                                <?php for ($i = 2; $i <= $cantidadPaginas; $i++) { ?>
                                    <li class="<?= $paginaActiva == $i ? "active" : "noActive" ?>">
                                        <a data-page="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content modal-content-minimo">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Detalles</h3>
            </div>
            <div id="bodyModalDetalles" class="modal-body">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Etapa:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divEtapa"></div></b>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Fase:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divFase"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">¿Hizo la tarea?:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divPracticas"></div></b>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Práticas Realizadas:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divPracticas"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Lecturas vistas en clase:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divlecturas"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Ejercicios Realizados:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divEjercicios"></div></b>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Velocidad Mental Actual:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divVlm"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Velocidad Verbal Actual:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divVlv"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Comprensión Actual:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divC"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Retención Actual :</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divR"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Meta Velocidad:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divMeta_v"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Meta Comprensión:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divMeta_c"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Meta Retención:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divMeta_r"></div></b>
                        </div>
                    </div>
                </div>                                
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Observación Titular y/o Alumno:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divObsTitular"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Fecha de creación:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divFecha_trans"></div></b>
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
