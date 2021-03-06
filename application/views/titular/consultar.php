<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar titulares <span class="help-block pull-right">(<?= $cantidad_empleados ?> titulares encontrados)</span></legend>
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
                            <a class='btn btn-primary' href="<?= base_url() ?>titular/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-xs-2">
                            <label>Segundo apellido</label>
                            <input type='text' id="segundo_apellido" class='form-control letras_numeros' placeholder="Segundo apellido" value="<?= isset($_GET["segundo_apellido"]) ? $_GET["segundo_apellido"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Cumpleaños (desde)</label>
                            <div class="input-group">
                                <input name="fecha_nacimiento" id="fecha_nacimiento" type="text" class="soloclick cumpleanios form-control input_fecha" data-date-format="yyyy-mm-dd" placeholder="mm-dd" value="<?= isset($_GET["fecha_nacimiento"]) ? $_GET["fecha_nacimiento"] : "" ?>">
                                <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div> 
                        </div>
                        <div class="col-xs-2">
                            <label>Cumpleaños (hasta)</label>
                            <div class="input-group">
                                <input name="fecha_nacimiento_hasta" id="fecha_nacimiento_hasta" type="text" class="soloclick cumpleanios form-control input_fecha" data-date-format="yyyy-mm-dd" placeholder="mm-dd" value="<?= isset($_GET["fecha_nacimiento_hasta"]) ? $_GET["fecha_nacimiento_hasta"] : "" ?>">
                                <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div> 
                        </div>
                        <div class="col-xs-2">
                            <label>Vigente</label>
                            <select id="vigente" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="0" <?= isset($_GET["vigente"]) && $_GET["vigente"] == "0" ? "selected" : "" ?>>No</option>
                                <option value="1" <?= isset($_GET["vigente"]) && $_GET["vigente"] == "1" ? "selected" : "" ?>>Si</option>
                            </select>
                        </div>
                        <div class="col-xs-2"></div>
                        <div class="col-xs-2">
                            <br>
                            <button title="" id="toExcel" href="#" class="btn btn-success pull-right">Exportar a excel</button>
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
                                    <th>Fecha de nacimiento</th>
                                    <th>Domicilio</th>
                                    <th>Teléfonos</th>
                                    <th>Email</th>
                                    <!--<th>Vigente</th>-->
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista_empleados as $row) { ?>
                                    <tr>
                                        <td><?= $row->tipo ?></td>
                                        <td><?= $row->documento ?></td>
                                        <td><?= $row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2 ?></td>
                                        <td><?= $row->fecha_nacimiento ?></td>
                                        <td><?= $row->pais . " / " . $row->provincia . " / " . $row->ciudad . " - " . $row->tipo_domicilio . " / " . $row->direccion . " / " . $row->barrio ?></td>
                                        <td><?= $row->celular . " - " . $row->telefono ?></td>
                                        <td><?= $row->email ?></td>
                                        <!--<td><?= $row->vigente == "1" ? "Si" : "No" ?></td>-->
                                        <td class="text-center" style="vertical-align:middle;">
                                            <button class="editar btn btn-success btn-sm"
                                                    data-dni="<?= $row->dni ?>"
                                                    data-id="<?= $row->documento ?>"
                                                    data-nombre1="<?= $row->nombre1 ?>"
                                                    data-nombre2="<?= $row->nombre2 ?>"
                                                    data-apellido1="<?= $row->apellido1 ?>"
                                                    data-apellido2="<?= $row->apellido2 ?>"
                                                    data-fecha_nacimiento="<?= $row->fecha_nacimiento ?>"
                                                    data-genero="<?= $row->genero ?>"
                                                    data-id_pais="<?= $row->id_pais ?>"
                                                    data-id_departamento="<?= $row->id_provincia ?>"
                                                    data-id_ciudad="<?= $row->id_ciudad ?>"
                                                    data-t_domicilio="<?= $row->t_domicilio ?>"
                                                    data-direccion="<?= $row->direccion ?>"
                                                    data-barrio="<?= $row->barrio ?>"
                                                    data-telefono="<?= $row->telefono ?>"
                                                    data-celular="<?= $row->celular ?>"
                                                    data-email="<?= $row->email ?>"
                                                    data-observacion="<?= $row->observacion ?>"
                                                    >Editar</button>
                                        </td>
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
                             data-fechanacimientohasta="<?= isset($_GET["fecha_nacimiento_hasta"]) ? $_GET["fecha_nacimiento_hasta"] : "" ?>"
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
<div class="modal" id="modal-editar-titular" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content modal-content-minimo">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Editar titular</h3>
            </div> 
            <form role="form" method="post" action="actualizar" id="formularioEditarTitular">
                <div id="bodyModalDetalles" class="modal-body">
                    <p class="required_alert"><em class="required_asterisco">*</em> Campos Obligatorios</p> 
                    <input type="hidden" name="id_old" id="id_old-modal" />
                    <input type="hidden" name="dni_old" id="dni_old-modal" />                    
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Tipo de Identificación</label>
                                        <select name="dni_new" id="dni-modal" class="form-control">
                                            <option value="">Seleccionar tipo</option>
                                            <?php foreach ($tipos_documentos as $row) { ?>
                                                <option value="<?= $row->id ?>" <?= isset($_GET["tipo_documento"]) && $_GET["tipo_documento"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>   
                                </div>
                                <div class="col-xs-6">  
                                    <div class="form-group">
                                        <label>Número de Identificación</label>
                                        <input name="id_new" id="id-modal" type="text" class="form-control numerico" placeholder="Número de Identificación" maxlength="13">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Primer Nombre<em class="required_asterisco">*</em></label>
                                        <input name="nombre1" id="nombre1-modal" type="text" class="form-control exit_caution alfabeto" placeholder="Primer Nombre" maxlength="30">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Segundo Nombre</label>
                                        <input name="nombre2" id="nombre2-modal" type="text" class="form-control exit_caution alfabeto_espacios" placeholder="Segundo Nombre" maxlength="30">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Primer Apellido<em class="required_asterisco">*</em></label>
                                        <input name="apellido1" id="apellido1-modal" type="text" class="form-control exit_caution alfabeto" placeholder="Primer Apellido" maxlength="30">
                                    </div> 
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Segundo Apellido</label>
                                        <input name="apellido2" id="apellido2-modal" type="text" class="form-control exit_caution alfabeto" placeholder="Segundo Apellido" maxlength="30">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Fecha de Nacimiento</label>
                                        <div class="input-group">
                                            <input name="fecha_nacimiento" id="fecha_nacimiento-modal" type="text" class="soloclick datepicker form-control exit_caution input_fecha hasDatepicker" data-date-format="yyyy-mm-dd" placeholder="Fecha de Nacimiento">
                                            <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Género<em class="required_asterisco">*</em></label>
                                        <select name="genero" id="genero_titular-modal" class="form-control exit_caution">
                                            <option value="default">Seleccione Género</option>
                                            <option value="F">Mujer</option>
                                            <option value="M">Hombre</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>País de domicilio<em class="required_asterisco">*</em></label>
                                <select name="pais" id="pais-modal" class="form-control exit_caution">
                                    <option value="default">Seleccione País</option>

                                    <option value="49">Colombia</option>

                                </select>
                            </div>                            
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Departamento de domiclio<em class="required_asterisco">*</em></label>
                                        <select name="provincia" id="provincia-modal" class="form-control exit_caution" disabled="">
                                            <option value="default">Seleccione primero País</option>
                                        </select>                                
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ciudad de domiclio<em class="required_asterisco">*</em></label>
                                        <select name="ciudad" id="ciudad-modal" class="form-control exit_caution" disabled="">
                                            <option value="default">Seleccione primero Depto</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tipo de Domicilio<em class="required_asterisco">*</em></label>
                                <select name="t_domicilio" id="t_domicilio-modal" class="form-control exit_caution">
                                    <option value="default">Seleccione T. de Domicilio</option>

                                    <option value="1">Casa</option>

                                    <option value="2">Unidad Cerrada</option>

                                    <option value="3">Oficina</option>

                                    <option value="4">Empresa</option>

                                </select>
                            </div>                        
                            <div id="validacion_alert">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Dirección<em class="required_asterisco">*</em></label>
                                <input name="direccion" id="direccion-modal" type="text" class="form-control exit_caution alfanumerico" placeholder="Dirección" maxlength="80">
                            </div>
                            <div class="form-group">
                                <label>Barrio/Sector</label>
                                <input name="barrio" id="barrio-modal" type="text" class="form-control exit_caution letras_numeros" placeholder="Barrio o Sector" maxlength="40">
                            </div>      
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Telefonos fijos de contacto<em class="required_asterisco">*</em></label>     
                                        <input name="telefono" id="telefono-modal" type="text" class="form-control exit_caution alfanumerico" placeholder="Anexar indicativo Ej:(034-4114107)" maxlength="40">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Celular<em class="required_asterisco">*</em></label>
                                        <input name="celular" id="celular-modal" type="text" class="form-control exit_caution numerico" placeholder="Celular" maxlength="10">
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input name="email" id="email-modal" type="text" class="form-control exit_caution email" placeholder="Correo Electrónico" maxlength="80">
                            </div>                 
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea name="observacion" id="observacion-modal" class="form-control exit_caution" rows="4" maxlength="250" placeholder="Observación..." style="max-width:100%;"></textarea>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group  pull-right">
                        <center>
                            <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->
                            <button id="botonValidarEditarTitular" class="btn btn-success">Editar titular</button> 
                            <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </center>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>