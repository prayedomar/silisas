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
        if ($("#t_curso").val() != null && $("#t_curso").val() != "") {
            url += "t_curso=" + $("#t_curso").val() + "&";
        }   
        if ($("#asistencia").val() != null && $("#asistencia").val() != "") {
            url += "asistencia=" + $("#asistencia").val() + "&";
        } 
        if ($("#practicas").val() != null && $("#practicas").val() != "") {
            url += "practicas=" + $("#practicas").val() + "&";
        } 
        if ($("#avanzo").val() != null && $("#avanzo").val() != "") {
            url += "avanzo=" + $("#avanzo").val() + "&";
        }         
        if ($("#id_alumno").val() != null && $("#id_alumno").val() != "") {
            url += "id_alumno=" + $("#id_alumno").val() + "&";
        }
        if ($("#id_dni_responsable").val() != null && $("#id_dni_responsable").val() != "") {
            url += "id_dni_responsable=" + $("#id_dni_responsable").val() + "&";
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
        if ($("#paginacion").data("t_curso") != "") {
            url += "t_curso=" + $("#paginacion").data("t_curso") + "&";
        }  
        if ($("#paginacion").data("asistencia") != "" || $("#paginacion").data("asistencia") == "0") {
            url += "asistencia=" + $("#paginacion").data("asistencia") + "&";
        }
        if ($("#paginacion").data("practicas") != "" || $("#paginacion").data("practicas") == "0") {
            url += "practicas=" + $("#paginacion").data("practicas") + "&";
        }
        if ($("#paginacion").data("avanzo") != "" || $("#paginacion").data("avanzo") == "0") {
            url += "avanzo=" + $("#paginacion").data("avanzo") + "&";
        }        
        if ($("#paginacion").data("id_alumno") != "") {
            url += "id_alumno=" + $("#paginacion").data("id_alumno") + "&";
        }
        if ($("#paginacion").data("id_dni_responsable") != "") {
            url += "id_dni_responsable=" + $("#paginacion").data("id_dni_responsable") + "&";
        }
        url = url.substr(0, url.length - 1);
        window.location.href = url;
    });
    $("#bodyTabla button").click(function() {
        $("#divEtapa").html($(this).data("etapa"));
        $("#divFase").html($(this).data("fase"));
        $("#divPracticas").html($(this).data("practicas"));
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