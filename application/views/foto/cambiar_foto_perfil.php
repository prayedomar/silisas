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
 jQuery(function($){

    // The variable jcrop_api will hold a reference to the
    // Jcrop API once Jcrop is instantiated.
    var jcrop_api;

    // In this example, since Jcrop may be attached or detached
    // at the whim of the user, I've wrapped the call into a function
    initJcrop();
    
    // The function is pretty simple
    function initJcrop()//{{{
    {
      // Hide any interface elements that require Jcrop
      // (This is for the local user interface portion.)
      $('.requiresjcrop').hide();

      // Invoke Jcrop in typical fashion
      $('#target').Jcrop({
        onRelease: releaseCheck
      },function(){

        jcrop_api = this;
        jcrop_api.animateTo([10,10,400,300]);

        // Setup and dipslay the interface for "enabled"
        $('#can_click,#can_move,#can_size').attr('checked','checked');
        $('#ar_lock,#size_lock,#bg_swap').attr('checked',false);
        $('.requiresjcrop').show();

      });

    };
    //}}}

    // Use the API to find cropping dimensions
    // Then generate a random selection
    // This function is used by setSelect and animateTo buttons
    // Mainly for demonstration purposes
    function getRandom() {
      var dim = jcrop_api.getBounds();
      return [
        Math.round(Math.random() * dim[0]),
        Math.round(Math.random() * dim[1]),
        Math.round(Math.random() * dim[0]),
        Math.round(Math.random() * dim[1])
      ];
    };

    // This function is bound to the onRelease handler...
    // In certain circumstances (such as if you set minSize
    // and aspectRatio together), you can inadvertently lose
    // the selection. This callback re-enables creating selections
    // in such a case. Although the need to do this is based on a
    // buggy behavior, it's recommended that you in some way trap
    // the onRelease callback if you use allowSelect: false
    function releaseCheck()
    {
      jcrop_api.setOptions({ allowSelect: true });
      $('#can_click').attr('checked',false);
    };

    // Attach interface buttons
    // This may appear to be a lot of code but it's simple stuff
    $('#setSelect').click(function(e) {
      // Sets a random selection
      jcrop_api.setSelect(getRandom());
    });
    $('#animateTo').click(function(e) {
      // Animates to a random selection
      jcrop_api.animateTo(getRandom());
    });
    $('#release').click(function(e) {
      // Release method clears the selection
      jcrop_api.release();
    });
    $('#disable').click(function(e) {
      // Disable Jcrop instance
      jcrop_api.disable();
      // Update the interface to reflect disabled state
      $('#enable').show();
      $('.requiresjcrop').hide();
    });
    $('#enable').click(function(e) {
      // Re-enable Jcrop instance
      jcrop_api.enable();
      // Update the interface to reflect enabled state
      $('#enable').hide();
      $('.requiresjcrop').show();
    });
    $('#rehook').click(function(e) {
      // This button is visible when Jcrop has been destroyed
      // It performs the re-attachment and updates the UI
      $('#rehook,#enable').hide();
      initJcrop();
      $('#unhook,.requiresjcrop').show();
      return false;
    });
    $('#unhook').click(function(e) {
      // Destroy Jcrop widget, restore original state
      jcrop_api.destroy();
      // Update the interface to reflect un-attached state
      $('#unhook,#enable,.requiresjcrop').hide();
      $('#rehook').show();
      return false;
    });

    // Hook up the three image-swapping buttons
    $('#img1').click(function(e) {
      $(this).addClass('active').closest('.btn-group')
        .find('button.active').not(this).removeClass('active');

      jcrop_api.setImage('demo_files/sago.jpg');
      jcrop_api.setOptions({ bgOpacity: .6 });
      return false;
    });
    $('#img2').click(function(e) {
      $(this).addClass('active').closest('.btn-group')
        .find('button.active').not(this).removeClass('active');

      jcrop_api.setImage('demo_files/pool.jpg');
      jcrop_api.setOptions({ bgOpacity: .6 });
      return false;
    });
    $('#img3').click(function(e) {
      $(this).addClass('active').closest('.btn-group')
        .find('button.active').not(this).removeClass('active');

      jcrop_api.setImage('demo_files/sago.jpg',function(){
        this.setOptions({
          bgOpacity: 1,
          outerImage: 'demo_files/sagomod.jpg'
        });
        this.animateTo(getRandom());
      });
      return false;
    });

    // The checkboxes simply set options based on it's checked value
    // Options are changed by passing a new options object

    // Also, to prevent strange behavior, they are initially checked
    // This matches the default initial state of Jcrop

    $('#can_click').change(function(e) {
      jcrop_api.setOptions({ allowSelect: !!this.checked });
      jcrop_api.focus();
    });
    $('#can_move').change(function(e) {
      jcrop_api.setOptions({ allowMove: !!this.checked });
      jcrop_api.focus();
    });
    $('#can_size').change(function(e) {
      jcrop_api.setOptions({ allowResize: !!this.checked });
      jcrop_api.focus();
    });
    $('#ar_lock').change(function(e) {
      jcrop_api.setOptions(this.checked?
        { aspectRatio: 4/3 }: { aspectRatio: 0 });
      jcrop_api.focus();
    });
    $('#size_lock').change(function(e) {
      jcrop_api.setOptions(this.checked? {
        minSize: [ 80, 80 ],
        maxSize: [ 350, 350 ]
      }: {
        minSize: [ 0, 0 ],
        maxSize: [ 0, 0 ]
      });
      jcrop_api.focus();
    });

  });


</script>