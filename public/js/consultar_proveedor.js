$(function() {

    $("#searchBtn").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        var url = "consultar?";
        if ($("#tipo_documento").val() != "") {
            url += "tipo_documento=" + $("#tipo_documento").val() + "&";
        }
        if ($("#numero_documento").val() != "") {
            url += "numero_documento=" + $("#numero_documento").val() + "&";
        }
        if ($("#razon_social").val() != "") {
            url += "razon_social=" + $("#razon_social").val() + "&";
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
        if ($("#paginacion").data("tipodocumento") != "") {
            url += "tipo_documento=" + $("#paginacion").data("tipodocumento") + "&";
        }
        if ($("#paginacion").data("numerodocumento") != "") {
            url += "numero_documento=" + $("#paginacion").data("numerodocumento") + "&";
        }
        if ($("#paginacion").data("razonsocial") != "") {
            url += "razon_social=" + $("#paginacion").data("razonsocial") + "&";
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
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });

});