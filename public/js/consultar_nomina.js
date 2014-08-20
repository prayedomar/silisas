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
        if ($("#cuenta").val() != "") {
            url += "cuenta=" + $("#cuenta").val() + "&";
        }
        if ($("#vigente").val() == "0") {
            url += "vigente=" + $("#vigente").val() + "&";
        }
        if ($("#id_dni_empleado").val() != "") {
            url += "id_dni_empleado=" + $("#id_dni_empleado").val() + "&";
        }
        if ($("#id_dni_responsable").val() != "") {
            url += "id_dni_responsable=" + $("#id_dni_responsable").val() + "&";
        }
        if ($("#efectivo_bancos").val() != "") {
            url += "efectivo_bancos=" + $("#efectivo_bancos").val() + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
    $("#divCriterios .form-control").keypress(function(e) {
        if (e.which == 13) {
            $("#searchBtn").trigger("click");
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
        if ($("#paginacion").data("desde") != "") {
            url += "desde=" + $("#paginacion").data("desde") + "&";
        }
        if ($("#paginacion").data("hasta") != "") {
            url += "hasta=" + $("#paginacion").data("hasta") + "&";
        }
        if ($("#paginacion").data("sede") != "") {
            url += "sede=" + $("#paginacion").data("sede") + "&";
        }
        if ($("#paginacion").data("id") != "") {
            url += "id=" + $("#paginacion").data("id") + "&";
        }
        if ($("#paginacion").data("caja") != "") {
            url += "caja=" + $("#paginacion").data("caja") + "&";
        }
        if ($("#paginacion").data("cuenta") != "") {
            url += "cuenta=" + $("#paginacion").data("cuenta") + "&";
        }
        if ($("#paginacion").data("vigente") == "0") {
            url += "vigente=" + $("#paginacion").data("vigente") + "&";
        }
        if ($("#paginacion").data("id_dni_empleado") != "") {
            url += "id_dni_empleado=" + $("#paginacion").data("id_dni_empleado") + "&";
        }
        if ($("#paginacion").data("id_dni_responsable") != "") {
            url += "id_dni_responsable=" + $("#paginacion").data("id_dni_responsable") + "&";
        }
        if ($("#paginacion").data("efectivo_bancos") != "") {
            url += "efectivo_bancos=" + $("#paginacion").data("efectivo_bancos") + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
    $("#bodyTabla button").click(function() {
        $("#divVigente").html($(this).data("vigente"));
//        $("#modalDetalles").modal("show");
        //PAra mostrar el modal y desactivar el click al lado del modal para cerrarlo
        $('#modalDetalles').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });
    });
    $("#historico_pdf").click(function() {
        if ($("#cant_registros").val() < 1) {
            $("#validacion_inicial").html('<br><div class="alert alert-warning" id="div_warning"></div>');
            $("#div_warning").html("<p><strong><center>El historico PDF, sólo es permitido para consultas no vacías.</center></strong></p>");
            $("#div_warning").delay(8000).fadeOut(1000);
        } else {
            if ($("#cant_registros").val() > 50) {
                $("#validacion_inicial").html('<br><div class="alert alert-warning" id="div_warning"></div>');
                $("#div_warning").html("<p><strong><center>El historico PDF, sólo es permitido para consultas con menos de 50 registros.</center></strong></p>");
                $("#div_warning").delay(8000).fadeOut(1000);
            } else {
                var url = "?";
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
                if ($("#cuenta").val() != "") {
                    url += "cuenta=" + $("#cuenta").val() + "&";
                }
                if ($("#vigente").val() == "0") {
                    url += "vigente=" + $("#vigente").val() + "&";
                }
                if ($("#id_dni_empleado").val() != "") {
                    url += "id_dni_empleado=" + $("#id_dni_empleado").val() + "&";
                }
                if ($("#id_dni_responsable").val() != "") {
                    url += "id_dni_responsable=" + $("#id_dni_responsable").val() + "&";
                }
                if ($("#efectivo_bancos").val() != "") {
                    url += "efectivo_bancos=" + $("#efectivo_bancos").val() + "&";
                }
                url = url.substr(0, url.length - 1);
                url = $("#base_url").val() + "nomina/historico_nominas" + url;
                window.open(url, '_blank');
            }
        }
    });
});