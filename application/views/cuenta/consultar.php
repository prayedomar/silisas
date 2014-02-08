<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar cuentas <span class="help-block pull-right">(<?= $cantidad ?> cuentas encontrados)</span></legend>
                <div id="divCriterios">
                    <div  class="row">
                        <div class="col-xs-2">
                            <label>Número de cuenta</label>
                            <input type='text' id="numero_cuenta" class='form-control letras_numeros' placeholder="Número de cuenta" value="<?= isset($_GET["numero_cuenta"]) ? $_GET["numero_cuenta"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Tipo de cuenta</label>
                            <select id="cuenta" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($tipos_cuentas as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["cuenta"]) && $_GET["cuenta"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Banco</label>
                            <select id="banco" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_bancos as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["banco"]) && $_GET["banco"] == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Nombre de la cuenta</label>
                            <input type='text' id="nombre_cuenta" class='form-control letras_numeros' placeholder="Nombre de la cuenta" value="<?= isset($_GET["nombre_cuenta"]) ? $_GET["nombre_cuenta"] : "" ?>">
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
                            <a class='btn btn-primary' href="<?= base_url() ?>cuenta/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Número de cuenta</th>
                                    <th>Tipo de cuenta</th>
                                    <th>Banco</th>
                                    <th>Nombre de la cuenta</th>
                                    <th>Vigente</th>
                                    <th>Observación</th>
                                    <th>Fecha creación</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista as $row) { ?>
                                    <tr>
                                        <td><?= $row->id ?></td>
                                        <td><?= $row->tipo_cuenta ?></td>
                                        <td><?= $row->nombre_banco ?></td>
                                        <td><?= $row->nombre_cuenta ?></td>
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
                             data-numerocuenta="<?= isset($_GET["numero_cuenta"]) ? $_GET["numero_cuenta"] : "" ?>"
                             data-cuenta="<?= isset($_GET["cuenta"]) ? $_GET["cuenta"] : "" ?>"
                             data-banco="<?= isset($_GET["banco"]) ? $_GET["banco"] : "" ?>"
                             data-nombrecuenta="<?= isset($_GET["nombre_cuenta"]) ? $_GET["nombre_cuenta"] : "" ?>"
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
