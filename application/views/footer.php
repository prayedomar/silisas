<div class="text-center">
    <footer>
        <p>&copy;2006 - 2014, <a href="http://www.sili.com.co"  target="_blank" class="blanco">SILI S.A.S  </a></p>
    </footer>
</div>
<div id="coverDisplay">
    <img id="imgLoading" src="<?= base_url() ?>public/images/loading.gif">
</div>
<script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-47882854-1', 'webfactional.com');
    ga('send', 'pageview');

</script>
<script src='<?= base_url() ?>public/js/global.js'></script>
<?php if (isset($tab) && $tab == "consultar_sede") { ?>
    <script src='<?= base_url() ?>public/js/consultar_sede.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_reporte_alumno") { ?>
    <script src='<?= base_url() ?>public/js/consultar_reporte_alumno.js'></script>    
<?php } else if (isset($tab) && $tab == "consultar_salon") { ?>
    <script src='<?= base_url() ?>public/js/consultar_salon.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_salario") { ?>
    <script src='<?= base_url() ?>public/js/consultar_salario.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_empleado") { ?>
    <script src='<?= base_url() ?>public/js/consultar_empleado.js'></script>
<?php } else if (isset($tab) && $tab == "ausencia_laboral") { ?>
    <script src='<?= base_url() ?>public/js/ausencia_laboral.js'></script>
<?php } else if (isset($tab) && $tab == "llamado_atencion") { ?>
    <script src='<?= base_url() ?>public/js/llamado_atencion.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_titular") { ?>
    <script src='<?= base_url() ?>public/js/consultar_titular.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_alumno") { ?>
    <script src='<?= base_url() ?>public/js/consultar_alumno.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_clientes") { ?>
    <script src='<?= base_url() ?>public/js/consultar_clientes.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_proveedor") { ?>
    <script src='<?= base_url() ?>public/js/consultar_proveedor.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_caja") { ?>
    <script src='<?= base_url() ?>public/js/consultar_caja.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_cuenta") { ?>
    <script src='<?= base_url() ?>public/js/consultar_cuenta.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_transacciones") { ?>
    <script src='<?= base_url() ?>public/js/transacciones.js'></script>
<?php } else if (isset($tab) && $tab == "consultar_nomina") { ?>
    <script src='<?= base_url() ?>public/js/consultar_nomina.js'></script>    
<?php } else if (isset($tab) && $tab == "consultar_pagos_matricula") { ?>
    <script src='<?= base_url() ?>public/js/consultar_pagos_matricula.js'></script>    
<?php } else if (isset($tab) && $tab == "consultar_matricula") { ?>
    <script src='<?= base_url() ?>public/js/consultar_matricula.js'></script>
<?php } else if (isset($tab) && $tab == "cambiar_foto_perfil") { ?>
    <script src='<?= base_url() ?>public/js/jquery.Jcrop.min.js'></script>    
<?php } else if (isset($tab) && $tab == "consultar_cod_autorizacion") { ?>
    <script src='<?= base_url() ?>public/js/consultar_cod_autorizacion.js'></script>    
<?php } ?>
</body>
</html>
