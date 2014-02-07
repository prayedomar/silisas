<div class="text-center">
    <footer>
        <p>&copy;2006 - 2014, <a href="http://www.sili.com.co"  target="_blank" class="blanco">SILI S.A.S  </a></p>
    </footer>
</div>
<div id="coverDisplay">
    <img id="imgLoading" src="<?= base_url() ?>public/images/loading.gif">
</div>
<?php if (isset($tab) && $tab == "consultar_sede") { ?>
    <script src='<?= base_url() ?>public/js/consultar_sede.js'></script>
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
<?php } ?>
</body>
</html>