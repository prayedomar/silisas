<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar alumnos <span class="help-block pull-right">(<?= $cantidad_empleados ?> alumnos encontrados)</span></legend>
                <div id="divCriterios">
                    <div  class="row">
                        <div class="col-xs-2">
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
                        <div class="col-xs-2">
                            <label>Primer apellido</label>
                            <input type='text' id="primer_apellido" class='form-control letras_numeros' placeholder="Primer apellido" value="<?= isset($_GET["primer_apellido"]) ? $_GET["primer_apellido"] : "" ?>">
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <a class='btn btn-primary' href="<?= base_url() ?>cliente/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-xs-2">
                            <label>Segundo apellido</label>
                            <input type='text' id="segundo_apellido" class='form-control letras_numeros' placeholder="Segundo apellido" value="<?= isset($_GET["segundo_apellido"]) ? $_GET["segundo_apellido"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Fecha nacimiento</label>
                            <div class="input-group">
                                <input name="fecha_nacimiento" id="fecha_nacimiento" type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="F. nacimiento" value="<?= isset($_GET["fecha_nacimiento"]) ? $_GET["fecha_nacimiento"] : "" ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div> 
                        </div>

                        <div class="col-xs-2">
                            <label>Estado</label>
                            <select id="estado" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="c" <?= isset($_GET["estado"]) && $_GET["estado"] == "c" ? "selected" : "" ?>>Con préstamos</option>
                                <option value="s" <?= isset($_GET["estado"]) && $_GET["estado"] == "s" ? "selected" : "" ?>>Sin préstamos</option>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Sede ppal</label>
                            <select id="sede_ppal" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_sedes as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["sede_ppal"]) && $_GET["sede_ppal"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
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
                                    <th>Identificación</th>
                                    <th>Nombre</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>Domicilio</th>
                                    <th>Teléfonos</th>
                                    <th>Email</th>
                                    <th>Estado</th>
                                    <th>Sede ppal</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista_alumnos as $row) { ?>
                                    <tr>
                                        <td><?= $row->abreviacion . $row->documento ?></td>
                                        <td><?= $row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2 ?></td>
                                        <td><?= $row->fecha_nacimiento ?></td>     
                                        <td><?= $row->pais . " / " . $row->provincia . " / " . $row->ciudad . " - " . $row->tipo_domicilio . " / " . $row->direccion . " / " . $row->barrio ?></td>
                                        <td><?= $row->celular . " - " . $row->telefono ?></td>  
                                        <td><?= $row->email ?></td>
                                        <td><?= ($row->cantidad_prestamos>0)? "Con {$row->cantidad_prestamos} préstamo(s)":"Sin prestamos"?></td>
                                        <td><?= $row->sede ?></td>
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
                             data-fechanacimiento="<?= isset($_GET["fecha_nacimiento"]) ? $_GET["fecha_nacimiento"] : "" ?>"
                             data-estado="<?= isset($_GET["estado"]) ? $_GET["estado"] : "" ?>"
                             data-sedeppal="<?= isset($_GET["sede_ppal"]) ? $_GET["sede_ppal"] : "" ?>">


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