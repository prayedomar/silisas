<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar llamados de atención <span class="help-block pull-right">(<?= $cantidad_empleados ?> llamados de atención encontrados)</span></legend>
                <div id="divCriterios">
                    <div  class="row">
                        <div class="col-xs-2 col-xs-offset-1">
                            <label>Tipo de documento</label>
                            <select id="tipo_documento" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($tipos_documentos as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["tipo_documento"]) && $_GET["tipo_documento"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Num. de documento</label>
                            <input type='text' id="numero_documento" class='form-control letras_numeros' placeholder="Num. de documento<" value="<?= isset($_GET["numero_documento"]) ? $_GET["numero_documento"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Primer nombre</label>
                            <input type='text' id="primer_nombre" class='form-control letras_numeros' placeholder="Primer nombre" value="<?= isset($_GET["primer_nombre"]) ? $_GET["primer_nombre"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Segundo nombre</label>
                            <input type='text' id="segundo_nombre" class='form-control letras_numeros' placeholder="Segundo nombre" value="<?= isset($_GET["segundo_nombre"]) ? $_GET["segundo_nombre"] : "" ?>">
                        </div>
                      
                        <div class="col-xs-1">
                            <br>
                            <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <a class='btn btn-primary' href="<?= base_url() ?>llamado_atencion/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                          <div class="col-xs-2 col-xs-offset-1">
                            <label>Primer apellido</label>
                            <input type='text' id="primer_apellido" class='form-control letras_numeros' placeholder="Primer apellido" value="<?= isset($_GET["primer_apellido"]) ? $_GET["primer_apellido"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Segundo apellido</label>
                            <input type='text' id="segundo_apellido" class='form-control letras_numeros' placeholder="Segundo apellido" value="<?= isset($_GET["segundo_apellido"]) ? $_GET["segundo_apellido"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Tipo de sanción</label>
                            <select id="tipo_sancion" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($tipos_sanciones as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["tipo_sancion"]) && $_GET["tipo_sancion"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Vigente</label>
                            <select id="vigente" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="0" <?= isset($_GET["vigente"]) && $_GET["vigente"] == "0" ? "selected" : "" ?>>No</option>
                                <option value="1" <?= isset($_GET["vigente"]) && $_GET["vigente"] == "1" ? "selected" : "" ?>>Si</option>
                            </select>
                        </div>
                         <div class="col-xs-2">
                            <label>Sede principal</label>
                            <select id="sede" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_sedes as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["sede"]) && $_GET["sede"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Tipo documento</th>
                                    <th>Num. documento</th>
                                    <th>Nombre</th>
                                    <th>Falta laboral</th>
                                    <th>Sanción</th>
                                    <th>Vigente</th>
                                    <th>Descripción</th>
                                    <th>Fecha creación</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista_empleados as $row) { ?>
                                    <tr>
                                        <td><?= $row->tipo ?></td>
                                        <td><?= $row->documento ?></td>
                                        <td><?= $row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2 ?></td>
                                        <td><?= $row->falta ?></td>
                                        <td><?= $row->llamado ?></td>
                                        <td><?= $row->vigente == "1" ? "Si" : "No" ?></td>
                                        <td><?= $row->descripcion ?></td>
                                        <td><?= $row->fecha_trans ?></td>
                                        <td><?= $row->nom1_resposable . " " . $row->nom2_resposable . " " . $row->apell1_resposable . " " . $row->apell2_resposable ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="paginacion" class=" pull-right"
                             data-tipodocumento="<?= isset($_GET["tipo_documento"]) ? $_GET["tipo_documento"] : "" ?>"
                             data-numerodocumento="<?= isset($_GET["numero_documento"]) ? $_GET["numero_documento"] : "" ?>"
                             data-primernombre="<?= isset($_GET["primer_nombre"]) ? $_GET["primer_nombre"] : "" ?>"
                             data-segundonombre="<?= isset($_GET["segundo_nombre"]) ? $_GET["segundo_nombre"] : "" ?>"
                             data-primerapellido="<?= isset($_GET["primer_apellido"]) ? $_GET["primer_apellido"] : "" ?>"
                             data-segundoapellido="<?= isset($_GET["segundo_apellido"]) ? $_GET["segundo_apellido"] : "" ?>"
                             data-tiposancion="<?= isset($_GET["tiposancion"]) ? $_GET["tiposancion"] : "" ?>"
                             data-vigente="<?= isset($_GET["vigente"]) ? $_GET["vigente"] : "" ?>"
                              data-sede="<?= isset($_GET["sede"]) ? $_GET["sede"] : "" ?>"
                             >


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
                            <div class="text-right">Cuenta de nomina:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divCueta"></div></b>
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
                            <div class="text-right">Departamento empresarial:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divDepto"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Cargo:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divCargo"></div></b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Salario:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divSalario"></div></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <div class="text-right">Jefe:</div>   
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <b><div id="divJefe"></div></b>
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