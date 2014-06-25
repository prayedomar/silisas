$(function() {
    $(".cumpleanios").datepicker({
        changeMonth: true,
        dateFormat: "mm-dd",
        monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        dayNamesMin: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"]
    });
    $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        yearRange: 'c-100:c',
        changeYear: true,
        monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        dayNamesMin: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"]
    });
    //PAra activar el calendario desde el icono de calendario
    $(".input-group").delegate(".click_input_date", "click", function() {
        $(this).parent().find("input").datepicker('show');
    });
});