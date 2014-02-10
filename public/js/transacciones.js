$(function() {

    $("#searchBtn").click(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
        var url = "consultar?";
        if ($("#desde").val() != "") {
            url += "desde=" + $("#desde").val() + "&";
        }
        if ($("#hasta").val() != "") {
            url += "hasta=" + $("#hasta").val() + "&";
        }
        if ($("#sede").val() != null && $("#sede").val() != "") {
            url += "sede=" + $("#sede").val() + "&";
        }
        if ($("#id").val() != null && $("#id").val() != "") {
            url += "id=" + $("#id").val() + "&";
        }
        if ($("#caja").val() != "") {
            url += "caja=" + $("#caja").val() + "&";
        }
        if ($("#vigente").val() == "0") {
            url += "vigente=" + $("#vigente").val() + "&";
        }
        if ($("#tipo_documento").val() != "") {
            url += "tipo_documento=" + $("#tipo_documento").val() + "&";
        }
        if ($("#documento").val() != "") {
            url += "documento=" + $("#documento").val() + "&";
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

});