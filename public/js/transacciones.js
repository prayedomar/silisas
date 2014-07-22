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
        if ($("#documento").val() != "") {
            url += "documento=" + $("#documento").val() + "&";
        }
        if ($("#tipo_trans").val() != "") {
            url += "tipo_trans=" + $("#tipo_trans").val() + "&";
        }
        if ($("#efectivo_bancos").val() != "") {
            url += "efectivo_bancos=" + $("#efectivo_bancos").val() + "&";
        }
        if ($("#credito_debito").val() != "") {
            url += "credito_debito=" + $("#credito_debito").val() + "&";
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
        if ($("#paginacion").data("documento") != "") {
            url += "documento=" + $("#paginacion").data("documento") + "&";
        }
        if ($("#paginacion").data("tipotrans") != "") {
            url += "tipo_trans=" + $("#paginacion").data("tipotrans") + "&";
        }
        if ($("#paginacion").data("efectivo_bancos") != "") {
            url += "efectivo_bancos=" + $("#paginacion").data("efectivo_bancos") + "&";
        }
        if ($("#paginacion").data("credito_debito") != "") {
            url += "credito_debito=" + $("#paginacion").data("credito_debito") + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
    $("#bodyTabla button").click(function() {
        $("#divVigente").html($(this).data("vigente"));
        $("#detalles_html").html($(this).data("detalles_html"));
        $("#modalDetalles").modal("show");
    });
    $("#toExcel").click(function() {
        var url = "excel?";

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
        if ($("#documento").val() != "") {
            url += "documento=" + $("#documento").val() + "&";
        }
        if ($("#tipo_trans").val() != "") {
            url += "tipo_trans=" + $("#tipo_trans").val() + "&";
        }
        if ($("#efectivo_bancos").val() != "") {
            url += "efectivo_bancos=" + $("#efectivo_bancos").val() + "&";
        }
        if ($("#credito_debito").val() != "") {
            url += "credito_debito=" + $("#credito_debito").val() + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
});