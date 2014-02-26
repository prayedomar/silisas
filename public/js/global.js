$(function() {
    $(".cumpleanios").datepicker({
        changeMonth:true,
        dateFormat: "mm-dd",
        monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        dayNamesMin: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"]
    });
    $(".datepicker").datepicker({
        dateFormat: "yyyy-mm-dd",
        changeYear:true,
        monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        dayNamesMin: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"]
    });
});