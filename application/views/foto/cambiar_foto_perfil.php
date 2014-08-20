<div class="contenidoperm">
    <div class="row">
        <div class="col-xs-12 thumbnail">
            <div class="row">
                <legend>Cambiar foto de perfil</legend>
                <div class="row" style="margin-top:80px;">
                    <div class="col-xs-5 col-xs-offset-1">
                        <button id="link_subir_foto" class="thumbnail" style="text-decoration:none;">
                            <h1><span class="glyphicon glyphicon-arrow-up"></span> Subir foto de perfil</h1>
                        </button>
                    </div>
                    <div class="col-xs-5">
                        <button class="thumbnail" style="text-decoration:none;">
                            <h1><span class="glyphicon glyphicon-camera"></span> Tomar foto de perfil</h1>
                        </button>
                    </div>
                </div> 
                <img src="<?= $_SESSION["rutaImg"] ?>" id="target">
                <div id="preview-pane">
                    <div class="preview-container">
                        <img src="<?= $_SESSION["rutaImg"] ?>" class="jcrop-preview" alt="Preview" />
                    </div>
                </div>
                <div id="subir_foto">
                    <?= form_open_multipart('foto/subir_foto'); ?><br>
                    <label>X1 <input type="text" size="4" id="x1" name="x1" /></label>
                    <label>Y1 <input type="text" size="4" id="y1" name="y1" /></label>
                    <label>X2 <input type="text" size="4" id="x2" name="x2" /></label>
                    <label>Y2 <input type="text" size="4" id="y2" name="y2" /></label>
                    <label>W <input type="text" size="4" id="w" name="w" /></label>
                    <label>H <input type="text" size="4" id="h" name="h" /></label>
                    <div class="row">
                        <input type="file" name="foto_perfil" id="foto_perfil" size="20" class="form-control" style="display:none"  placeholder="Seleccione foto de perfil"/>                 
                        <div class="form-group separar_submit">
                            <center>
                                <button id="btn_submit" type="submit" name="submit" value="submit" class="btn btn-success">Subir foto</button>
                                <a href="<?= base_url() ?>"class="btn btn-danger" role="button"> Cancelar </a>
                            </center>
                        </div> 
                        <div id="validacion_alert">
                        </div>  
                    </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    jQuery(function($) {

        var jcrop_api;

        $('#target').Jcrop({
            bgFade: true,
            bgOpacity: .2,
            setSelect: [60, 70, 540, 330]
        }, function() {
            jcrop_api = this;
        });

        $('#fadetog').change(function() {
            jcrop_api.setOptions({
                bgFade: this.checked
            });
        }).attr('checked', 'checked');

        $('#shadetog').change(function() {
            if (this.checked)
                $('#shadetxt').slideDown();
            else
                $('#shadetxt').slideUp();
            jcrop_api.setOptions({
                shade: this.checked
            });
        }).attr('checked', false);

        // Define page sections
        var sections = {
            bgc_buttons: 'Change bgColor',
            bgo_buttons: 'Change bgOpacity',
            anim_buttons: 'Animate Selection'
        };
        // Define animation buttons
        var ac = {
            anim1: [217, 122, 382, 284],
            anim2: [20, 20, 580, 380],
            anim3: [24, 24, 176, 376],
            anim4: [347, 165, 550, 355],
            anim5: [136, 55, 472, 183]
        };
        // Define bgOpacity buttons
        var bgo = {
            Low: .2,
            Mid: .5,
            High: .8,
            Full: 1
        };
        // Define bgColor buttons
        var bgc = {
            R: '#900',
            B: '#4BB6F0',
            Y: '#F0B207',
            G: '#46B81C',
            W: 'white',
            K: 'black'
        };
        // Create fieldset targets for buttons
        for (i in sections)
            insertSection(i, sections[i]);

        function create_btn(c) {
            var $o = $('<button />').addClass('btn btn-small');
            if (c)
                $o.append(c);
            return $o;
        }

        var a_count = 1;
        // Create animation buttons
        for (i in ac) {
            $('#anim_buttons .btn-group')
                    .append(
                            create_btn(a_count++).click(animHandler(ac[i])),
                            ' '
                            );
        }

        $('#anim_buttons .btn-group').append(
                create_btn('Bye!').click(function(e) {
            $(e.target).addClass('active');
            jcrop_api.animateTo(
                    [300, 200, 300, 200],
                    function() {
                        this.release();
                        $(e.target).closest('.btn-group').find('.active').removeClass('active');
                    }
            );
            return false;
        })
                );

        // Create bgOpacity buttons
        for (i in bgo) {
            $('#bgo_buttons .btn-group').append(
                    create_btn(i).click(setoptHandler('bgOpacity', bgo[i])),
                    ' '
                    );
        }
        // Create bgColor buttons
        for (i in bgc) {
            $('#bgc_buttons .btn-group').append(
                    create_btn(i).css({
                background: bgc[i],
                color: ((i == 'K') || (i == 'R')) ? 'white' : 'black'
            }).click(setoptHandler('bgColor', bgc[i])), ' '
                    );
        }
        // Function to insert named sections into interface
        function insertSection(k, v) {
            $('#interface').prepend(
                    $('<fieldset></fieldset>').attr('id', k).append(
                    $('<legend></legend>').append(v),
                    '<div class="btn-toolbar"><div class="btn-group"></div></div>'
                    )
                    );
        }
        ;
        // Handler for option-setting buttons
        function setoptHandler(k, v) {
            return function(e) {
                $(e.target).closest('.btn-group').find('.active').removeClass('active');
                $(e.target).addClass('active');
                var opt = {};
                opt[k] = v;
                jcrop_api.setOptions(opt);
                return false;
            };
        }
        ;
        // Handler for animation buttons
        function animHandler(v) {
            return function(e) {
                $(e.target).addClass('active');
                jcrop_api.animateTo(v, function() {
                    $(e.target).closest('.btn-group').find('.active').removeClass('active');
                });
                return false;
            };
        }
        ;

        $('#bgo_buttons .btn:first,#bgc_buttons .btn:last').addClass('active');
        $('#interface').show();

    });


    //Cargar div de valor abono y cuotas  de matricula escogida     
    $("#link_subir_foto").live("click", function() {
        $("#foto_perfil").click();
    });
    //Cargamos la vista previa de la carga    
    $("#foto_perfil").live("change", function() {
        alert("cargamos la vista previa de la imgane para recortarla");
    });
</script>