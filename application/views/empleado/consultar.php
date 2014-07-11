<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Consultar empleados <span class="help-block pull-right">(<?= $cantidad_empleados ?> empleados encontrados)</span></legend>
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
                            <a class='btn btn-primary' href="<?= base_url() ?>empleado/consultar"> <span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-xs-2">
                            <label>Segundo apellido</label>
                            <input type='text' id="segundo_apellido" class='form-control letras_numeros' placeholder="Segundo apellido" value="<?= isset($_GET["segundo_apellido"]) ? $_GET["segundo_apellido"] : "" ?>">
                        </div>
                        <div class="col-xs-2">
                            <label>Estado</label>
                            <select id="estado" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($estados_empleados as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["estado"]) && $_GET["estado"] == $row->id ? "selected" : "" ?>><?= $row->estado ?></option>
                                <?php } ?>
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
                        <div class="col-xs-2">
                            <label>Depto. empresarial</label>
                            <select id="depto" class="form-control">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($lista_dptos as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($_GET["depto"]) && $_GET["depto"] == $row->id ? "selected" : "" ?>><?= $row->tipo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Cargo</label>
                            <select id="cargo" class="form-control" <?= empty($_GET["depto"]) ? "disabled" : "" ?>>
                                <?php if (!empty($_GET["depto"])) { ?>
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($lista_cargos as $row) { ?>
                                        <option value="<?= $row->id ?>" <?= isset($_GET["cargo"]) && $_GET["cargo"] == $row->id ? "selected" : "" ?>><?= $row->cargo_masculino ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <br>
                            <button title="" id="toExcel" href="#" class="btn btn-success pull-right">Exportar a excel</button>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Cumpleaños (desde) </label>
                            <div class="input-group">
                                <input name="fecha_nacimiento" id="fecha_nacimiento" type="text" class="soloclick cumpleanios form-control" placeholder="mm-dd" value="<?= isset($_GET["fecha_nacimiento"]) ? $_GET["fecha_nacimiento"] : "" ?>">
                                <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div> 
                        </div>
                        <div class="col-xs-2">
                            <label>Cumpleaños (hasta)</label>
                            <div class="input-group">
                                <input name="fecha_nacimiento_hasta" id="fecha_nacimiento_hasta" type="text" class="soloclick cumpleanios form-control" data-date-format="yyyy-mm-dd" placeholder="mm-dd" value="<?= isset($_GET["fecha_nacimiento_hasta"]) ? $_GET["fecha_nacimiento_hasta"] : "" ?>">
                                <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div> 
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
                                    <th>Sede</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabla">
                                <?php foreach ($lista_empleados as $row) { ?>
                                    <tr>
                                        <td><?= $row->abreviacion . "" . $row->documento ?></td>
                                        <td><?= $row->nombre1 . " " . $row->nombre2 . " " . $row->apellido1 . " " . $row->apellido2 ?></td>
                                        <td><?= $row->fecha_nacimiento ?></td>
                                        <td><?= $row->pais . " / " . $row->provincia . " / " . $row->ciudad . " - " . $row->tipo_domicilio . " / " . $row->direccion . " / " . $row->barrio ?></td>
                                        <td><?= $row->celular . " - " . $row->telefono ?></td>
                                        <td><?= $row->email ?></td>
                                        <td><?= $row->sede ?></td>
                                        <td class="text-center"><button class="detalles btn btn-primary btn-xs" 
                                                                        data-cuenta="<?= $row->cuenta ?>" 
                                                                        data-estadoempleado="<?= $row->estado_empleado ?>" 
                                                                        data-depto="<?= $row->depto ?>"
                                                                        data-genero="<?= $row->genero ?>"
                                                                        data-cargomasculino="<?= $row->cargo_masculino ?>"
                                                                        data-cargofemenino="<?= $row->cargo_femenino ?>"
                                                                        data-salario="<?= $row->nombre_salario ?>"
                                                                        data-nombre1jefe="<?= $row->nombre1_jefe ?>"
                                                                        data-nombre2jefe="<?= $row->nombre2_jefe ?>"
                                                                        data-apellido1jefe="<?= $row->apellido1_jefe ?>"
                                                                        data-apellido2jefe="<?= $row->apellido2_jefe ?>"
                                                                        data-observacion="<?= $row->observacion ?>"
                                                                        >Ver detalles</button><br><br>
                                                                        <?php if ($_SESSION["perfil"] == "admon_sistema" || $_SESSION["perfil"] == "directivo" || $_SESSION["perfil"] == "admon_sede") { ?>
                                                <button class="editar btn btn-success btn-xs"
                                                        data-dni="<?= $row->dni ?>"
                                                        data-id="<?= $row->documento ?>"
                                                        data-nombre1="<?= $row->nombre1 ?>"
                                                        data-nombre2="<?= $row->nombre2 ?>"
                                                        data-apellido1="<?= $row->apellido1 ?>"
                                                        data-apellido2="<?= $row->apellido2 ?>"
                                                        data-fecha_nacimiento="<?= $row->fecha_nacimiento ?>"
                                                        data-genero="<?= $row->genero ?>"
                                                        data-est_civil="<?= $row->est_civil ?>"
                                                        data-id_pais="<?= $row->id_pais ?>"
                                                        data-id_departamento="<?= $row->id_provincia ?>"
                                                        data-id_ciudad="<?= $row->id_ciudad ?>"
                                                        data-t_domicilio="<?= $row->t_domicilio ?>"
                                                        data-direccion="<?= $row->direccion ?>"
                                                        data-barrio="<?= $row->barrio ?>"
                                                        data-telefono="<?= $row->telefono ?>"
                                                        data-celular="<?= $row->celular ?>"
                                                        data-email="<?= $row->email ?>"
                                                        data-cuenta="<?= $row->cuenta ?>"
                                                        data-sede_ppal="<?= $row->sede_ppal ?>"
                                                        data-depto="<?= $row->id_depto ?>"
                                                        data-cargo="<?= $row->cargo ?>"
                                                        data-salario="<?= $row->salario ?>"
                                                        >Editar</button>
                                            <?php } ?></td>
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
                             data-estado="<?= isset($_GET["estado"]) ? $_GET["estado"] : "" ?>"
                             data-sede="<?= isset($_GET["sede"]) ? $_GET["sede"] : "" ?>"
                             data-depto="<?= isset($_GET["depto"]) ? $_GET["depto"] : "" ?>"
                             data-cargo="<?= isset($_GET["cargo"]) ? $_GET["cargo"] : "" ?>"
                             data-fechanacimiento="<?= isset($_GET["fecha_nacimiento"]) ? $_GET["fecha_nacimiento"] : "" ?>"
                             data-fechanacimientohasta="<?= isset($_GET["fecha_nacimiento_hasta"]) ? $_GET["fecha_nacimiento_hasta"] : "" ?>">


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
<div class="modal" id="modal-editar-empleado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content modal-content-minimo">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Editar empleado</h3>
            </div>
            <form role="form" method="post" action="actualizar" id="formulario">
                <div  class="modal-body">
                    <div class="row ">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Tipo de Identificación</label>
                                            <select name="dni" id="dni-modal" class="form-control" readonly>
                                                <option value="default">Seleccione T.I.</option>

                                                <option value="1">Cedula de Ciudadania</option>

                                                <option value="2">Cedula de Extranjeria</option>

                                                <option value="3">Pasaporte</option>

                                                <option value="4">Tarjeta de Identidad</option>

                                            </select>
                                        </div>   
                                    </div>
                                    <div class="col-xs-6">  
                                        <div class="form-group">
                                            <label>Número de Identificación</label>
                                            <input name="id" id="id-modal" type="text" class="form-control numerico" placeholder="Número de Identificación" maxlength="13" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Primer Nombre<em class="required_asterisco">*</em></label>
                                            <input name="nombre1" id="nombre1-modal" type="text" class="form-control alfabeto" placeholder="Primer Nombre" maxlength="30">
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Segundo Nombre</label>
                                            <input name="nombre2" id="nombre2-modal" type="text" class="form-control alfabeto" placeholder="Segundo Nombre" maxlength="30">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Primer Apellido<em class="required_asterisco">*</em></label>
                                            <input name="apellido1" id="apellido1-modal" type="text" class="form-control alfabeto" placeholder="Primer Apellido" maxlength="30">
                                        </div> 
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Segundo Apellido</label>
                                            <input name="apellido2" id="apellido2-modal" type="text" class="form-control alfabeto" placeholder="Segundo Apellido" maxlength="30">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Fecha de Nacimiento<em class="required_asterisco">*</em></label>
                                            <div class="input-group">
                                                <input name="fecha_nacimiento" id="fecha_nacimiento-modal" type="text" class="soloclick datepicker form-control input_fecha" data-date-format="yyyy-mm-dd" placeholder="Fecha de Nacimiento">
                                                <span class="input-group-addon click_input_date"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Género<em class="required_asterisco">*</em></label>
                                            <select name="genero" id="genero-modal" class="form-control">
                                                <option value="default">Seleccione Género</option>
                                                <option value="F">Mujer</option>
                                                <option value="M">Hombre</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Estado Civil<em class="required_asterisco">*</em></label>
                                    <select name="est_civil" id="est_civil-modal" class="form-control">
                                        <option value="default">Seleccione Estado Civil</option>
                                        <?php foreach ($est_civil as $row) { ?>
                                            <option value="<?= $row->id ?>"><?= $row->estado ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>País de domicilio<em class="required_asterisco">*</em></label>
                                    <select name="pais" id="pais-modal" class="form-control">
                                        <option value="default">Seleccione País</option>

                                        <option value="49">Colombia</option>

                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Departamento de domiclio<em class="required_asterisco">*</em></label>
                                            <select name="provincia" id="provincia-modal" class="form-control" readonly="">
                                                <option value="default">Seleccione primero País</option>
                                            </select>                                
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Ciudad de domiclio<em class="required_asterisco">*</em></label>
                                            <select name="ciudad" id="ciudad-modal" class="form-control" readonly="">
                                                <option value="default">Seleccione primero Depto</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Telefonos fijos de contacto<em class="required_asterisco">*</em></label>                                    
                                            <input name="telefono" id="telefono-modal" type="text" class="form-control alfanumerico" placeholder="Anexar indicativo Ej:(034-4114107)" maxlength="40">
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Celular</label>
                                            <input name="celular" id="celular-modal" type="text" class="form-control numerico" placeholder="Celular" maxlength="10">
                                        </div>
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label>Correo Electrónico<em class="required_asterisco">*</em></label>
                                    <input name="email" id="email-modal" type="text" class="form-control email" placeholder="Correo Electrónico" maxlength="80">
                                </div>                            
                                <div class="form-group">
                                    <label>Número de Cuenta Bancaria Nomina de Sili</label>
                                    <input name="cuenta" id="cuenta-modal" type="text" class="form-control numerico" placeholder="Cuenta Bancaria de Nómina" maxlength="12">
                                </div>    
                                <div class="form-group">
                                    <label>Tipo de Domicilio<em class="required_asterisco">*</em></label>
                                    <select name="t_domicilio" id="t_domicilio-modal" class="form-control">
                                        <option value="default">Seleccione T. de Domicilio</option>

                                        <option value="1">Casa</option>

                                        <option value="2">Unidad Cerrada</option>

                                        <option value="3">Oficina</option>

                                        <option value="4">Empresa</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Dirección<em class="required_asterisco">*</em></label>
                                    <input name="direccion" id="direccion-modal" type="text" class="form-control alfanumerico" placeholder="Dirección" maxlength="80">
                                </div>
                                <div class="form-group">
                                    <label>Barrio/Sector</label>
                                    <input name="barrio" id="barrio-modal" type="text" class="form-control letras_numeros" placeholder="Barrio o Sector" maxlength="40">
                                </div>
                            </div>
                        </div>
                        <div id="validacion_alert">
                        </div>   
                    </div>
                </div>
                <div class="modal-footer">
                    <!--El boton oculto tiene que estar despues del de ajax, porq si el usuario da enter al final del formulario ejecutara el oculto, por lo menos en firefox-->                                    
                    <button id="botonValidarEmpleado" class="btn btn-success">Actualizar Empleado</button>                                 
                    <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success" style="display:none;"></button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>