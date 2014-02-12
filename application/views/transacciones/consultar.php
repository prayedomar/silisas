<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar transacciones <span class="help-block pull-right">(<?= $cantidad ?> transacciones encontrados)</span></legend>
                <div id="divCriterios">
                    <div  class="row">
                        <div class="col-xs-2 col-xs-offset-1">
                            <label>Desde</label>
                            <div class="input-group">
                                <input id="desde"  type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Desde" value="<?= isset($_GET["desde"]) ? $_GET["desde"] : "" ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <label>Hasta</label>
                            <div class="input-group">
                                <input id="hasta"  type="text" class="soloclick datepicker form-control exit_caution input_fecha" data-date-format="yyyy-mm-dd" placeholder="Hasta" value="<?= isset($_GET["hasta"]) ? $_GET["hasta"] : "" ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
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
                            <label>Id</label>
                            <input type='text' id="id" class='form-control letras_numeros' placeholder="Id" value="<?= isset($_GET["id"]) ? $_GET["id"] : "" ?>">
                        </div>

                        <div class="col-xs-1">
                            <br>
                            <button id="searchBtn" class='btn btn-primary'> <span class="glyphicon glyphicon-search"></span></button>
                        </div>
                        <div class="col-xs-1">
                            <br>
                            <a class='btn btn-primary' href="<?= base_url() ?>transacciones/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-xs-2 col-xs-offset-1">
                            <label>Caja</label>
                            <select id="caja" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($listar_cajas as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["caja"]) && $_GET["caja"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Vigente</label>
                            <select id="vigente" class="form-control">
                                <option value="1">Si</option>
                                <option value="0" <?= isset($_GET["vigente"]) && $_GET["vigente"] == "0" ? "selected" : "" ?>>No</option>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Tip. doc. responsable</label>
                            <select id="tipo_documento" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($tipos_documentos as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["tipo_documento"]) && $_GET["tipo_documento"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Doc. responsable</label>
                            <input type='text' id="documento" class='form-control letras_numeros' placeholder="Documento" value="<?= isset($_GET["documento"]) ? $_GET["documento"] : "" ?>">
                        </div>
                        <!--                        <div class="col-xs-2">
                                                    <label>Total</label>
                                                   <label><?= $total ?></label>
                                                </div>-->
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Fecha creacion</th>
                                    <th>Sede</th>
                                    <th>Id</th>
                                    <th>T. transacción</th>
                                    <th>Caja</th>
                                    <th>Efectivo en caja</th>
                                    <th>Cuenta</th>
                                    <th>Valor en cuenta</th>
                                    <th>Vigente</th>
                                    <th>Observacion</th>

                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista as $row) { ?>
                                    <tr class="<?php if ($row->nombre_tabla == "Adelanto" || $row->nombre_tabla == "Prestamo" || $row->nombre_tabla == "Egreso" || $row->nombre_tabla == "Nomina" || $row->nombre_tabla == "Nota credito")
                                    echo "danger";
                                else
                                    echo "success";
                                ?>">
                                        <td><?= $row->fecha_trans ?></td>
                                        <td><?= $row->sede ?></td>
                                        <td><?= $row->prefijo . " " . $row->id ?></td>
                                        <td><?= $row->nombre_tabla ?></td>
                                        <td><?= $row->caja ?></td>
                                        <td><?= $row->efectivo ?></td>
                                        <td><?= $row->num_cuenta ?></td>
                                        <td><?= $row->consignado ?></td>
                                        <td><?= $row->vigente == 1 || $row->vigente == 2 ? "Vigente" : "No vigente" ?></td>
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
                             data-desde="<?= isset($_GET["desde"]) ? $_GET["desde"] : "" ?>"
                             data-hasta="<?= isset($_GET["hasta"]) ? $_GET["hasta"] : "" ?>"
                             data-sede="<?= isset($_GET["sede"]) ? $_GET["sede"] : "" ?>"
                             data-id="<?= isset($_GET["id"]) ? $_GET["id"] : "" ?>"
                             data-caja="<?= isset($_GET["caja"]) ? $_GET["caja"] : "" ?>"
                             data-vigente="<?= isset($_GET["vigente"]) ? $_GET["vigente"] : "" ?>"
                             data-tipodocumento="<?= isset($_GET["tipo_documento"]) ? $_GET["tipo_documento"] : "" ?>"
                             data-documento="<?= isset($_GET["documento"]) ? $_GET["documento"] : "" ?>">


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