$(function() {

    $("#searchBtn").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        var url = "consultar?";
        if ($("#nombre").val() != "") {
            url += "nombre=" + $("#nombre").val() + "&";
        }
        if ($("#pais").val() != "") {
            url += "pais=" + $("#pais").val() + "&";
        }
        if ($("#departamento").val() != null && $("#departamento").val() != "") {
            url += "departamento=" + $("#departamento").val() + "&";
        }
        if ($("#ciudad").val() != null && $("#ciudad").val() != "") {
            url += "ciudad=" + $("#ciudad").val() + "&";
        }
        if ($("#estado").val() != "") {
            url += "estado=" + $("#estado").val() + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });


    $("#divCriterios .form-control").keypress(function(e) {
        if (e.which == 13) {
            $("#searchBtn").trigger("click");
        }
    });

    $("#pais").change(function() {
        if ($(this).val() == "") {
            $("#departamento").html("").prop("disabled", true);
            $("#ciudad").html("").prop("disabled", true);
        } else {
            $.ajax({
                url: "listar_departamentos",
                data: {
                    idPais: $(this).val(),
                },
                type: 'GET',
                success: function(data) {
                    if (data != "[]") {
                        data = JSON.parse(data);
                        var str = "<option value=''>Seleccionar...</option>";
                        $.each(data, function(k, l) {
                            str += "<option value='" + l.id + "'>" + l.nombre + "</option>";
                        });
                        $("#departamento").html(str).prop("disabled", false)
                    }
                }
            });
        }
    });
    $("#departamento").change(function() {
        if ($(this).val() == "") {
            $("#ciudad").html("").prop("disabled", true);
        } else {
            $.ajax({
                url: "listar_ciudades",
                data: {
                    idDepartamento: $(this).val(),
                },
                type: 'GET',
                success: function(data) {
                    if (data != "[]") {
                        data = JSON.parse(data);
                        var str = "<option value=''>Seleccionar...</option>";
                        $.each(data, function(k, l) {
                            str += "<option value='" + l.id + "'>" + l.nombre + "</option>";
                        });
                        $("#ciudad").html(str).prop("disabled", false)
                    }
                }
            });
        }
    });

    $("#paginacion li.noActive a").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        if ($(this).data("page") == 1) {
            url = "consultar?";
        } else {
            var url = "consultar?page=" + $(this).data("page") + "&";
        }

        if ($("#nombre").val() != "") {
            url += "nombre=" + $("#nombre").val() + "&";
        }
        if ($("#pais").val() != "") {
            url += "pais=" + $("#pais").val() + "&";
        }
        if ($("#departamento").val() != null && $("#departamento").val() != "") {
            url += "departamento=" + $("#departamento").val() + "&";
        }
        if ($("#ciudad").val() != null && $("#ciudad").val() != "") {
            url += "ciudad=" + $("#ciudad").val() + "&";
        }
        if ($("#estado").val() != "") {
            url += "estado=" + $("#estado").val() + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });

    //PAra cargar los selects dinamicos de pais y departamento
    $("#pais-modal").live("change", function() {
        pais = $('#pais-modal').val();
        $.post('llena_provincia', {
            pais: pais
        }, function(data) {
            $("#provincia-modal").removeAttr("disabled");
            $("#provincia-modal").html(data);
            $("#provincia-modal").prepend('<option value="default" selected>Seleccione Departamento</option>');
            //Con esto activamos automaticamente el evento click como si lo hicieramos nosotros.
            $("#provincia-modal").change();
        });
    });
    $("#provincia-modal").live("change", function() {
        provincia = $('#provincia-modal').val();
        $.post('llena_ciudad', {
            provincia: provincia
        }, function(data) {
            $("#ciudad-modal").removeAttr("disabled")
            $("#ciudad-modal").html(data);
            $("#ciudad-modal").prepend('<option value="default" selected>Seleccione Ciudad</option>');
        });
    });

    $("table .editar").click(function() {
        document.getElementById("formulario").reset();
        $("#id_sede").val($(this).data("id"));
        $("#nombre-modal").val($(this).data("nombre"));

        var id_pais = $(this).data("pais");
        var id_departamento = $(this).data("departamento");
        var id_ciudad = $(this).data("ciudad");
        $("#pais-modal").val(id_pais);

        $.post('llena_provincia', {
            pais: id_pais
        }, function(data) {
            $("#provincia-modal").removeAttr("disabled");
            $("#provincia-modal").html(data);
            $("#provincia-modal").prepend('<option value="default" selected>Seleccione Departamento</option>');
            //Con esto activamos automaticamente el evento click como si lo hicieramos nosotros.
            $("#provincia-modal").val(id_departamento);
        });

        $.post('llena_ciudad', {
            provincia: id_departamento
        }, function(data) {
            $("#ciudad-modal").removeAttr("disabled")
            $("#ciudad-modal").html(data);
            $("#ciudad-modal").prepend('<option value="default" selected>Seleccione Ciudad</option>');
            $("#ciudad-modal").val(id_ciudad);
        });
        $("#estado-modal").val($(this).data("estado"));
        $("#direccion-modal").val($(this).data("direccion"));
        $("#tel1-modal").val($(this).data("tel1"));
        $("#tel2-modal").val($(this).data("tel2"));
        $("#prefijo_trans-modal").val($(this).data("prefijo_trans"));
        $("#observacion-modal").val($(this).data("observacion"));
//        $("#modal-editar").modal("show");
        $('#modal-editar').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });         
    });

    $('#botonValidarSede').live('click', function() {
        //PAra desactivar el click al lado del modal para cerrarlo
        $(function() {
            $('#modal_loading').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });
        });
        $.ajax({
            type: "POST",
            url: "validarParaEditar",
            cache: false,
            data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
            success: function(data)
            {
                if (data != "OK") {
                    $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                    $("#div_alert").html(data);
                    $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
                    //Para cerrar el modal de loading
                    $('#modal_loading').modal('hide');
                } else {
                    $(window).unbind('beforeunload');
                    $("#btn_submit").click();
                }
            },
            error: function(data) {
                $("#validacion_alert").html('<div class="alert alert-danger" id="div_alert"></div>');
                $('#div_alert').html('<p>Hubo un error en la peticion al servidor</p>');
                $("#div_alert").prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

            }
        });
        return false; // Evitar ejecutar el submit del formulario
    });
});