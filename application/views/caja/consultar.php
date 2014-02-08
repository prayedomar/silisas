<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar cajas <span class="help-block pull-right">(<?= $cantidad ?> cajas encontrados)</span></legend>
                <div id="divCriterios">
                    <div  class="row">
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
                            <label>Tipo de caja</label>
                            <select id="caja" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($tipos_cajas as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["caja"]) && $_GET["caja"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>T. doc. encargado</label>
                            <select id="tipo_documento" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($tipos_documentos as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["tipo_documento"]) && $_GET["tipo_documento"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Num. doc. encargado</label>
                            <input type='text' id="numero_documento" class='form-control letras_numeros' placeholder="Num. doc. encargado" value="<?= isset($_GET["numero_documento"]) ? $_GET["numero_documento"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Vigente</label>
                            <select id="vigente" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="1" <?= isset($_GET["vigente"]) && $_GET["vigente"] == "1" ? "selected" : "" ?>>Si</option>
                                <option value="0" <?= isset($_GET["vigente"]) && $_GET["vigente"] == "0" ? "selected" : "" ?>>No</option>
                            </select>
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <a class='btn btn-primary' href="<?= base_url() ?>caja/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Sede</th>
                                    <th>Caja</th>
                                    <th>Encargado</th>
                                    <th>Vigente</th>
                                    <th>Observación</th>
                                    <th>Fecha creación</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista as $row) { ?>
                                    <tr>
                                        <td><?= $row->sede ?></td>
                                        <td><?= $row->caja ?></td>
                                        <td><?= $row->nombre1_encg . " " . $row->nombre2_encg . " " . $row->apellido1_encg . " " . $row->apellido2_encg ?></td>
                                        <td><?= $row->vigente == 1 ? "Vigente" : "No vigente" ?></td>
                                        <td><?= $row->observacion ?></td>
                                        <td><?= $row->fecha_trans ?></td>
                                        <td><?= $row->nombre1_resp . " " . $row->nombre2_resp . " " . $row->apellido1_resp . " " . $row->apellido2_resp ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="paginacion" class=" pull-right"
                             data-sede="<?= isset($_GET["sede"]) ? $_GET["sede"] : "" ?>"
                             data-caja="<?= isset($_GET["caja"]) ? $_GET["caja"] : "" ?>"
                             data-tipodocumento="<?= isset($_GET["tipo_documento"]) ? $_GET["tipo_documento"] : "" ?>"
                             data-numerodocumento="<?= isset($_GET["numero_documento"]) ? $_GET["numero_documento"] : "" ?>"
                             data-vigente="<?= isset($_GET["vigente"]) ? $_GET["vigente"] : "" ?>">
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
                            <div class="text-right">Matrícula:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divMatricula"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Velocidad inicial:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divVelocidadInicial"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Comprensión inicial:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divComprensionInicial"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Curso:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divCurso"></div></b>
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
                            <div class="text-right">Fecha grados:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divFechaGrados"></div></b>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>