<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar matriculas <span class="help-block pull-right">(<?= $cantidad ?> matriculas encontradas)</span></legend>
                <div id="divCriterios">
                    <div  class="row">
                        <div class="col-xs-2">
                            <label>Contrato</label>
                            <input type='text' id="contrato" class='form-control letras_numeros' placeholder="Contrato" value="<?= isset($_GET["contrato"]) ? $_GET["contrato"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Fecha matr. (desde)</label>
                            <div class="input-group">
                                <input name="fecha_matricula_desde" id="fecha_matricula_desde" type="text" class="soloclick datepicker form-control input_fecha" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" value="<?= isset($_GET["fecha_matricula_desde"]) ? $_GET["fecha_matricula_desde"] : "" ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div> 
                        </div>
                        <div class="col-xs-2">
                            <label>Fecha matr. (hasta)</label>
                            <div class="input-group">
                                <input name="fecha_matricula_hasta" id="fecha_matricula_hasta" type="text" class="soloclick datepicker form-control input_fecha" data-date-format="yyyy-mm-dd" placeholder="mm-dd" value="<?= isset($_GET["fecha_matricula_hasta"]) ? $_GET["fecha_matricula_hasta"] : "" ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div> 
                        </div>
                        <div class="col-xs-2">
                            <label>ID titular</label>
                            <input type='text' id="id_titular" class='form-control' placeholder="ID titular" value="<?= isset($_GET["id_titular"]) ? $_GET["id_titular"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>ID ejecutivo</label>
                            <input type='text' id="id_ejecutivo" class='form-control' placeholder="ID ejecutivo" value="<?= isset($_GET["id_ejecutivo"]) ? $_GET["id_ejecutivo"] : "" ?>">
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <a class='btn btn-primary' href="<?= base_url() ?>matricula/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-xs-2">
                            <label>Cargo ejecutivo</label>
                            <select id="cargo_ejecutivo" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_cargos as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["cargo_ejecutivo"]) && $_GET["cargo_ejecutivo"] == $row->id ? "selected" : "" ?>><?= $row->cargo_masculino ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Plan</label>
                            <select id="plan" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_planes as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["plan"]) && $_GET["plan"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>DataCrédito</label>
                            <select id="datacredito" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="1" <?= isset($_GET["datacredito"]) && $_GET["datacredito"] == "1" ? "selected" : "" ?>>Si</option>
                                <option value="0" <?= isset($_GET["datacredito"]) && $_GET["datacredito"] == "0" ? "selected" : "" ?>>No</option>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Jurídico</label>
                            <select id="juridico" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="1" <?= isset($_GET["juridico"]) && $_GET["juridico"] == "1" ? "selected" : "" ?>>Si</option>
                                <option value="0" <?= isset($_GET["juridico"]) && $_GET["juridico"] == "0" ? "selected" : "" ?>>No</option>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Sede</label>
                            <select id="sede" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_sedes as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["sede"]) && $_GET["sede"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Estado</label>
                            <select id="estado" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($estados_alumnos as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["estado"]) && $_GET["estado"] == $row->id ? "selected" : "" ?>><?= $row->estado ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="row">
                    <div class="col-xs-2">
                        <label>ID alumno</label>
                        <input type='text' id="id_alumno" class='form-control' placeholder="ID alumno" value="<?= isset($_GET["id_alumno"]) ? $_GET["id_alumno"] : "" ?>">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table id="tabla-matriculas" class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Contrato</th>
                                    <th>Fecha matricula</th>
                                    <th>ID titular</th>
                                    <th>Nombre titular</th>
                                    <th>Plan</th>
                                    <th>Sede</th>
                                    <th >Detalles</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista as $row) { ?>
                                    <tr>
                                        <td><?= $row->contrato ?></td>
                                        <td><?= $row->fecha_matricula ?></td>
                                        <td><?= $row->nombre_dni . " " . $row->id_titular ?></td>
                                        <td><?= $row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2 ?></td>
                                        <td><?= $row->nombre_plan ?></td>
                                        <td><?= $row->nombre_sede ?></td>
                                        <td><button class="ver-detalles btn  btn-primary btn-sm" 
                                                     data-alumnos="<?= $row->lista_alumnos ?>"
                                                    data-id-ejecutivo="<?= $row->dni_titular . " " . $row->id_titular ?>"
                                                    data-nombre-ejecutivo="<?= $row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2 ?>"
                                                    data-cargo-ejecutivo="<?= $row->cargo_masculino ?>"
                                                    data-cant-materiales="<?= $row->cant_materiales_disponibles ?>"
                                                    data-datacredito="<?= $row->datacredito ?>"
                                                    data-juridico="<?= $row->juridico ?>"
                                                    data-liquidacion-escalas="<?= $row->liquidacion_escalas ?>"
                                                    data-estado="<?= $row->nombre_estado ?>"
                                                    data-observacion="<?= $row->observacion_matricula ?>"
                                                    data-fecha-creacion="<?= $row->fecha_trans ?>"
                                                    data-resposable="<?= $row->nom1res . " " . $row->nom2res . " " . $row->apell1res . " " . $row->apell2res ?>"
                                                    >Ver detalles</button></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="paginacion" class=" pull-right"
                             data-contrato="<?= isset($_GET["contrato"]) ? $_GET["contrato"] : "" ?>"
                             data-fecha_matricula_desde="<?= isset($_GET["fecha_matricula_desde"]) ? $_GET["fecha_matricula_desde"] : "" ?>"
                             data-fecha_matricula_hasta="<?= isset($_GET["fecha_matricula_hasta"]) ? $_GET["fecha_matricula_hasta"] : "" ?>"
                             data-id_titular="<?= isset($_GET["id_titular"]) ? $_GET["id_titular"] : "" ?>"
                             data-id_ejecutivo="<?= isset($_GET["id_ejecutivo"]) ? $_GET["id_ejecutivo"] : "" ?>"
                             data-cargo_ejecutivo="<?= isset($_GET["cargo_ejecutivo"]) ? $_GET["cargo_ejecutivo"] : "" ?>"
                             data-plan="<?= isset($_GET["plan"]) ? $_GET["plan"] : "" ?>"
                             data-datacredito="<?= isset($_GET["datacredito"]) ? $_GET["datacredito"] : "" ?>"
                             data-juridico="<?= isset($_GET["juridico"]) ? $_GET["juridico"] : "" ?>"
                             data-sede="<?= isset($_GET["sede"]) ? $_GET["sede"] : "" ?>"
                             data-estado="<?= isset($_GET["estado"]) ? $_GET["estado"] : "" ?>"
                             data-id_alumno="<?= isset($_GET["id_alumno"]) ? $_GET["id_alumno"] : "" ?>">


                            <ul class="pagination">
                                <li class="<?= $paginaActiva == 1 ? "active" : "noActive"; ?>">
                                    <a data-page="1">1</a></li>
                                <?php for ($i = 2; $i <= $cantidad_paginas; $i++) { ?>
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
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Detalles</h3>
            </div>
            <div id="bodyModalDetalles" class="modal-body">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Alumnos:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divIdAlumnos"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Id ejecutivo:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divIdEjecutivo"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Nombre ejecutivo:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divNombreEjecutivo"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Cargo ejecutivo:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divCargoEjecutivo"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Cantidad materiales disponibles:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divCantidadMateriales"></div></b>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Datacrédito:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divDatacredito"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Jurídico:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divJuridico"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right"> Liquidacion escalas:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divLiquidacionEscalas"></div></b>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Estado:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divEstado"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Observación:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divObservacion"></div></b>
                        </div>
                    </div>
                </div>





                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Fecha creación:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divFechaCreacion"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Resonsable:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divResposable"></div></b>
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