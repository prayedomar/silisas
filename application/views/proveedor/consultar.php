<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar proveedores <span class="help-block pull-right">(<?= $cantidad_empleados ?> proveedores encontrados)</span></legend>
                <div id="divCriterios">
                    <div  class="row">
                        <div class="col-xs-2  col-xs-offset-2">
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
                            <input type='text' id="numero_documento" class='form-control numerico' placeholder="Num. de documento<" value="<?= isset($_GET["numero_documento"]) ? $_GET["numero_documento"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Razon social</label>
                            <input type='text' id="razon_social" class='form-control letras_numeros' placeholder="Razón social" value="<?= isset($_GET["razon_social"]) ? $_GET["razon_social"] : "" ?>">
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <a class='btn btn-primary' href="<?= base_url() ?>proveedor/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-2 col-xs-offset-2">
                            <label>Pais</label>
                            <select id="pais" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($paises as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["pais"]) && $_GET["pais"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Departamento </label>
                            <select id="departamento" class="form-control" <?= empty($_GET["departamento"]) && empty($_GET["pais"]) ? "disabled" : "" ?>>
                                <?php if (!empty($_GET["departamento"]) || !empty($_GET["pais"])) { ?>
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($departamentos as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= isset($_GET["departamento"]) && $_GET["departamento"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Ciudad </label>
                            <select id="ciudad" class="form-control" <?= empty($_GET["ciudad"]) && empty($_GET["departamento"]) ? "disabled" : "" ?>>
                                <?php if (!empty($_GET["ciudad"]) || !empty($_GET["departamento"])) { ?>
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($ciudades as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= isset($_GET["ciudad"]) && $_GET["ciudad"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                        <?php
                                    }
                                }
                                ?>
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
                                    <th>Razón social</th>
                                    <th>Domicilio</th>
                                    <th>Teléfono</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista_alumnos as $row) { ?>
                                    <tr>
                                        <td><?= $row->abreviacion . $row->documento ?><?= !empty($row->d_v)? "-".$row->d_v :"" ?></td>
                                        <td><?= $row->razon_social ?></td>
                                        <td><?= $row->pais . "/" . $row->provincia . "/" . $row->ciudad . " - " . $row->tipo_domicilio . "/" . $row->direccion ?></td>
                                        <td><?= $row->telefono ?></td>  
                                        <td><?= $row->observacion ?></td> 
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
                             data-razonsocial="<?= isset($_GET["razon_social"]) ? $_GET["razon_social"] : "" ?>"
                             data-pais="<?= isset($_GET["pais"]) ? $_GET["pais"] : "" ?>"
                             data-departamento="<?= isset($_GET["departamento"]) ? $_GET["departamento"] : "" ?>"
                             data-ciudad="<?= isset($_GET["ciudad"]) ? $_GET["ciudad"] : "" ?>">
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