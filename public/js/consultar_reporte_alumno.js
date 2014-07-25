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
        if ($("#id_alumno").val() != null && $("#id_alumno").val() != "") {
            url += "id_alumno=" + $("#id_alumno").val() + "&";
        }
        if ($("#id_responsable").val() != null && $("#id_responsable").val() != "") {
            url += "id_responsable=" + $("#id_responsable").val() + "&";
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
        if ($("#paginacion").data("id_alumno") != "") {
            url += "id_alumno=" + $("#paginacion").data("id_alumno") + "&";
        }
        if ($("#paginacion").data("id_responsable") != "") {
            url += "id_responsable=" + $("#paginacion").data("id_responsable") + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
    $("#bodyTabla button").click(function() {
        $("#divFase").html($(this).data("fase"));
        $("#divMeta_v").html($(this).data("meta_v") + " p.p.m");
        $("#divMeta_c").html($(this).data("meta_c") + " %");
        $("#divMeta_r").html($(this).data("meta_r") + " %");
        $("#divPracticas").html($(this).data("cant_practicas"));
        $("#divlecturas").html($(this).data("lectura"));
        $("#divVlm").html($(this).data("vlm") + " p.p.m");
        $("#divVlv").html($(this).data("vlv") + " p.p.m");
        $("#divC").html($(this).data("c") + " %");
        $("#divR").html($(this).data("r") + " %");
        $("#divEjercicios").html($(this).data("ejercicios"));
        $("#divObsTitular").html($(this).data("observacion_titular_alumno"));
        $("#divFecha_trans").html($(this).data("fecha_trans"));
//        $("#modalDetalles").modal("show");
        $('#modalDetalles').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });
    });
});